@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🛒 Checkout</h1>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Transaction Summary --}}
    <div class="card mb-4">
        <div class="card-header">Transaction Summary</div>
        <div class="card-body">
            <p><strong>Event:</strong> {{ $event->title ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> RM {{ number_format($amount, 2) }}</p>
        </div>
    </div>

    {{-- Payment Form --}}
    <form action="{{ route('payment.process') }}" method="POST">
        @csrf

        <input type="hidden" name="event_id" value="{{ $event->id ?? '' }}">
        <input type="hidden" name="amount" value="{{ $amount }}">

        <div class="mb-3">
            <label for="method" class="form-label"><strong>Select Payment Method</strong></label>
            <select name="method" id="method" class="form-select" required>
                <option value="">-- Choose --</option>
                <option value="card">💳 Credit/Debit Card</option>
                <option value="ewallet">📱 E-Wallet</option>
                <option value="onlinebanking">🏦 Online Banking</option>
            </select>
        </div>

        {{-- Example fields for card (can enhance with JS to show only when selected) --}}
        <div id="card-fields" class="mt-3" style="display: none;">
            <div class="mb-2">
                <label class="form-label">Card Number</label>
                <input type="text" name="card_number" class="form-control" maxlength="16" placeholder="Enter 16-digit card number">
            </div>
            <div class="mb-2">
                <label class="form-label">Expiry Date</label>
                <input type="text" name="expiry_date" class="form-control" placeholder="MM/YY">
            </div>
            <div class="mb-2">
                <label class="form-label">CVV</label>
                <input type="password" name="cvv" class="form-control" maxlength="3" placeholder="***">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">💰 Pay Now</button>
    </form>
</div>

{{-- Simple JS to toggle card fields --}}
<script>
    document.getElementById('method').addEventListener('change', function() {
        const cardFields = document.getElementById('card-fields');
        if (this.value === 'card') {
            cardFields.style.display = 'block';
        } else {
            cardFields.style.display = 'none';
        }
    });
</script>
@endsection