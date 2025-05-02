<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Interfaces\BaseInterface;
use App\Interfaces\User\UserInterface;
use App\Interfaces\DataBank\DataBankInterface;
use App\Interfaces\Transaction\TransactionInterface;
// Repositories
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\DataBank\DataBankRepository;

use App\Interfaces\Auth\AuthInterface;
use App\Services\Auth\AuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(AuthInterface::class, AuthService::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionRepository::class);
        $this->app->bind(DataBankInterface::class, DataBankRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
