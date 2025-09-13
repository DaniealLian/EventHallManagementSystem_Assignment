@extends('layouts.app')

@section('content')
<style>
    .tier-item {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
        position: relative;
    }
    .tier-counter {
        font-weight: bold;
        color: #6c757d;
        margin-bottom: 10px;
    }
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .alert {
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <div class="form-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Event</h2>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('events.update', $event) }}" id="eventForm">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $event->title) }}" required>
                @error('title') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3">{{ old('description', $event->description) }}</textarea>
                @error('description') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" 
                               class="form-control @error('start_time') is-invalid @enderror" 
                               value="{{ old('start_time', $event->start_time ? $event->start_time->format('Y-m-d\TH:i') : '') }}" 
                               required>
                        @error('start_time') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" 
                               class="form-control @error('end_time') is-invalid @enderror" 
                               value="{{ old('end_time', $event->end_time ? $event->end_time->format('Y-m-d\TH:i') : '') }}" 
                               required>
                        @error('end_time') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Pricing Tiers</label>
                @error('pricing_tiers') 
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
                
                <div id="tiersContainer">
                    @if(old('pricing_tiers'))
                        {{-- Show validation errors with old input --}}
                        @foreach(old('pricing_tiers') as $index => $tier)
                            <div class="tier-item" data-tier-index="{{ $index }}">
                                <div class="tier-counter">Tier {{ $index + 1 }}</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Tier Name</label>
                                        <input type="text" name="pricing_tiers[{{ $index }}][tier]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.tier') is-invalid @enderror" 
                                               value="{{ $tier['tier'] }}" 
                                               placeholder="e.g., VIP, Regular, Student" required>
                                        @error('pricing_tiers.'.$index.'.tier') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Price ($)</label>
                                        <input type="number" name="pricing_tiers[{{ $index }}][price]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.price') is-invalid @enderror" 
                                               value="{{ $tier['price'] }}" 
                                               step="0.01" min="0" placeholder="0.00" required>
                                        @error('pricing_tiers.'.$index.'.price') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Available Quantity</label>
                                        <input type="number" name="pricing_tiers[{{ $index }}][available_qty]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.available_qty') is-invalid @enderror" 
                                               value="{{ $tier['available_qty'] }}" 
                                               min="1" placeholder="e.g., 100" required>
                                        @error('pricing_tiers.'.$index.'.available_qty') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Description (Optional)</label>
                                        <input type="text" name="pricing_tiers[{{ $index }}][description]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.description') is-invalid @enderror" 
                                               value="{{ $tier['description'] ?? '' }}" 
                                               placeholder="Brief tier description">
                                        @error('pricing_tiers.'.$index.'.description') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Show existing event data --}}
                        @forelse($event->pricingTiers as $index => $tier)
                            <div class="tier-item" data-tier-index="{{ $index }}">
                                <div class="tier-counter">Tier {{ $index + 1 }}</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Tier Name</label>
                                        <input type="text" name="pricing_tiers[{{ $index }}][tier]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.tier') is-invalid @enderror" 
                                               value="{{ $tier->tier }}" 
                                               placeholder="e.g., VIP, Regular, Student" required>
                                        @error('pricing_tiers.'.$index.'.tier') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Price ($)</label>
                                        <input type="number" name="pricing_tiers[{{ $index }}][price]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.price') is-invalid @enderror" 
                                               value="{{ $tier->price }}" 
                                               step="0.01" min="0" placeholder="0.00" required>
                                        @error('pricing_tiers.'.$index.'.price') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Available Quantity</label>
                                        <input type="number" name="pricing_tiers[{{ $index }}][available_qty]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.available_qty') is-invalid @enderror" 
                                               value="{{ $tier->available_qty }}" 
                                               min="1" placeholder="e.g., 100" required>
                                        @error('pricing_tiers.'.$index.'.available_qty') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Description (Optional)</label>
                                        <input type="text" name="pricing_tiers[{{ $index }}][description]" 
                                               class="form-control @error('pricing_tiers.'.$index.'.description') is-invalid @enderror" 
                                               value="{{ $tier->description ?? '' }}" 
                                               placeholder="Brief tier description">
                                        @error('pricing_tiers.'.$index.'.description') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                <strong>No pricing tiers found!</strong> This event doesn't have any pricing tiers yet.
                                Please <a href="{{ route('events.edit', $event) }}" class="alert-link">refresh the page</a> 
                                or contact support if this continues.
                            </div>
                        @endforelse
                    @endif
                </div>

                
            </div>

            <div class="mb-3">
                <label class="form-label">Secret Notes (Optional)</label>
                <textarea name="secret_notes" class="form-control @error('secret_notes') is-invalid @enderror" 
                          rows="2" placeholder="Internal notes for this event...">{{ old('secret_notes', $event->secret_notes) }}</textarea>
                @error('secret_notes') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Event
                </button>
                
                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate that end time is after start time
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');

    function validateDates() {
        if (startTimeInput.value && endTimeInput.value) {
            const startDate = new Date(startTimeInput.value);
            const endDate = new Date(endTimeInput.value);
            
            if (endDate <= startDate) {
                endTimeInput.setCustomValidity('End time must be after start time');
                endTimeInput.classList.add('is-invalid');
            } else {
                endTimeInput.setCustomValidity('');
                endTimeInput.classList.remove('is-invalid');
            }
        }
    }

    if (startTimeInput && endTimeInput) {
        startTimeInput.addEventListener('change', validateDates);
        endTimeInput.addEventListener('change', validateDates);
    }

    // Form validation before submission
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        const tiers = document.querySelectorAll('.tier-item');
        
        if (tiers.length === 0) {
            e.preventDefault();
            alert('This event has no pricing tiers. Please contact support or create a new event.');
            return false;
        }

        // Check for duplicate tier names
        const tierNames = [];
        let hasDuplicates = false;
        let hasEmptyNames = false;

        tiers.forEach(tier => {
            const nameInput = tier.querySelector('input[name$="[tier]"]');
            if (nameInput) {
                const name = nameInput.value.trim().toLowerCase();
                if (!name) {
                    hasEmptyNames = true;
                } else if (tierNames.includes(name)) {
                    hasDuplicates = true;
                }
                tierNames.push(name);
            }
        });

        if (hasEmptyNames) {
            e.preventDefault();
            alert('Please fill in all tier names.');
            return false;
        }

        if (hasDuplicates) {
            e.preventDefault();
            alert('Please ensure all tier names are unique.');
            return false;
        }

        // Check if all required fields are filled
        let hasEmptyRequired = false;
        tiers.forEach(tier => {
            const requiredInputs = tier.querySelectorAll('input[required]');
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    hasEmptyRequired = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        });

        if (hasEmptyRequired) {
            e.preventDefault();
            alert('Please fill in all required fields for all pricing tiers.');
            return false;
        }

        return true;
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-success') || alert.classList.contains('alert-error')) {
                const bsAlert = bootstrap.Alert.getInstance(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            }
        });
    }, 5000);
});
</script>
@endsection