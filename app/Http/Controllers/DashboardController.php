<?php

namespace App\Http\Controllers;

use App\Models\CashDeposit;
use App\Models\Rate;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Redirect::route('admin.dashboard');
        }

        if ($user->role === 'manager') {
            return Redirect::route('manager.dashboard');
        }

        if ($user->role === 'cashier') {
            return Redirect::route('cashier.dashboard');
        }

        // Default redirect for any other roles or if role is not set
        Auth::logout();
        return Redirect::route('login')->with('error', 'Invalid user role.');
    }
}
