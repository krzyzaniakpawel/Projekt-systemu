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
                <th class="col-hide-600 col-hide-380">Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th class="col-hide-380">Faza</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($upcomingMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td class="col-hide-600 col-hide-380" data-label="Rozgrywki">{{ $match->league_name }}</td>
                    <td data-label="Gospodarz">{{ $match->club1_name }}</td>
                    <td data-label="Gość">{{ $match->club2_name }}</td>
                    <td class="col-hide-380" data-label="Faza">{{ $match->level_of_play }}</td>
                    <td data-label="Data">{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
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
                <th class="col-hide-600 col-hide-380">Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th class="col-hide-380">Faza</th>
                <th class="col-hide-380 col-hide-600">Data</th>
                <th>Wynik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pastMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td class="col-hide-600 col-hide-380" data-label="Rozgrywki">{{ $match->league_name }}</td>
                    <td data-label="Gospodarz">{{ $match->club1_name }}</td>
                    <td data-label="Gość">{{ $match->club2_name }}</td>
                    <td class="col-hide-380" data-label="Faza">{{ $match->level_of_play }}</td>
                    <td class="col-hide-380 col-hide-600" data-label="Data">{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
                    <td data-label="Wynik">{{ $match->club_result_1 }} : {{ $match->club_result_2 }}</td>
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
                <th class="col-hide-600 col-hide-380">Asysty</th>
                <th class="col-hide-600 col-hide-380">🟨</th>
                <th class="col-hide-600 col-hide-380">🟥</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr class="clickable-row" data-href="{{ route('player_details', ['id' => $player->player_id]) }}" style="cursor:pointer;">
                    <td data-label="Imię">{{ $player->name }}</td>
                    <td data-label="Nazwisko">{{ $player->surname }}</td>
                    <td data-label="Mecze">{{ $player->number_of_matches_played ?? 0 }}</td>
                    <td data-label="Bramki">{{ $player->goals ?? 0 }}</td>
                    <td class="col-hide-600 col-hide-380" data-label="Asysty">{{ $player->assists ?? 0 }}</td>
                    <td class="col-hide-600 col-hide-380" data-label="🟨">{{ $player->yellow_cards ?? 0 }}</td>
                    <td class="col-hide-600 col-hide-380" data-label="🟥">{{ $player->red_cards ?? 0 }}</td>
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
