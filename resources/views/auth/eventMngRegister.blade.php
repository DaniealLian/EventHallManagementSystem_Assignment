<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply as Event Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Apply as Event Manager</h4>
                        <p class="mb-0 text-muted">Complete your event manager application</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Display current user info -->
                        <div class="alert alert-info">
                            <strong>Applying as:</strong> {{ $user->name }} ({{ $user->email }})
                        </div>

                        <form method="POST" action="{{ route('manager.submit') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name *</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                               id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_email" class="form-label">Company Email *</label>
                                        <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                               id="company_email" name="company_email" value="{{ old('company_email') }}" required>
                                        @error('company_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="company_address" class="form-label">Company Address *</label>
                                <textarea class="form-control @error('company_address') is-invalid @enderror"
                                          id="company_address" name="company_address" rows="3" required>{{ old('company_address') }}</textarea>
                                @error('company_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label">Experience & Background</label>
                                <textarea class="form-control @error('experience') is-invalid @enderror"
                                          id="experience" name="experience" rows="4"
                                          placeholder="Tell us about your event management experience, qualifications, and why you want to become an event manager...">{{ old('experience') }}</textarea>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <small><strong>Note:</strong> Your application will be reviewed by an administrator. Please ensure all information is accurate as this will be used for verification purposes.</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
