<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $existingCurrencies = Currency::orderBy('name')->get();
        $allCurrencies = collect(config('currencies.list'));

        $availableCurrencies = $allCurrencies->filter(function ($currency) use ($existingCurrencies) {
            return !$existingCurrencies->contains('code', $currency['code']);
        });

        return view('manager.currencies.create', [
            'currencies' => $existingCurrencies,
            'availableCurrencies' => $availableCurrencies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|max:3|unique:currencies,code',
        ]);

        $allCurrencies = collect(config('currencies.list'));
        $selectedCurrency = $allCurrencies->firstWhere('code', $request->currency_code);

        if (!$selectedCurrency) {
            return back()->withErrors(['currency_code' => 'Invalid currency selected.'])->withInput();
        }

        $currency = Currency::create([
            'name' => $selectedCurrency['name'],
            'code' => $selectedCurrency['code'],
        ]);

        $currency->rates()->create([
            'buy_rate' => 0,
            'sell_rate' => 0,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'currency_create',
            'description' => "Created new currency: {$currency->name} ({$currency->code})",
        ]);

        return redirect()->route('manager.rates.index')->with('success', 'Currency created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('manager.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $currency->id,
            'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
        ]);

        $oldName = $currency->name;
        $oldCode = $currency->code;

        $currency->update($request->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'currency_update',
            'description' => "Updated currency: {$oldName} ({$oldCode}) to {$currency->name} ({$currency->code})",
        ]);

        return redirect()->route('manager.rates.index')->with('success', 'Currency updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'currency_delete',
            'description' => "Deleted currency: {$currency->name} ({$currency->code})",
        ]);

        $currency->delete();

        return redirect()->route('manager.rates.index')->with('success', 'Currency deleted successfully.');
    }
}
