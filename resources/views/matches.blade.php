@extends('layouts.main')
@section('content')

<div class="container">
    <h2 class="mt-4">Nadchodzące mecze</h2>
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
            @forelse ($upcomingMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td>{{ $match->league_name }}</td>
                    <td>{{ $match->club1_name }}</td>
                    <td>{{ $match->club2_name }}</td>
                    <td>{{ $match->level_of_play }}</td>
                    <td>{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
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
                <th>Rozgrywki</th>
                <th>Gospodarz</th>
                <th>Gość</th>
                <th>Faza</th>
                <th>Wynik</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pastMatches as $match)
                <tr class="clickable-row" data-href="{{ route('match_details', ['id' => $match->match_id]) }}" style="cursor:pointer;">
                    <td>{{ $match->league_name }}</td>
                    <td>{{ $match->club1_name }}</td>
                    <td>{{ $match->club2_name }}</td>
                    <td>{{ $match->level_of_play }}</td>
                    <td>
                        @if(isset($match->club_result_1) && isset($match->club_result_2))
                            {{ $match->club_result_1 }} : {{ $match->club_result_2 }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}</td>
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