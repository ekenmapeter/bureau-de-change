<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyBalance;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        // Get the current wallet balances for the top card display
        $currentWallets = Wallet::with('currency.rates')->get();

        // Get paginated daily closing balances
        $dailyBalances = DailyBalance::with('wallet.currency')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.wallets.index', [
            'currentWallets' => $currentWallets,
            'dailyBalances' => $dailyBalances,
        ]);
    }

    public function downloadHistory()
    {
        $balances = DailyBalance::with('wallet.currency')
            ->orderBy('date', 'desc')
            ->get();

        $pdf = app('dompdf.wrapper')->loadView('admin.wallets.pdf', ['balances' => $balances]);

        return $pdf->stream('wallet-balance-history.pdf');
    }

    public function api()
    {
        $wallets = Wallet::with('currency')->get()->mapWithKeys(function ($wallet) {
            return [$wallet->currency->id => $wallet->balance];
        });
        return response()->json($wallets);
    }
}
