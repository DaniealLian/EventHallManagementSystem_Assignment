@extends('layouts.app')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

@php
    use Illuminate\Support\Facades\Crypt;
@endphp

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Events</h2>
    @if(auth()->user()->isManager('manager') || auth()->guard('admin')->check())
        <a href="{{ route('events.create') }}" class="btn btn-primary">+ Create Event</a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Start</th>
            <th>End</th>
            <th>Organizer</th>
            <th>Venue</th>
            <th>Secret Notes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->description }}</td>
                <td>{{ $event->start_time }}</td>
                <td>{{ $event->end_time }}</td>
                <td>{{ $event->organizer->name ?? 'N/A' }}</td>

                <td>
                    {{ $event->venue->name ?? 'N/A' }}
                    <small class="text-muted">{{ $event->venue->address ?? '' }}</small>
                </td>

                <td>
                    {{ $event->secret_notes ? Crypt::decryptString($event->secret_notes) : '' }}
                </td>
                <td>
                    @if(auth()->user()->isManager('manager') || auth()->guard('admin')->check())
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-success">Book Event</a>
                    @else
                        <a href="#" class="btn btn-sm btn-success">Book Event</a>
                    @endif
                </td>
            </tr>
        @empty
            <td colspan="{{ (auth()->user()->hasRole('manager') || auth()->guard('admin')->check()) ? '8' : '7' }}" class="text-center">
                    No events found
                </td>
            @endforelse
    </tbody>
</table>
@endsection