<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FirstInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is command for first install sms app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('queue:table');
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Artisan::call('passport:install --force');
        DB::table('oauth_clients')->where('provider', 'users')->update(['provider' => 'customers']);
        Artisan::call('l5-swagger:generate');
    }
}
