<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\LogActivity;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user', 'currency'])->latest()->paginate(15);
        $prefix = Auth::user()->role;

        return view('transactions.index', compact('transactions', 'prefix'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allCurrencies = Currency::with('rates')->orderBy('name')->get();
        $transactionCurrencies = $allCurrencies->where('code', '!=', 'NGN');
        $prefix = Auth::user()->role;

        return view('transactions.create', compact('transactionCurrencies', 'allCurrencies', 'prefix'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:purchase,sale',
            'currency_id' => 'required|exists:currencies,id',
            'quantity' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0.01',
            'amount_in_ngn' => 'required|numeric|min:0.01',
            'amount_foreign' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $prefix = $user->role;

        try {
            DB::beginTransaction();

            $foreignWallet = Wallet::where('currency_id', $request->currency_id)->lockForUpdate()->first();
            $ngnWallet = Wallet::whereHas('currency', fn ($q) => $q->where('code', 'NGN'))->lockForUpdate()->first();

            if (!$foreignWallet || !$ngnWallet) {
                throw new \Exception('Wallet not found.');
            }

            if ($request->type === 'sale' && $foreignWallet->balance < $request->quantity) {
                throw new \Exception('Insufficient balance for the selected foreign currency.');
            }

            if ($request->type === 'purchase' && $ngnWallet->balance < $request->amount_in_ngn) {
                throw new \Exception('Insufficient NGN balance.');
            }

            if ($request->type === 'sale') {
                $foreignWallet->decrement('balance', $request->quantity);
                $ngnWallet->increment('balance', $request->amount_in_ngn);
            } else {
                $foreignWallet->increment('balance', $request->quantity);
                $ngnWallet->decrement('balance', $request->amount_in_ngn);
            }

            $currency = Currency::find($request->currency_id);
            $type = $request->type;

            $officialRate = $currency->rates->first()->rate ?? 0;
            $profit = 0;

            if ($type === 'sale') {
                // We sell at a higher rate, so profit is the difference
                $profit = ($request->rate - $officialRate) * $request->quantity;
            } else { // Purchase
                // We buy at a lower rate, customer gets less NGN
                $profit = ($officialRate - $request->rate) * $request->quantity;
            }

            Transaction::create($request->all() + [
                'user_id' => $user->id,
                'profit' => $profit,
                ]);

            LogActivity::log($type, "{$user->name} recorded a {$type} of {$request->quantity} {$currency->code}.");

            DB::commit();

            return redirect()->route($prefix . '.dashboard')->with('success', 'Transaction recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage())->withInput();
        }
    }

    public function chartData()
    {
        $purchases = Transaction::where('type', 'purchase')
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, SUM(amount_in_ngn) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $sales = Transaction::where('type', 'sale')
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, SUM(amount_in_ngn) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = collect();
        for ($i = 6; $i >= 0; $i--) {
            $labels->push(Carbon::now()->subDays($i)->format('M d'));
        }

        $purchaseData = $labels->map(function ($date) use ($purchases) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            return $purchases->get($formattedDate, 0);
        });

        $salesData = $labels->map(function ($date) use ($sales) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            return $sales->get($formattedDate, 0);
        });

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Purchases', 'data' => $purchaseData, 'borderColor' => '#ef4444', 'backgroundColor' => 'rgba(239, 68, 68, 0.2)'],
                ['label' => 'Sales', 'data' => $salesData, 'borderColor' => '#22c55e', 'backgroundColor' => 'rgba(34, 197, 94, 0.2)'],
            ],
        ]);
    }

    public function currencyDistributionChartData()
    {
        $currencyDistribution = Transaction::query()
            ->join('currencies', 'transactions.currency_id', '=', 'currencies.id')
            ->select('currencies.code', DB::raw('count(*) as total'))
            ->groupBy('currencies.code')
            ->pluck('total', 'code');

        $labels = $currencyDistribution->keys();
        $data = $currencyDistribution->values();

        $colors = $data->map(fn () => sprintf('#%06X', mt_rand(0, 0xFFFFFF)))->toArray();

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
        ]);
    }
}
