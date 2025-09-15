@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Payment Status') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                        <div class="text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            {{ session('error') }}
                        </div>
                        <div class="text-center">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Try Again</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ session('info') }}
                        </div>
                        <div class="text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    @endif

                    @if(!session('success') && !session('error') && !session('info'))
                        <div class="text-center">
                            <h4>No payment status available</h4>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
