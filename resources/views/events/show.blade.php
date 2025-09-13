@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $event->title }}</h4>
                    <div>
                        @can('update', $event)
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan
                        @can('delete', $event)
                            <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $event->description ?? 'No description provided.' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Start Time:</strong>
                            <p>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>End Time:</strong>
                            <p>{{ \Carbon\Carbon::parse($event->end_time)->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Organizer:</strong>
                        <p>{{ $event->organizer->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Venue:</strong>
                        <p>
                            {{ $event->venue->name ?? 'N/A' }}  
                            @if(!empty($event->venue->address))
                                <br><small class="text-muted">{{ $event->venue->address }}</small>
                            @endif
                        </p>
                    </div>
                    
                    @if($event->secret_notes && (Auth::id() === $event->user_id || Auth::user()->role === 'admin'))
                        <div class="mb-3">
                            <strong>Secret Notes:</strong>
                            <p class="alert alert-info">{{ \Illuminate\Support\Facades\Crypt::decryptString($event->secret_notes) }}</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
