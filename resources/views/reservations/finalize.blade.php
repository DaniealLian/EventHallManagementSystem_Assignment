@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Finalize Reservation</h2>

    {{-- Event Summary --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $event->title }}</h4>
            <p class="card-text text-muted">{{ $event->description }}</p>
            <p><strong>Event Start:</strong> {{ $event->start_time->format('d M Y, H:i') }}</p>
            <p><strong>Event End:</strong> {{ $event->end_time->format('d M Y, H:i') }}</p>
        </div>
    </div>

    {{-- Reservation Summary --}}
    <h4 class="mb-3">Your Reservation</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tier</th>
                    <th>Quantity</th>
                    <th>Unit Price (RM)</th>
                    <th>Subtotal (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation_session['reservationItems'] as $item)
                    <tr>
                        <td>{{ $item['tier'] ?? 'N/A' }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['unit_price'], 2) }}</td>
                        <td>{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>RM {{ number_format($reservation_session['total_price'], 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Timer Info --}}
    @if(isset($sessionData['time_remaining']))
        <div class="alert alert-warning mt-3">
            ⚠️ You have <strong>{{ $sessionData['time_remaining'] }}</strong> seconds left to confirm
            this reservation before it expires.
        </div>
    @endif

    {{-- Confirm Form --}}
    <form action="{{ route('reservations.confirm', ['event' => $event->id, 'token' => $token]) }}" method="POST">
        @csrf
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('reservations.create', $event->id) }}" class="btn btn-outline-secondary">
                Back
            </a>
            <button type="submit" class="btn btn-success">
                ✅ Confirm Reservation
            </button>
        </div>
    </form>
</div>
@endsection
