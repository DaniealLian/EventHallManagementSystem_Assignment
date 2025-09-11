<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservation - bookMyShow</title>
  @vite('resources/css/app.css')   <!--connect to css files-->
  @vite('resources/js/app.js')
</head>
<div class="container">
    <h1>🎫 Event Reservations Management</h1>
    <p>Total Events: <strong>{{ $event->count() }}</strong></p>
        
</div>


<body>
    <h1>Reserve for: {{ $event->event_name }}</h1>

    <form method="POST" action="{{ route('reservations.store', $event) }}">
        @csrf

        <label for="reserved_date_time">Select Reservation Date:</label>
        <input type="date" id="reserved_date_time" name="reserved_date_time" required>
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

        <br>
        <button type="submit">Confirm Reservation</button>
    </form>


  

</body>
</html>
