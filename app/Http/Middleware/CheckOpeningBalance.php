<?php

namespace App\Http\Middleware;

use App\Models\OpeningBalance;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOpeningBalance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $today = Carbon::today();

        if ($user) {
            $openingBalanceToday = OpeningBalance::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('wallet_id', function ($subQuery) {
                        $subQuery->select('id')->from('wallets');
                    });
            })
            ->whereDate('date', $today)
            ->exists();

            if (!$openingBalanceToday) {
                // No opening balance recorded for today, redirect to create one
                return redirect()->route('opening-balances.create')
                    ->with('info', 'Please record the opening balance for today to proceed.');
            }
        }

        return $next($request);
    }
}
