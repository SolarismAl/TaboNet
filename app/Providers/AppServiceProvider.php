<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->configureAuthorization();
    }

    /**
     * Configure Role-Based Access Control (RBAC) gates and policies.
     */
    protected function configureAuthorization(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Listing::class, \App\Policies\ProductPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Product::class, \App\Policies\ProductPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Inquiry::class, \App\Policies\InquiryPolicy::class);

        \Illuminate\Support\Facades\Gate::define('admin', fn (\App\Models\User $user) => $user->isAdmin());
        \Illuminate\Support\Facades\Gate::define('farmer', fn (\App\Models\User $user) => $user->isFarmer());
        \Illuminate\Support\Facades\Gate::define('buyer', fn (\App\Models\User $user) => $user->isBuyer());
        \Illuminate\Support\Facades\Gate::define('publish-harvest', fn (\App\Models\User $user) => ($user->isFarmer() || $user->isAdmin()) && ! $user->isSuspended());
        \Illuminate\Support\Facades\Gate::define('moderate-system', fn (\App\Models\User $user) => $user->isAdmin());
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
