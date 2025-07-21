<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::with('rates')->get();
        return view('manager.rates.index', compact('currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'rates.*.buy_rate' => 'required|numeric|min:0',
            'rates.*.sell_rate' => 'required|numeric|min:0',
        ]);

        foreach ($request->rates as $currencyId => $rates) {
            Rate::updateOrCreate(
                ['currency_id' => $currencyId],
                [
                    'buy_rate' => $rates['buy_rate'],
                    'sell_rate' => $rates['sell_rate'],
                ]
            );
        }

        return redirect()->route('manager.rates.index')->with('success', 'Exchange rates updated successfully.');
    }
}