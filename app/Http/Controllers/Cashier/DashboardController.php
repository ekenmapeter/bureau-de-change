<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\OpeningBalance;
use App\Models\CashDeposit;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $openingBalances = OpeningBalance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with('wallet.currency')
            ->get();

        $hasOpeningBalance = $openingBalances->isNotEmpty();

        $transactions = Transaction::with('currency')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $purchasesToday = $transactions->where('type', 'purchase');
        $salesToday = $transactions->where('type', 'sale');

        $totalPurchases = $purchasesToday->sum('amount_in_ngn');
        $purchaseCount = $purchasesToday->count();
        $totalSales = $salesToday->sum('amount_in_ngn');
        $salesCount = $salesToday->count();

        $profitToday = $totalSales - $totalPurchases;

        return view('cashier.dashboard', compact(
            'hasOpeningBalance',
            'openingBalances',
            'profitToday',
            'totalPurchases',
            'purchaseCount',
            'totalSales',
            'salesCount',
            'transactions'
        ));
    }
}
