<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

test('club details page can be rendered', function () {
    $clubId = 1;
    $clubData = (object)[
        'club_id' => $clubId,
        'name' => 'Manchester United',
        'crest' => 'example.png',
        'home_stadium' => 'Old Trafford',
    ];

    // klub
    DB::shouldReceive('table')
        ->with('Clubs')
        ->andReturnSelf();
    DB::shouldReceive('select')->andReturnSelf();
    DB::shouldReceive('where')
        ->with('club_id', $clubId)
        ->andReturnSelf();
    DB::shouldReceive('first')
        ->andReturn($clubData);

    // gracze
    DB::shouldReceive('table')->with('PlayersClubs as pc')->andReturnSelf();
    DB::shouldReceive('join')->andReturnSelf();
    DB::shouldReceive('select')->andReturnSelf();
    DB::shouldReceive('where')->andReturnSelf();
    DB::shouldReceive('get')->andReturn(collect());

    // mecze
    DB::shouldReceive('table')->with('Matches as m')->andReturnSelf();
    DB::shouldReceive('join')->andReturnSelf();
    DB::shouldReceive('select')->andReturnSelf();
    DB::shouldReceive('where')->andReturnSelf();
    DB::shouldReceive('orderBy')->andReturnSelf();
    DB::shouldReceive('paginate')->andReturn(collect());
    DB::shouldReceive('orderByDesc')->andReturnSelf();

    // logowanie 
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get("/club_details/{$clubId}");

    $response->assertStatus(200);
    $response->assertViewIs('club_details');
    $response->assertViewHas('club', $clubData);
});