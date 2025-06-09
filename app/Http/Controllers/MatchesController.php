<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatchesController extends Controller
{
    public function index()
    {
        // Ustal dzisiejszą datę (bez czasu)
        $today = Carbon::today();

        // Nadchodzące mecze: data meczu >= dziś
        $upcomingMatches = DB::table('Matches')
            ->join('Leagues', 'Matches.league_id', '=', 'Leagues.league_id')
            ->join('Clubs as c1', 'Matches.club_1_id', '=', 'c1.club_id')
            ->join('Clubs as c2', 'Matches.club_2_id', '=', 'c2.club_id')
            ->select(
                'Matches.*',
                'Leagues.name as league_name',
                'c1.name as club1_name',
                'c2.name as club2_name'
            )
            ->whereDate('Matches.match_date', '>=', $today)
            ->orderBy('Matches.match_date', 'asc')
            ->paginate(5, ['*'], 'upcoming_page'); // 10 na stronę, możesz zmienić

        // Zakończone mecze: data meczu < dziś
        $pastMatches = DB::table('Matches')
            ->join('Leagues', 'Matches.league_id', '=', 'Leagues.league_id')
            ->join('Clubs as c1', 'Matches.club_1_id', '=', 'c1.club_id')
            ->join('Clubs as c2', 'Matches.club_2_id', '=', 'c2.club_id')
            ->select(
                'Matches.*',
                'Leagues.name as league_name',
                'c1.name as club1_name',
                'c2.name as club2_name'
            )
            ->whereDate('Matches.match_date', '<', $today)
            ->orderBy('Matches.match_date', 'desc')
            ->paginate(5, ['*'], 'past_page'); // 10 na stronę, możesz zmienić

        return view('matches', compact('upcomingMatches', 'pastMatches'));
    }
}