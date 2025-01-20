<?php

namespace App\Providers;

use App\Repositories\AssetRepository;
use App\Repositories\AssetRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ProjectRepository;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\QuoteRepository;
use App\Repositories\QuoteRepositoryInterface;
use App\Repositories\SmmeRepository;
use App\Repositories\SmmeRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\DuskServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

				//Repository
			  $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
				$this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
				$this->app->bind(QuoteRepositoryInterface::class, QuoteRepository::class);
			  $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);
			  $this->app->bind(SmmeRepositoryInterface::class, SmmeRepository::class);


		}
}