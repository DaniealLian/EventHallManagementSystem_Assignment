@extends('layouts.app')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tier-item {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            position: relative;
        }
        .tier-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            cursor: pointer;
        }
        .add-tier-btn {
            border: 2px dashed #6c757d;
            background: transparent;
            color: #6c757d;
            padding: 10px 20px;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-tier-btn:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background: #f8f9fa;
        }
        .tier-counter {
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 10px;
        }
    </style>
</head>
@section('content')
<h2>Create Event</h2>

<form method="POST" action="{{ route('events.store') }}" id="eventForm">
    @csrf
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Event Tiers</label>
        <div id="tiersContainer">
            <div class="tier-item" data-tier-index="0">
                <div class="tier-counter">Tier 1</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Tier Name</label>
                        <input type="text" name="tiers[0][name]" class="form-control" placeholder="e.g., VIP, Regular, Student" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price ($)</label>
                        <input type="number" name="tiers[0][price]" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Available Seats</label>
                        <input type="number" name="tiers[0][seats]" class="form-control" min="1" placeholder="e.g., 100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" name="tiers[0][description]" class="form-control" placeholder="Brief tier description">
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" class="add-tier-btn w-100" onclick="addNewTier()">
            <i class="bi bi-plus-circle"></i> ➕ Add New Tier
        </button>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control" required>
                @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">End Time</label>
                <input type="datetime-local" name="end_time" class="form-control" required>
                @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Secret Notes (Optional)</label>
        <textarea name="secret_notes" class="form-control" rows="2" placeholder="Internal notes for this event..."></textarea>
        @error('secret_notes') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
    <label class="form-label">Venue</label>
        <div class="d-flex">
            <select name="venue_id" class="form-control me-2" required>
                <option value="">-- Select Venue --</option>
                @foreach(\App\Facades\VFacade::getAllVenues() as $v)
                    <option value="{{ $v->id }}" 
                        {{ request('venue_id') == $v->id ? 'selected' : '' }}>
                        {{ $v->name }} - {{ $v->address }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('venues.create') }}" target="_blank" class="btn btn-outline-primary">
                ➕ Add Venue
            </a>
        </div>
    </div>


    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Save Event</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
let tierIndex = 1;

function addNewTier() {
    const container = document.getElementById('tiersContainer');
    const newTier = document.createElement('div');
    newTier.className = 'tier-item';
    newTier.setAttribute('data-tier-index', tierIndex);
    
    newTier.innerHTML = `
        <button type="button" class="tier-remove" onclick="removeTier(this)" title="Remove Tier">×</button>
        <div class="tier-counter">Tier ${tierIndex + 1}</div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Tier Name</label>
                <input type="text" name="tiers[${tierIndex}][name]" class="form-control" placeholder="e.g., VIP, Regular, Student" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Price ($)</label>
                <input type="number" name="tiers[${tierIndex}][price]" class="form-control" step="0.01" min="0" placeholder="0.00" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <label class="form-label">Available Seats</label>
                <input type="number" name="tiers[${tierIndex}][seats]" class="form-control" min="1" placeholder="e.g., 100">
            </div>
            <div class="col-md-6">
                <label class="form-label">Description (Optional)</label>
                <input type="text" name="tiers[${tierIndex}][description]" class="form-control" placeholder="Brief tier description">
            </div>
        </div>
    `;
    
    container.appendChild(newTier);
    tierIndex++;
    updateTierNumbers();
}

function removeTier(button) {
    const tierItem = button.closest('.tier-item');
    tierItem.remove();
    updateTierNumbers();
}

function updateTierNumbers() {
    const tiers = document.querySelectorAll('.tier-item');
    tiers.forEach((tier, index) => {
        const counter = tier.querySelector('.tier-counter');
        counter.textContent = `Tier ${index + 1}`;

        const inputs = tier.querySelectorAll('input[name^="tiers"]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/tiers\[\d+\]/, `tiers[${index}]`);
            input.setAttribute('name', newName);
        });
    });
}

document.getElementById('eventForm').addEventListener('submit', function(e) {
    const tiers = document.querySelectorAll('.tier-item');
    if (tiers.length === 0) {
        e.preventDefault();
        alert('Please add at least one tier for the event.');
        return;
    }
    
    const tierNames = [];
    let hasDuplicates = false;
    
    tiers.forEach(tier => {
        const nameInput = tier.querySelector('input[name$="[name]"]');
        const name = nameInput.value.trim().toLowerCase();
        if (name && tierNames.includes(name)) {
            hasDuplicates = true;
        }
        tierNames.push(name);
    });
    
    if (hasDuplicates) {
        e.preventDefault();
        alert('Please ensure all tier names are unique.');
        return;
    }
});
</script>
@endsection