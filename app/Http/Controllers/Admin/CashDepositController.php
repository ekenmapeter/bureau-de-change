<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\CashDeposit;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::with('currency')->get();
        $depositsQuery = CashDeposit::query();

        // Stats calculations
        $today = Carbon::today();
        $totalDepositsToday = (clone $depositsQuery)->whereDate('created_at', $today)->sum('amount');
        $countDepositsToday = (clone $depositsQuery)->whereDate('created_at', $today)->count();
        $totalDepositsAllTime = (clone $depositsQuery)->sum('amount');

        $depositsByCurrency = CashDeposit::with('wallet.currency')
            ->select('wallet_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('wallet_id')
            ->get();

        // Paginated history
        $deposits = $depositsQuery->with('user', 'wallet.currency')
            ->latest()
            ->paginate(10);

        return view('admin.cash_deposits.index', compact(
            'wallets',
            'deposits',
            'totalDepositsToday',
            'countDepositsToday',
            'totalDepositsAllTime',
            'depositsByCurrency'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'depositor_name' => 'nullable|string|max:255',
            'depositor_phone' => 'nullable|string|max:255',
            'depositor_email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create the deposit record
                $deposit = CashDeposit::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $request->wallet_id,
                    'amount' => $request->amount,
                    'depositor_name' => $request->depositor_name,
                    'depositor_phone' => $request->depositor_phone,
                    'depositor_email' => $request->depositor_email,
                    'description' => $request->description,
                ]);

                // Update wallet balance
                $wallet = Wallet::findOrFail($request->wallet_id);
                $wallet->balance += $request->amount;
                $wallet->save();

                // Log activity
                $currency = $wallet->currency;
                $formattedAmount = number_format($request->amount, 2);
                $logMessage = "Recorded a cash deposit of {$formattedAmount} {$currency->code}";
                if ($request->filled('depositor_name')) {
                    $logMessage .= " from {$request->depositor_name}";
                }
                $logMessage .= ".";

                LogActivity::log('cash_deposit', $logMessage);
            });

            return redirect()->route('admin.cash-deposits.index')->with('success', 'Deposit recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while recording the deposit. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
