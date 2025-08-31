@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user"></i> Profile Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" 
                               name="password_confirmation">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-id-badge"></i> Account Status</h5>
            </div>
            <div class="card-body">
                <p><strong>Role:</strong> 
                    <span class="badge bg-{{ $user->isAdmin() ? 'danger' : ($user->isManager() ? 'success' : 'primary') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
                
                <p><strong>Member Since:</strong><br>
                   {{ $user->created_at->format('F j, Y') }}
                </p>

                @if($user->isCustomer())
                    <hr>
                    <h6>Want to become an Event Manager?</h6>
                    
                    @if($user->canApplyForManager())
                        <p class="text-muted small">Apply to manage event halls and help customers with their bookings.</p>
                        <a href="{{ route('manager.apply') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-briefcase"></i> Apply for Manager Role
                        </a>
                    @elseif($user->hasManagerApplicationPending())
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> Your manager application is pending review.
                            <br><small>Applied on: {{ $user->manager_applied_at->format('M j, Y') }}</small>
                        </div>
                    @elseif($user->manager_status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times"></i> Your manager application was not approved.
                            <br><small>Reviewed on: {{ $user->manager_reviewed_at->format('M j, Y') }}</small>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @if($user->isAdmin())
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-cog"></i> Admin Tools</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.manager-applications') }}" class="btn btn-outline-primary btn-sm d-block mb-2">
                    <i class="fas fa-clipboard-list"></i> Review Manager Applications
                </a>
                <a href="{{ route('halls.create') }}" class="btn btn-outline-success btn-sm d-block">
                    <i class="fas fa-plus"></i> Add New Hall
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection