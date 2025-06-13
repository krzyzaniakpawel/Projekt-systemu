@extends('layouts.main')
@section('content')

<div style="display: flex; justify-content: center; margin-top: 2rem;">
    <div class="info-card match-card-flex" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 0;">
        <!-- Klub 1 -->
        <div class="match-card-club club1" style="flex: 1 1 0; display: flex; flex-direction: column; align-items: center; min-width: 120px; max-width: 180px;">
            <a href="{{ route('club_details', ['id' => $match->club1_id]) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center;">
                @if($match->club1_logo)
                    <img src="data:image/png;base64,{{ base64_encode($match->club1_logo) }}" alt="Logo klubu 1" style="height: 90px; width: 90px; object-fit: contain; border-radius: 10px; background: #f8f8f8;">
                @else
                    <div style="height: 90px; width: 90px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 10px; color: #888;">
                        Brak logo
                    </div>
                @endif
                <div style="margin-top: 0.5rem; text-align: center; max-width: 140px; word-break: break-word; font-weight: 500;">
                    {{ $match->club1_name }}
                </div>
            </a>
        </div>

        <!-- Wynik i data -->
        <div class="match-card-center" style="flex: 0 0 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 100px;">
            <div style="font-size: 1.1em; color: #888; margin-bottom: 0.5rem;">
                {{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}
            </div>
            <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 0.5rem;">
                {{ $match->club_result_1 }} - {{ $match->club_result_2 }}
            </div>
        </div>

        <!-- Klub 2 -->
        <div class="match-card-club club2" style="flex: 1 1 0; display: flex; flex-direction: column; align-items: center; min-width: 120px; max-width: 180px;">
            <a href="{{ route('club_details', ['id' => $match->club2_id]) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center;">
                @if($match->club2_logo)
                    <img src="data:image/png;base64,{{ base64_encode($match->club2_logo) }}" alt="Logo klubu 2" style="height: 90px; width: 90px; object-fit: contain; border-radius: 10px; background: #f8f8f8;">
                @else
                    <div style="height: 90px; width: 90px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 10px; color: #888;">
                        Brak logo
                    </div>
                @endif
                <div style="margin-top: 0.5rem; text-align: center; max-width: 140px; word-break: break-word; font-weight: 500;">
                    {{ $match->club2_name }}
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Tabela z dodatkowymi statystykami --}}
<div style="display: flex; justify-content: center; margin-top: 2.5rem;">
    <table style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px #e0e0e0; width: 100%; max-width: 700px; border-collapse: separate; border-spacing: 0; padding: 0;">
        <tbody>
            <tr>
                <th style="width: 33%; text-align: center; padding: 1.2em 0;">{{ $match->club1_name }}</th>
                <th style="width: 34%; text-align: center; padding: 1.2em 0;">Mecz</th>
                <th style="width: 33%; text-align: center; padding: 1.2em 0;">{{ $match->club2_name }}</th>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_possession_1 ?? '-' }}%</td>
                <td style="text-align: center; padding: 0.7em 0;">Posiadanie piłki</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_possession_2 ?? '-' }}%</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_chances_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Sytuacje bramkowe</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_chances_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_corners_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Rzuty rożne</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_corners_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_free_kicks_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Rzuty wolne</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_free_kicks_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_penalties_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Rzuty karne</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_penalties_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_offsides_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Spalone</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_offsides_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_fouls_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 0.7em 0;">Faule</td>
                <td style="text-align: center; padding: 0.7em 0;">{{ $match->club_fouls_2 ?? '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 1.2em 0;">{{ $match->club_passes_1 ?? '-' }}</td>
                <td style="text-align: center; padding: 1.2em 0;">Podania</td>
                <td style="text-align: center; padding: 1.2em 0;">{{ $match->club_passes_2 ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection