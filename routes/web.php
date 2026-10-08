<?php

use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    $team = $user?->currentTeam ?? $user?->personalTeam();
    if (! $team) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard', ['current_team' => $team->slug]);
})->middleware(['auth']);

Route::get('price-index', function () {
    $user = auth()->user();
    $team = $user?->currentTeam ?? $user?->personalTeam();
    if (! $team) {
        return redirect()->route('login');
    }

    return redirect()->route('price-index', ['current_team' => $team->slug]);
})->middleware(['auth']);

Route::get('harvest-registry', function () {
    $user = auth()->user();
    $team = $user?->currentTeam ?? $user?->personalTeam();
    if (! $team) {
        return redirect()->route('login');
    }

    return redirect()->route('harvest-registry', ['current_team' => $team->slug]);
})->middleware(['auth']);

Route::get('trade-inquiries', function () {
    $user = auth()->user();
    $team = $user?->currentTeam ?? $user?->personalTeam();
    if (! $team) {
        return redirect()->route('login');
    }

    return redirect()->route('trade-inquiries', ['current_team' => $team->slug]);
})->middleware(['auth']);

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::view('price-index', 'price-index')->name('price-index');
        Route::view('harvest-registry', 'harvest-registry')->name('harvest-registry');
        Route::view('trade-inquiries', 'trade-inquiries')->name('trade-inquiries');
    });

require __DIR__.'/settings.php';
