@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">📢 Payment Status</h1>

    {{-- Success --}}
    @if($status === 'paid')
        <div class="alert alert-success p-4">
            <h2>✅ Payment Successful</h2>
            <p>Your payment for <strong>{{ $transaction->event->title ?? 'the event' }}</strong> has been received.</p>
            <p><strong>Amount:</strong> RM {{ number_format($transaction->amount, 2) }}</p>
            <p><strong>Reference ID:</strong> {{ $transaction->id }}</p>
        </div>

    {{-- Pending --}}
    @elseif($status === 'pending')
        <div class="alert alert-warning p-4">
            <h2>⏳ Payment Pending</h2>
            <p>Your payment is being processed. Please check again later.</p>
            <p><strong>Reference ID:</strong> {{ $transaction->id }}</p>
        </div>

    {{-- Failed --}}
    @else
        <div class="alert alert-danger p-4">
            <h2>❌ Payment Failed</h2>
            <p>Unfortunately, your payment could not be completed.</p>
            <p><strong>Reference ID:</strong> {{ $transaction->id ?? '-' }}</p>
        </div>
    @endif

    <a href="{{ route('payment.index') }}" class="btn btn-primary mt-4">🔙 Back to Transactions</a>
</div>
@endsection
