@extends('layouts.main')
@section('content')

<section class="mt-4">
    <h2>Twoje ulubione kluby</h2>
    @if($favouriteClubs->isEmpty())
        <p>Nie dodałeś jeszcze żadnego klubu do ulubionych.</p>
    @else
        <table class="standings-table">
            <thead>
                <tr>
                    <th>Nazwa klubu</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($favouriteClubs as $club)
                    <tr>
                        <td>{{ $club->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('favourite_clubs.remove') }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="club_id" value="{{ $club->club_id }}">
                                <button type="submit" style="background:none;border:none;color:red;font-size:1.2em;cursor:pointer;" title="Usuń z ulubionych">
                                    &#10006;
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>

@endsection