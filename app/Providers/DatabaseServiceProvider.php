<?php

namespace App\Providers;

use App\Exceptions\DatabaseConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        try {
            // Attempt to connect to the database
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Database connection failed: ' . $e->getMessage());

            // Throw your custom exception
            throw new DatabaseConnectionException();
        }
    }
}
