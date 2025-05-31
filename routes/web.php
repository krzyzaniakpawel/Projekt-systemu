<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LeaguesController, LeagueClubsController, ClubController, PlayerController, FavouriteClubsController, MatchController};

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('leagues');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/leagues', [LeaguesController::class, 'index'])->name('leagues');
    Route::get('/league_details/{id}', [LeagueClubsController::class, 'show'])->name('league_details');
    Route::get('/club_details/{id}', [ClubController::class, 'show'])->name('club_details');
    Route::get('/player_details/{id}', [PlayerController::class, 'show'])->name('player_details');
    Route::get('/favourite_clubs', [FavouriteClubsController::class, 'index'])->name('favourite_clubs');
    Route::get('/match_details/{id}', [MatchController::class, 'show'])->name('match_details');

    Route::post('/favourite_clubs/add', [FavouriteClubsController::class, 'add'])->name('favourite_clubs.add');
    Route::post('/favourite_clubs/remove', [FavouriteClubsController::class, 'remove'])->name('favourite_clubs.remove');
});

require __DIR__.'/auth.php';
