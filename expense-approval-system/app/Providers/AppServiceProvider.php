<?php

namespace App\Providers;

use App\Repositories\ApprovalStage\ApprovalStageRepositoryInterface;
use App\Repositories\ApprovalStage\ApprovalStageRepository;
use App\Repositories\Approver\ApproverRepositoryInterface;
use App\Repositories\Approver\ApproverRepository;
use App\Repositories\Expense\ExpenseRepositoryInterface;
use App\Repositories\Expense\ExpenseRepository;
use App\Services\ApprovalStage\ApprovalStageService;
use App\Services\Expense\ExpenseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Binding Repositories
        $this->app->bind(
            ApprovalStageRepositoryInterface::class,
            ApprovalStageRepository::class
        );

        $this->app->bind(
            ApproverRepositoryInterface::class,
            ApproverRepository::class
        );

        $this->app->bind(
            ExpenseRepositoryInterface::class,
            ExpenseRepository::class
        );

        // Singleton Services
        $this->app->singleton(
            ExpenseService::class,
            function ($app) {
                return new ExpenseService($app->make(ExpenseRepositoryInterface::class));
            }
        );

        $this->app->singleton(
            ApprovalStageService::class,
            function ($app) {
                return new ApprovalStageService($app->make(ApprovalStageRepositoryInterface::class));
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
