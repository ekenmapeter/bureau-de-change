<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashDeposit;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivity;
use Carbon\Carbon;

class CashDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();
        $today = Carbon::today();

        $deposits = CashDeposit::where('user_id', $user_id)->with('wallet.currency', 'user')->latest()->paginate(10);

        $totalDepositsToday = CashDeposit::where('user_id', $user_id)
            ->whereDate('created_at', $today)
            ->sum('amount');

        $countDepositsToday = CashDeposit::where('user_id', $user_id)
            ->whereDate('created_at', $today)
            ->count();

        $totalDepositsAllTime = CashDeposit::where('user_id', $user_id)->sum('amount');

        return view('cashier.cash_deposits.index', compact('deposits', 'totalDepositsToday', 'countDepositsToday', 'totalDepositsAllTime'));
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
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'depositor_name' => 'nullable|string|max:255',
            'depositor_phone' => 'nullable|string|max:255',
            'depositor_email' => 'nullable|email|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $deposit = CashDeposit::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $request->wallet_id,
                    'amount' => $request->amount,
                    'description' => $request->description,
                    'depositor_name' => $request->depositor_name,
                    'depositor_phone' => $request->depositor_phone,
                    'depositor_email' => $request->depositor_email,
                    'type' => 'deposit'
                ]);

                $wallet = Wallet::find($request->wallet_id);
                $wallet->increment('balance', $request->amount);

                LogActivity::log('deposit', 'Cash deposit of ' . $request->amount . ' ' . $wallet->currency->code . ' recorded.');
            });

            return redirect()->route('cashier.cash-deposits.index')->with('success', 'Cash deposit recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
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
