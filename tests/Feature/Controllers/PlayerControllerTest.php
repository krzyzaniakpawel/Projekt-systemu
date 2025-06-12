<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

test('player details page can be rendered', function () {
    $playerId = 7;
    $playerData = (object)[
        'player_id' => $playerId,
        'name' => 'Cristiano',
        'surname' => 'Ronaldo',
        'age' => 38,
        'nationality' => 'Portugal',
        'position' => 'Forward',
        'photo' => 'ronaldo.png',
    ];

    // piłkarz
    DB::shouldReceive('table')
        ->with('Players')
        ->andReturnSelf();
    DB::shouldReceive('select')->andReturnSelf();
    DB::shouldReceive('where')
        ->with('player_id', $playerId)
        ->andReturnSelf();
    DB::shouldReceive('first')
        ->andReturn($playerData);

    // logowanie
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get("/player_details/{$playerId}");

    $response->assertStatus(200);
    $response->assertViewIs('player_details');
    $response->assertViewHas('player', $playerData);
});