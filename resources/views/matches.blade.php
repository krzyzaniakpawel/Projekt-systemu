@extends('layouts.main')
@section('content')

<div class="container">
    <h2 class="mt-4">Nadchodzące mecze</h2>
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
            @forelse ($upcomingMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td class="col-hide-600 col-hide-380" data-label="Rozgrywki">{{ $match->league_name }}</td>
                    <td data-label="Gospodarz">{{ $match->club1_name }}</td>
                    <td data-label="Gość">{{ $match->club2_name }}</td>
                    <td class="col-hide-380" data-label="Faza">{{ $match->level_of_play }}</td>
                    <td data-label="Data">{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Brak nadchodzących meczów.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $upcomingMatches->withQueryString()->links('pagination::default') }}

    <h2 class="mt-5">Zakończone mecze</h2>
    <table class="standings-table">
        <thead>
            <tr>
                <th class="col-hide-600 col-hide-380">Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th class="col-hide-380">Faza</th>
                <th class="col-hide-380">Data</th>
                <th>Wynik</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pastMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td class="col-hide-600 col-hide-380" data-label="Rozgrywki">{{ $match->league_name }}</td>
                    <td data-label="Gospodarz">{{ $match->club1_name }}</td>
                    <td data-label="Gość">{{ $match->club2_name }}</td>
                    <td class="col-hide-380" data-label="Faza">{{ $match->level_of_play }}</td>
                    <td class="col-hide-380" data-label="Data">{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
                    <td data-label="Wynik">
                        @if(isset($match->club_result_1) && isset($match->club_result_2))
                            {{ $match->club_result_1 }} : {{ $match->club_result_2 }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Brak zakończonych meczów.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pastMatches->withQueryString()->links('pagination::default') }}
</div>

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