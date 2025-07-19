<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Rate;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function index()
    {
        $currencies = Currency::with('rates')->where('code', '!=', 'NGN')->orderBy('name')->get();
        return view('admin.rates.index', compact('currencies'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rates' => 'required|array',
            'rates.*.buy_rate' => 'required|numeric|min:0',
            'rates.*.sell_rate' => 'required|numeric|min:0',
        ]);

        foreach ($request->rates as $currencyId => $rateData) {
            Rate::updateOrCreate(
                ['currency_id' => $currencyId],
                [
                    'buy_rate' => $rateData['buy_rate'],
                    'sell_rate' => $rateData['sell_rate'],
                ]
            );
        }

        LogActivity::log('Rates Update', Auth::user()->name . ' updated the exchange rates.');

        return redirect()->back()->with('success', 'Exchange rates updated successfully.');
    }
}
