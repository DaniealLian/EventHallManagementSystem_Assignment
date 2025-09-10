@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Admin Dashboard</h2>
        <p class="text-muted">Welcome back, {{ auth('admin')->user()->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Application</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'manager' ? 'success' : 'primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M j, Y') }}</td>
                                    <td>
                                        @if($user->manager_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">

                        <p>No users registered yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                        Manage Users
                    </a>
                    <a href="{{ route('admin.manager.applications') }}" class="btn btn-outline-warning">
                        Event Manager Applications
                        @if($pendingApplications > 0)
                            <span class="badge bg-warning ms-2">{{ $pendingApplications }}</span>
                        @endif
                    </a>
                </div>

                <hr>

                <div class="text-center">
                    <small class="text-muted">
                        Last login:
                        {{ auth('admin')->user()->last_login_at ? auth('admin')->user()->last_login_at->format('M j, Y g:i A') : 'First time' }}
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">System Info</h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Admin Account:</span>
                        <span>{{ auth('admin')->user()->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Last Updated:</span>
                        <span>{{ now()->format('M j, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Version:</span>
                        <span>v1.0</span>
                    </div>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
