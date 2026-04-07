<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ClearGuestUsers extends Command
{

    protected $signature = 'users:clear-guests';

    protected $description = 'Удаление гостевых аккаунтов (без email), неактивных более месяца';

    public function handle()
    {

        $dateLimit = Carbon::now()->subMonth();

        User::whereNull('email')
            ->where('updated_at', '<', $dateLimit)
            ->chunk(100, function ($guests) {
                foreach ($guests as $guest) {
                    $guest->delete();
                }
            });

        $this->info('Старые гостевые аккаунты и их игры успешно удалены!');
    }
}