<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavouriteClubsController extends Controller
{
    public function index()
    {
        // Pobierz ID zalogowanego użytkownika (lub testowo ID=1 jeśli nie ma auth)
        $userId = auth()->id() ?? 1;

        $favouriteClubs = DB::table('FavouritesClubs as f')
            ->join('Clubs as c', 'f.club_id', '=', 'c.club_id')
            ->select('c.name', 'c.club_id')
            ->where('f.user_id', $userId)
            ->paginate(10); // 10 na stronę

        return view('favourite_clubs', compact('favouriteClubs'));
    }

    public function add(Request $request)
    {
        $userId = auth()->id();
        $clubId = $request->input('club_id');

        // Sprawdź, czy już jest w ulubionych
        $exists = DB::table('FavouritesClubs')
            ->where('user_id', $userId)
            ->where('club_id', $clubId)
            ->exists();

        if (!$exists) {
            DB::table('FavouritesClubs')->insert([
                'favorite_id' => DB::raw('FAVOURITESCLUBS_SEQ.NEXTVAL'), // jeśli masz sekwencję w Oracle
                'user_id' => $userId,
                'club_id' => $clubId,
            ]);
        }

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $userId = auth()->id() ?? 1;
        $clubId = $request->input('club_id');

        DB::table('FavouritesClubs')
            ->where('user_id', $userId)
            ->where('club_id', $clubId)
            ->delete();

        return redirect()->back();
    }
}