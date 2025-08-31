@extends('layouts.app')

@section('title', 'Welcome to Event Hall Management')

@section('content')
<div class="jumbotron bg-primary text-white text-center py-5 mb-4 rounded">
    <div class="container">
        <h1 class="display-4">
            <i class="fas fa-building"></i> 
            Welcome to Event Hall Management
        </h1>
        <p class="lead">Book your perfect venue for any occasion</p>
        @guest
            <a class="btn btn-light btn-lg me-2" href="{{ route('register') }}">
                <i class="fas fa-user-plus"></i> Get Started
            </a>
            <a class="btn btn-outline-light btn-lg" href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @else
            <a class="btn btn-light btn-lg" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Go to Dashboard
            </a>
        @endguest
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-building fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Premium Venues</h5>
                <p class="card-text">Choose from our collection of elegant event halls perfect for any occasion.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                <h5 class="card-title">Easy Booking</h5>
                <p class="card-text">Simple and secure booking process with instant confirmation.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-info mb-3"></i>
                <h5 class="card-title">Professional Service</h5>
                <p class="card-text">Dedicated support team to help make your event memorable.</p>
            </div>
        </div>
    </div>
</div>

@endsection