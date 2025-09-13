@extends('admin.layout')

@section('title', 'Manager Applications')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Manager Applications</h2>
        <p class="text-muted">Review and approve pending manager applications</p>
    </div>
</div>

@if($applications->count() > 0)
    <div class="row">
        @foreach($applications as $application)
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $application->name }}</h5>
                        <span class="badge bg-warning">Pending Review</span>
                    </div>
                    <small class="text-muted">Applied {{ $application->manager_applied_at->format('M j, Y g:i A') }}</small>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->email }}
                        </div>
                    </div>

                    @if($application->phone_number)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->phone_number }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Company:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->company_name ?: 'Not provided' }}
                        </div>
                    </div>

                    @if($application->company_email)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Company Email:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->company_email }}
                        </div>
                    </div>
                    @endif

                    @if($application->company_address)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Address:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->company_address }}
                        </div>
                    </div>
                    @endif

                    @if($application->experience)
                    <div class="mb-3">
                        <strong>Experience & Background:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $application->experience }}
                        </div>
                    </div>
                    @endif

                    <div class="row text-muted">
                        <div class="col-sm-4">
                            <strong>Member Since:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $application->created_at->format('M j, Y') }}
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success flex-fill"
                                onclick="approveApplication({{ $application->id }}, '{{ $application->name }}')">
                            Approve
                        </button>
                        <button type="button" class="btn btn-danger flex-fill"
                                onclick="rejectApplication({{ $application->id }}, '{{ $application->name }}')">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <h4 class="text-muted">No Pending Applications</h4>
            <p class="text-muted">All manager applications have been reviewed.</p>
        </div>
    </div>
@endif

<!-- Approve Application Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Manager Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <p class="text-center">Are you sure you want to approve <strong id="approveUserName"></strong>'s manager application?</p>
                <div class="alert alert-success">
                    <strong>This will:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Promote the user to Manager role</li>
                        <li>Grant access to hall management features</li>
                        <li>Allow them to view all bookings</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Application Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Manager Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-times-circle fa-3x text-danger"></i>
                </div>
                <p class="text-center">Are you sure you want to reject <strong id="rejectUserName"></strong>'s manager application?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>This will:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Mark the application as rejected</li>
                        <li>Keep the user as a regular customer</li>
                        <li>They may apply again in the future</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="rejectForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function approveApplication(userId, userName) {
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveForm').action = `/admin/manager-applications/${userId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function rejectApplication(userId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/admin/manager-applications/${userId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endsection
