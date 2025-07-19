<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\OpeningBalance;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class OpeningBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $openingBalances = OpeningBalance::with('user')->latest()->paginate(20);

        return view('opening-balances.index', compact('openingBalances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wallets = Wallet::with('currency')->get();
        return view('opening-balances.create', compact('wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amounts' => 'required|array',
            'amounts.*' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        try {
            DB::transaction(function () use ($request, $user, $today) {
                foreach ($request->amounts as $walletId => $amount) {
                    if (is_null($amount)) {
                        continue;
                    }

                    OpeningBalance::create([
                        'user_id' => $user->id,
                        'wallet_id' => $walletId,
                        'amount' => $amount,
                        'date' => $today,
                    ]);

                    // Update wallet balance
                    $wallet = Wallet::findOrFail($walletId);
                    $wallet->balance += $amount;
                    $wallet->save();

                    LogActivity::log('opening-balance', 'Recorded opening balance for ' . $wallet->currency->code);
                }
            });

            return redirect()->intended(route($user->role ? strtolower($user->role) . '.dashboard' : 'dashboard'))
                ->with('success', 'Opening balances recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while recording the opening balances. Please try again.');
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
        $openingBalance = OpeningBalance::findOrFail($id);
        $openingBalances = OpeningBalance::where('user_id', $openingBalance->user_id)
            ->whereDate('date', $openingBalance->date)
            ->with('wallet.currency')
            ->get();

        return view('opening-balances.edit', compact('openingBalances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'amounts' => 'required|array',
            'amounts.*' => 'nullable|numeric|min:0',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->amounts as $balanceId => $newAmount) {
                    $openingBalance = OpeningBalance::findOrFail($balanceId);
                    $wallet = Wallet::findOrFail($openingBalance->wallet_id);

                    // Revert old balance and apply new one
                    $wallet->balance -= $openingBalance->amount;
                    $wallet->balance += $newAmount;
                    $wallet->save();

                    // Update the opening balance record
                    $openingBalance->update([
                        'amount' => $newAmount,
                        'date' => Carbon::parse($request->date),
                    ]);

                    LogActivity::log('opening-balance', 'Updated opening balance for ' . $wallet->currency->code);
                }
            });

            return redirect()->route('opening-balances.index')
                ->with('success', 'Opening balances updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the opening balances. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $openingBalance = OpeningBalance::findOrFail($id);

                // Revert the balance from the wallet
                $wallet = Wallet::findOrFail($openingBalance->wallet_id);
                $wallet->balance -= $openingBalance->amount;
                $wallet->save();

                $openingBalance->delete();

                LogActivity::log('opening-balance', 'Deleted opening balance for ' . $wallet->currency->code);
            });

            return redirect()->route('opening-balances.index')
                ->with('success', 'Opening balance deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the opening balance. Please try again.');
        }
    }
}
