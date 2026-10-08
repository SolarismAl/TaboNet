<?php

namespace App\Providers;

use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\Product;
use App\Models\User;
use App\Policies\InquiryPolicy;
use App\Policies\ProductPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Listing::class, ProductPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Inquiry::class, InquiryPolicy::class);

        Gate::define('admin', fn (User $user) => $user->isAdmin());
        Gate::define('farmer', fn (User $user) => $user->isFarmer());
        Gate::define('buyer', fn (User $user) => $user->isBuyer());
        Gate::define('publish-harvest', fn (User $user) => ($user->isFarmer() || $user->isAdmin()) && ! $user->isSuspended());
        Gate::define('moderate-system', fn (User $user) => $user->isAdmin());
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
