@extends('layouts.app')

@section('title', 'Available Events')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Available Events</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Event Title</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                        <tr>
                            <td>
                                <h3>{{ $event->title }}</h3>
                            </td>
                            <td>
                                <h4>{{ $event->description }}</h4>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('reservations.create', $event) }}">Make a Reservation</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
            </div>
        </div>
    </div>
    @endsection