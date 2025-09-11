@extends('layouts.app')

@section('title', 'Available Events')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Available Events</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        <p><strong>Start:</strong> {{ $event->start_time }}</p>
                        <p><strong>End:</strong> {{ $event->end_time }}</p>
                        <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>

                        <div class="mt-auto">
                            <a href="{{ route('reservations.checkout', $event->id) }}" 
                               class="btn btn-success w-100">
                                Reserve Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No events available for booking at the moment.</p>
        @endforelse
    </div>
</div>
@endsection