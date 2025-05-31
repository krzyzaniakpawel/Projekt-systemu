<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function show($id)
    {
        // Pobierz dane piłkarza
        $player = DB::table('Players')
            ->select('player_id', 'name', 'surname', 'age', 'nationality', 'position', 'photo')
            ->where('player_id', $id)
            ->first();

        if (!$player) {
            abort(404, 'Piłkarz nie został znaleziony.');
        }

        return view('player_details', compact('player'));
    }
}