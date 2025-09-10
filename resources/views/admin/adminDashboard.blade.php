@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
        <p class="text-muted">Welcome back, {{ auth('admin')->user()->name }}!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $totalUsers }}</h3>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="text-primary-emphasis">
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary border-0">
                <a href="{{ route('admin.users') }}" class="text-white text-decoration-none">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $totalManagers }}</h3>
                        <p class="mb-0">Event Managers</p>
                    </div>
                    <div class="text-success-emphasis">
                        <i class="fas fa-user-tie fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success border-0">
                <a href="{{ route('admin.users') }}?role=manager" class="text-white text-decoration-none">
                    View Managers <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $pendingApplications }}</h3>
                        <p class="mb-0">Pending Applications</p>
                    </div>
                    <div class="text-warning-emphasis">
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning border-0">
                <a href="{{ route('admin.manager.applications') }}" class="text-white text-decoration-none">
                    Review Applications <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $totalUsers - $totalManagers }}</h3>
                        <p class="mb-0">Customers</p>
                    </div>
                    <div class="text-info-emphasis">
                        <i class="fas fa-user fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info border-0">
                <a href="{{ route('admin.users') }}?role=customer" class="text-white text-decoration-none">
                    View Customers <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Users</h5>
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
                                    <th>Status</th>
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
                                            <span class="badge bg-warning">Application Pending</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>No users registered yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.manager.applications') }}" class="btn btn-outline-warning">
                        <i class="fas fa-clipboard-list"></i> Review Applications
                        @if($pendingApplications > 0)
                            <span class="badge bg-warning ms-2">{{ $pendingApplications }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user-cog"></i> Admin Profile
                    </a>
                </div>

                <hr>

                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Last login:
                        {{ auth('admin')->user()->last_login_at ? auth('admin')->user()->last_login_at->format('M j, Y g:i A') : 'First time' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> System Info</h6>
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
