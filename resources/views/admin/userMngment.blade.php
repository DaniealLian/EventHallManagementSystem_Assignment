@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-users"></i> User Management</h2>
                <p class="text-muted">Manage all registered users and their roles</p>
            </div>
            <div>
                <!-- Filter buttons -->
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.users') }}"
                       class="btn btn-outline-primary {{ !request('role') ? 'active' : '' }}">
                        All Users
                    </a>
                    <a href="{{ route('admin.users') }}?role=customer"
                       class="btn btn-outline-primary {{ request('role') == 'customer' ? 'active' : '' }}">
                        Customers
                    </a>
                    <a href="{{ route('admin.users') }}?role=manager"
                       class="btn btn-outline-primary {{ request('role') == 'manager' ? 'active' : '' }}">
                        Managers
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Users List ({{ $users->total() }} total)</h5>
            </div>
            <div class="col-auto">
                <!-- Search form -->
                <form method="GET" class="d-flex">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <input type="text" name="search" class="form-control me-2"
                           placeholder="Search users..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">ID: #{{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $user->email }}</div>
                                @if($user->phone_number)
                                    <small class="text-muted">{{ $user->phone_number }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'manager' ? 'success' : 'primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->manager_status === 'pending')
                                    <span class="badge bg-warning">Application Pending</span>
                                @elseif($user->manager_status === 'rejected')
                                    <span class="badge bg-danger">Application Rejected</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $user->created_at->format('M j, Y') }}</small>
                                <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Role management buttons -->
                                    @if($user->role === 'customer')
                                        <button type="button" class="btn btn-success"
                                                onclick="promoteUser({{ $user->id }}, '{{ $user->name }}')"
                                                title="Promote to Manager">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                    @elseif($user->role === 'manager')
                                        <button type="button" class="btn btn-warning"
                                                onclick="demoteUser({{ $user->id }}, '{{ $user->name }}')"
                                                title="Demote to Customer">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    @endif

                                    <!-- Delete button -->
                                    <button type="button" class="btn btn-danger"
                                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                            title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">No users match your current filter criteria.</p>
            </div>
        @endif
    </div>
</div>

<!-- Promote User Modal -->
<div class="modal fade" id="promoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Promote to Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to promote <strong id="promoteUserName"></strong> to Manager?</p>
                <p class="text-muted">This will give them access to manage event halls and bookings.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="promoteForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Promote to Manager</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Demote User Modal -->
<div class="modal fade" id="demoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Demote to Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to demote <strong id="demoteUserName"></strong> to Customer?</p>
                <p class="text-muted">This will remove their manager privileges and access to management features.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="demoteForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">Demote to Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> This action cannot be undone. All user data, bookings, and related information will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function promoteUser(userId, userName) {
    document.getElementById('promoteUserName').textContent = userName;
    document.getElementById('promoteForm').action = `/admin/users/${userId}/promote`;
    new bootstrap.Modal(document.getElementById('promoteModal')).show();
}

function demoteUser(userId, userName) {
    document.getElementById('demoteUserName').textContent = userName;
    document.getElementById('demoteForm').action = `/admin/users/${userId}/demote`;
    new bootstrap.Modal(document.getElementById('demoteModal')).show();
}

function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
