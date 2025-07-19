<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\OpeningBalance;
use App\Models\User;
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

        $openingBalances = OpeningBalance::whereDate('date', $today)
            ->with('wallet.currency')
            ->get();

        $totalPurchases = Transaction::where('type', 'purchase')->whereDate('created_at', $today)->sum('amount_in_ngn');
        $purchaseCount = Transaction::where('type', 'purchase')->whereDate('created_at', $today)->count();
        $totalSales = Transaction::where('type', 'sale')->whereDate('created_at', $today)->sum('amount_in_ngn');
        $salesCount = Transaction::where('type', 'sale')->whereDate('created_at', $today)->count();

        $profitToday = $totalSales - $totalPurchases;

        $transactions = Transaction::with(['user', 'currency'])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        // New data
        $managers = User::where('role', 'manager')->get();
        $cashiers = User::where('role', 'cashier')->get();
        $recentCashDeposits = CashDeposit::with('user')->latest()->take(10)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();

        return view('manager.dashboard', compact(
            'openingBalances',
            'profitToday',
            'totalPurchases',
            'purchaseCount',
            'totalSales',
            'salesCount',
            'managers',
            'cashiers',
            'transactions'
        ));
    }
}
