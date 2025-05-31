<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    public function show($id)
    {
        // Pobierz dane klubu
        $club = DB::table('Clubs')
            ->select('club_id', 'name', 'crest', 'home_stadium')
            ->where('club_id', $id)
            ->first();

        // Pobierz piłkarzy i ich statystyki
        $players = DB::table('PlayersClubs as pc')
            ->join('ClubsLeagues as cl', 'pc.club_id_league', '=', 'cl.club_id_league')
            ->join('Players as p', 'pc.player_id', '=', 'p.player_id')
            ->select(
                'p.player_id',
                'p.name',
                'p.surname',
                'p.age',
                'p.nationality',
                'p.position',
                'pc.number_of_matches_played',
                'pc.goals',
                'pc.assists',
                'pc.yellow_cards',
                'pc.red_cards'
            )
            ->where('cl.club_id', $id)
            ->get();

        // Nadchodzące mecze (data w przyszłości lub dziś)
        $upcomingMatches = DB::table('Matches as m')
            ->join('Clubs as c1', 'm.club_1_id', '=', 'c1.club_id')
            ->join('Clubs as c2', 'm.club_2_id', '=', 'c2.club_id')
            ->join('Leagues as l', 'm.league_id', '=', 'l.league_id')
            ->select(
                'm.match_id',
                'l.name as league_name',
                'c1.name as club1_name',
                'c2.name as club2_name',
                'm.level_of_play',
                'm.match_date'
            )
            ->where(function($query) use ($id) {
                $query->where('m.club_1_id', $id)
                      ->orWhere('m.club_2_id', $id);
            })
            ->where('m.match_date', '>=', now())
            ->orderBy('m.match_date', 'asc')
            ->limit(10)
            ->get();

        // Przeszłe mecze (data w przeszłości)
        $pastMatches = DB::table('Matches as m')
            ->join('Clubs as c1', 'm.club_1_id', '=', 'c1.club_id')
            ->join('Clubs as c2', 'm.club_2_id', '=', 'c2.club_id')
            ->join('Leagues as l', 'm.league_id', '=', 'l.league_id')
            ->select(
                'm.match_id',
                'l.name as league_name',
                'c1.name as club1_name',
                'c2.name as club2_name',
                'm.level_of_play',
                'm.club_result_1',
                'm.club_result_2',
                'm.match_date'
            )
            ->where(function($query) use ($id) {
                $query->where('m.club_1_id', $id)
                      ->orWhere('m.club_2_id', $id);
            })
            ->where('m.match_date', '<', now())
            ->orderByDesc('m.match_date')
            ->limit(10)
            ->get();

        return view('club_details', compact('club', 'players', 'upcomingMatches', 'pastMatches'));
    }
}