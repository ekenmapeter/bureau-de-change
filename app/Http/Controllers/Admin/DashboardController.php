<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Rate;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\User;
use App\Models\CashDeposit;
use App\Models\ActivityLog;
use App\Models\OpeningBalance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $openingBalances = OpeningBalance::whereDate('date', $today)
            ->with('wallet.currency')
            ->get();

        $transactions = Transaction::with(['user', 'currency'])
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

        // New data
        $managers = User::where('role', 'manager')->get();
        $cashiers = User::where('role', 'cashier')->get();
        $recentCashDeposits = CashDeposit::with('user')->latest()->take(5)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'openingBalances',
            'profitToday',
            'totalPurchases',
            'purchaseCount',
            'totalSales',
            'salesCount',
            'managers',
            'cashiers',
            'transactions',
            'recentCashDeposits',
            'recentActivities'
        ));
    }
}
