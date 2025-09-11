@extends('layouts.app')

@section('title', 'Checkout Reservation')

@section('content')
<div class="container">
    <h2 class="mb-4">Reserve Your Seat</h2>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Event Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title">{{ $event->title }}</h4>
            <p class="card-text">{{ $event->description }}</p>
            <p><strong>Start:</strong> {{ $event->start_time }}</p>
            <p><strong>End:</strong> {{ $event->end_time }}</p>
            <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
        </div>
    </div>

    {{-- Reservation Form --}}
    <form method="POST" action="{{ route('reservations.store', $event->id) }}">
        @csrf
        <div class="mb-3">
            <label for="seat_type_id" class="form-label">Seat Type</label>
            <select name="seat_type_id" id="seat_type_id" class="form-select" required>
                <option value="" disabled selected>-- Select Seat Type --</option>
                @foreach($seatTypes as $seatType)
                    <option value="{{ $seatType->id }}">
                        {{ $seatType->name }} (RM {{ number_format($seatType->price, 2) }})
                    </option>
                @endforeach
            </select>
            @error('seat_type_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
    </form>
</div>
@endsection