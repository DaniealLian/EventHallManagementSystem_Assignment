@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout for {{ $reservation->event->title }}</h2>
    <p>Slots selected: {{ $reservation->reservationItems->sum('quantity') }}</p>
    <p>Total: RM {{ number_format($reservation->total_price, 2) }}</p>

    <form method="POST" action="{{ route('payments.process') }}">
        @csrf
        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

        <div class="mb-3">
            <label for="method">Select Payment Method:</label>
            <select name="method" class="form-control" required>
                <option value="card">Credit / Debit Card</option>
                <option value="online_banking">Online Banking (FPX)</option>
                <option value="e_wallet">E-Wallet</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</div>
@endsection
