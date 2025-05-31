@extends('layouts.main')
@section('content')

<div style="display: flex; justify-content: center; margin-top: 2rem;">
    <div class="info-card" style="max-width: 600px; width: 100%; transform: scale(1.12);">
        <div class="player-photo" style="flex-shrink: 0;">
            @if($player->photo)
                <img src="data:image/png;base64,{{ base64_encode($player->photo) }}" alt="Zdjęcie piłkarza" style="height: 180px; width: 135px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px #ccc;">
            @else
                <div style="height: 180px; width: 135px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #888;">
                    Brak zdjęcia
                </div>
            @endif
        </div>
        <div class="player-info" style="flex:1;">
            <h2 style="margin-bottom: 1rem; font-size: 1.45em;">{{ $player->name }} {{ $player->surname }}</h2>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 1.13em;">
                <li><strong>Wiek:</strong> {{ $player->age ?? '-' }}</li>
                <li><strong>Narodowość:</strong> {{ $player->nationality ?? '-' }}</li>
                <li><strong>Pozycja:</strong> {{ $player->position ?? '-' }}</li>
            </ul>
        </div>
    </div>
</div>

@endsection