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
        <div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Users</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if ($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Application</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentUsers as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->role === 'manager' ? 'success' : 'primary' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('M j, Y') }}</td>
                                            <td>
                                                @if ($user->manager_status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-success">Approved</span>
                                                @endif
                                            </td>
                                            <td>
                                                
                                                <button type="button" class="btn btn-danger flex-fill"
                                                    onclick="promoteToManager({{ $user->id }}, '{{ $user->name }}')">
                                                    Promote
                                                </button>
                                                <button type="button" class="btn btn-danger flex-fill"
                                                    onclick="demoteManager({{ $user->id }}, '{{ $user->name }}')">
                                                    Demote
                                                </button>
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
    </div>
@endsection
