{{-- resources/views/reservations/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">{{ $event->title }}</h2>
    <p class="text-muted">{{ $event->description }}</p>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Start:</strong> {{ $event->start_time->format('d M Y, H:i') }}</p>
            <p><strong>End:</strong> {{ $event->end_time->format('d M Y, H:i') }}</p>
        </div>
    </div>

    {{-- Reservation Form --}}
    <form action="{{ route('reservations.store', $event) }}" method="POST">
        @csrf

        {{-- Reservation Date --}}
        <div class="mb-3">
            <label for="reserved_date_time" class="form-label">Reservation Date & Time</label>
            <input type="datetime-local" 
                   name="reserved_date_time" 
                   id="reserved_date_time" 
                   class="form-control @error('reserved_date_time') is-invalid @enderror"
                   value="{{ old('reserved_date_time') }}">
            @error('reserved_date_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Pricing Tiers --}}
        <h4 class="mt-4">Available Tickets</h4>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tier</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Available</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->pricingTiers as $tier)
                        <tr>
                            <td><strong>{{ $tier->tier }}</strong></td>
                            <td>{{ $tier->description ?? '-' }}</td>
                            <td>RM {{ number_format($tier->price, 2) }}</td>
                            <td>{{ $tier->real_available_qty }}</td>
                            <td>
                                <input type="number" 
                                       name="tiers[{{ $tier->id }}]" 
                                       class="form-control" 
                                       min="0" 
                                       max="{{ $tier->real_available_qty }}" 
                                       value="0">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">Back</a>
            <button type="submit" class="btn btn-primary">
                Reserve Tickets
            </button>
        </div>
    </form>
</div>
@endsection