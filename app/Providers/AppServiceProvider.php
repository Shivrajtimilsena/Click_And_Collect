<?php

namespace App\Providers;

use App\Overrides\CustomOracleGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $connection = DB::connection('oracle');
            $connection->setSchemaGrammar(new CustomOracleGrammar($connection));
        });
    }
}
