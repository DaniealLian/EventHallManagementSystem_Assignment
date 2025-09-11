@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Payments</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Event</th>
                <th>Seats</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $reservation)
                <tr>
                    <td>{{ $reservation->user?->name }}</td>
                    <td>{{ $reservation->event?->title }}</td>
                    <td>{{ $reservation->quantity }}</td>
                    <td>RM {{ number_format($reservation->total_price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $reservation->status == 'confirmed' ? 'success' : 'warning' }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($reservation->payment_method ?? 'N/A') }}</td>
                    <td>{{ $reservation->created_at->format('d M Y, H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection