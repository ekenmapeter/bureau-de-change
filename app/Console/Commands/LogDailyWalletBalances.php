<?php

namespace App\Console\Commands;

use App\Models\DailyBalance;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class LogDailyWalletBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balances:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logs the closing balance of each wallet at the end of the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Logging daily wallet balances...');

        $wallets = Wallet::all();
        $today = Carbon::today();

        foreach ($wallets as $wallet) {
            DailyBalance::updateOrCreate(
                [
                    'wallet_id' => $wallet->id,
                    'date' => $today,
                ],
                [
                    'balance' => $wallet->balance,
                ]
            );
        }

        $this->info('Successfully logged daily wallet balances.');
        return 0;
    }
}
