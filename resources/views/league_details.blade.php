{{-- resources/views/leagues/show.blade.php --}}
@extends('layouts.main')
@section('content')

<section class="mt-4">

  <h1 class="card-title">Szczegóły ligi: {{ $league->name }}</h1>
  <p class="mb-4">Kraj: {{ $league->country ?? 'Brak danych' }}</p>

  <table class="standings-table">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th>Klub</th>
        <th class="col-hide-600 col-matches">M</th>
        <th class="col-hide-600">Z</th>
        <th class="col-hide-600">R</th>
        <th class="col-hide-600">P</th>
        <th class="col-hide-600 col-goals">B</th>
        <th class="col-hide-600">RB</th>
        <th>PKT</th>
        <th>Ulubione</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standings as $index => $team)
        <tr class="clickable-row" data-href="{{ route('club_details', ['id' => $team->club_id]) }}" style="cursor:pointer;">
          <td>{{ $index + 1 }}</td>
          <td>
            @if ($team->logo)
              <img src="data:image/png;base64,{{ base64_encode($team->logo) }}" alt="Herb" style="height: 32px;">
            @else
              Brak
            @endif
          </td>
          <td>{{ $team->name }}</td>
          <td class="col-hide-600 col-matches">{{ $team->matches_played }}</td>
          <td class="col-hide-600">{{ $team->wins }}</td>
          <td class="col-hide-600">{{ $team->draws }}</td>
          <td class="col-hide-600">{{ $team->losses }}</td>
          <td class="col-hide-600 col-goals">{{ $team->goals_for }} : {{ $team->goals_against }}</td>
          <td class="col-hide-600">{{ $team->goals_for - $team->goals_against }}</td>
          <td>{{ $team->points }}</td>
          <td>
            @if(in_array($team->club_id, $favouriteClubIds))
              <form method="POST" action="{{ route('favourite_clubs.remove') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="club_id" value="{{ $team->club_id }}">
                <button type="submit" class="fav-btn" style="background:none;border:none;cursor:pointer;" title="Usuń z ulubionych">
                  <span class="star" style="color: gold;">&#9733;</span>
                </button>
              </form>
            @else
              <form method="POST" action="{{ route('favourite_clubs.add') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="club_id" value="{{ $team->club_id }}">
                <button type="submit" class="fav-btn" style="background:none;border:none;cursor:pointer;" title="Dodaj do ulubionych">
                  <span class="star">&#9734;</span>
                </button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

<section/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Klikalność wierszy
  document.querySelectorAll('.clickable-row').forEach(function(row) {
    row.addEventListener('click', function(e) {
      // Nie przekierowuj jeśli kliknięto w przycisk gwiazdki lub jego dziecko
      if (e.target.closest('.fav-btn')) return;
      window.location = this.dataset.href;
    });
  });

  // Zatrzymaj propagację kliknięcia na przycisku gwiazdki
  document.querySelectorAll('.fav-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
});
</script>
@endpush

@endsection