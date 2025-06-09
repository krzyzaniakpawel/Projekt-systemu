@extends('layouts.main')
@section('content')

<section class="mt-4">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h1>Szczegóły klubu: {{ $club->name }}</h1>
        @if ($club->crest)
            <img src="data:image/png;base64,{{ base64_encode($club->crest) }}" alt="Herb klubu" style="height: 60px;">
        @endif
    </div>
    <p>Stadion: {{ $club->home_stadium ?? 'Brak danych' }}</p>
</section>

{{-- Nadchodzące mecze --}}
@if($upcomingMatches->count())
<section class="mt-5">
    <h2 class="mt-5">Zbliżające się mecze</h2>
    <table class="standings-table">
        <thead>
            <tr>
                <th>Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th>Faza</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($upcomingMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td>{{ $match->league_name }}</td>
                    <td>{{ $match->club1_name }}</td>
                    <td>{{ $match->club2_name }}</td>
                    <td>{{ $match->level_of_play }}</td>
                    <td>{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $upcomingMatches->withQueryString()->links('pagination::default') }}
    </div>
</section>
@endif

{{-- Przeszłe mecze --}}
@if($pastMatches->count())
<section class="mt-5">
    <h2 class="mt-5">Ostatnie mecze</h2>
    <table class="standings-table">
        <thead>
            <tr>
                <th>Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th>Faza</th>
                <th>Data</th>
                <th>Wynik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pastMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td>{{ $match->league_name }}</td>
                    <td>{{ $match->club1_name }}</td>
                    <td>{{ $match->club2_name }}</td>
                    <td>{{ $match->level_of_play }}</td>
                    <td>{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
                    <td>{{ $match->club_result_1 }} : {{ $match->club_result_2 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $pastMatches->withQueryString()->links() }}
    </div>
</section>
@endif

<section class="mt-5">
    <h2 class="mt-4">Piłkarze</h2>
    <table class="standings-table">
        <thead>
            <tr>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Mecze</th>
                <th>Bramki</th>
                <th>Asysty</th>
                <th>🟨</th>
                <th>🟥</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr class="clickable-row" data-href="{{ route('player_details', ['id' => $player->player_id]) }}" style="cursor:pointer;">
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->surname }}</td>
                    <td>{{ $player->number_of_matches_played ?? 0 }}</td>
                    <td>{{ $player->goals ?? 0 }}</td>
                    <td>{{ $player->assists ?? 0 }}</td>
                    <td>{{ $player->yellow_cards ?? 0 }}</td>
                    <td>{{ $player->red_cards ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(function(row) {
      row.addEventListener('click', function() {
        window.location = this.dataset.href;
      });
    });
  });
</script>
@endpush
