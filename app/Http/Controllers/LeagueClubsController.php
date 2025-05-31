<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeagueClubsController extends Controller
{
    public function show($id)
    {
        $league = DB::table('Leagues')
            ->select('league_id', 'name', 'country', 'logo')
            ->where('league_id', $id)
            ->first();

        $standings = DB::table('ClubsLeagues as cl')
            ->join('Clubs as c', 'cl.club_id', '=', 'c.club_id')
            ->select(
                'c.club_id',
                'c.name',
                'c.crest as logo',
                'cl.number_of_played_matches as matches_played',
                'cl.number_of_won_matches as wins',
                'cl.number_of_drawn_matches as draws',
                'cl.number_of_losts as losses',
                'cl.number_of_points as points',
                // Bramki zdobyte przez klub
                DB::raw('(SELECT COUNT(*) FROM Goals g WHERE g.club_id = c.club_id AND g.match_id IN (SELECT m.match_id FROM Matches m WHERE m.league_id = cl.league_id)) as goals_for'),
                // Bramki stracone przez klub
                DB::raw('(SELECT COUNT(*) FROM Goals g WHERE g.club_id != c.club_id AND g.match_id IN (SELECT m.match_id FROM Matches m WHERE m.league_id = cl.league_id AND (m.club_1_id = c.club_id OR m.club_2_id = c.club_id))) as goals_against')
            )
            ->where('cl.league_id', $id)
            ->orderByDesc('cl.number_of_points')
            ->orderByDesc('goals_for')
            ->get();

        $userId = auth()->id();
        $favouriteClubIds = DB::table('FavouritesClubs')
            ->where('user_id', $userId)
            ->pluck('club_id')
            ->toArray();

        return view('league_details', compact('league', 'standings', 'favouriteClubIds'));
    }
}
