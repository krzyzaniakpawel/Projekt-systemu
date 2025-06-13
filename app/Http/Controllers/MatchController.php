<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function show($id)
    {
        // Pobierz dane meczu wraz z nazwami, herbami i statystykami klubów
        $match = DB::table('Matches as m')
            ->join('Clubs as c1', 'm.club_1_id', '=', 'c1.club_id')
            ->join('Clubs as c2', 'm.club_2_id', '=', 'c2.club_id')
            ->select(
                'm.match_id',
                'm.match_date',
                'm.club_result_1',
                'm.club_result_2',
                'm.club_possession_1',
                'm.club_possession_2',
                'm.club_chances_1',
                'm.club_chances_2',
                'm.club_corners_1',
                'm.club_corners_2',
                'm.club_free_kicks_1',
                'm.club_free_kicks_2',
                'm.club_penalties_1',
                'm.club_penalties_2',
                'm.club_offsides_1',
                'm.club_offsides_2',
                'm.club_fouls_1',
                'm.club_fouls_2',
                'm.club_passes_1',
                'm.club_passes_2',
                'c1.name as club1_name',
                'c1.crest as club1_logo',
                'c2.name as club2_name',
                'c2.crest as club2_logo',
                'c1.club_id as club1_id',
                'c2.club_id as club2_id'
            )
            ->where('m.match_id', $id)
            ->first();

        if (!$match) {
            abort(404, 'Mecz nie został znaleziony.');
        }

        return view('match_details', compact('match'));
    }
}