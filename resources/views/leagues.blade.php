@extends('layouts.main')
@section('content')

<section class="mt-4">
  <h1>Ligi</h1>

  <table class="standings-table">
    <thead>
      <tr>
        <th>Logo</th>
        <th>Nazwa</th>
        <th>Kraj</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($leagues as $league)
      <tr class="clickable-row" data-href="{{ route('league_details', ['id' => $league->league_id]) }}" style="cursor:pointer;">
        <td>
          @if ($league->logo)
          <img class="logo" src="data:image/png;base64,{{ base64_encode($league->logo) }}" alt="Logo"
          style="height: 40px;">
          @else
          Brak
          @endif
        </td>
        <td>
          {{ $league->name }}
        </td>
        <td>{{ $league->country ?? 'Brak danych' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(function(row) {
      row.addEventListener('click', function() {
        window.location = this.dataset.href;
      });
    });
  });
</script>

@endsection