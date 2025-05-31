<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;

class LeaguesController extends Controller
{
    public function index()
    {
        $leagues = League::all();
        return view('leagues', compact('leagues'));
    }
}
