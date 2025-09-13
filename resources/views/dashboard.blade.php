@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="lead">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Event Halls</h5>
                        <h2>TODO</h2>
                    </div>
                </div>
                <a class="btn btn-success btn-lg" href="{{ route('events.index') }}">
                <i class="fas fa-calendar"></i> View Events
            </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">My Bookings</h5>
                        <h2>TODO</h2>
                    </div>
                </div>
                <a href="{{ route('reservations.index') }}" class="btn btn-success btn-lg">Start Booking</a>
            </div>
        </div>
    </div>

        <div class="col-md-4">
    <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Payment Page (TESTING)</h5>
                        <h2>TODO</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isManager())
<div class="row mt-4">
    <div class="col-12">
        <h3>Management Tools</h3>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="TODO" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New Hall
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="TODO" class="btn btn-primary">
                            <i class="fas fa-cog"></i> Manage Halls
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
