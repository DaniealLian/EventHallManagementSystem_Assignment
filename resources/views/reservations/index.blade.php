<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservation - bookMyShow</title>
  @vite('resources/css/app.css')   <!--connect to css files-->
  @vite('resources/js/app.js')
  @vite('resources/js/reservation.js')
</head>
<div class="container">
    <h1>🎫 Event Reservations Management</h1>
    <p>Total Events: <strong>{{ $event->count() }}</strong></p>
        
</div>


<body>
    <h1>Reserve for: {{ $event->event_name }}</h1>


    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

    @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <form method="POST" action="{{ route('reservations.store', $event) }}" id="reservationForm">
        @csrf

        <label for="reserved_date_time">Select Reservation Date:</label>
        <input type="datetime-local" id="reserved_date_time" name="reserved_date_time" 
            min="{{ now()->format('Y-m-d\TH:i') }}"
            required>
        <br><br>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Tier</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            @foreach($event->pricingTiers as $tier)
                <tr>
                    <td>{{ $tier->tier }}</td>
                    <td>{{ $tier->price }}</td>
                    <td>
                        <input type="number" name="tiers[{{ $tier->id }}]" value="0" min="0">
                    </td>
                </tr>
            @endforeach
        </table>

        <!-- Total Preview -->
            <div class="total-preview">
                <h3>Order Summary:</h3>
                <p><strong>Total Amount: $<span id="totalAmount">0.00</span></strong></p>
                <p><small>You will have 1 minute to confirm your reservation once submitted.</small></p>
            </div>
        <br>
        <button type="submit">Confirm Reservation</button>
    </form>


  

</body>
</html>
