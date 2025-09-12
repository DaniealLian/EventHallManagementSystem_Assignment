<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Finalize Reservation - bookMyShow</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .countdown-timer {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            padding: 10px;
            background-color: #fff5f5;
            border: 2px solid #fed7d7;
            border-radius: 8px;
            margin: 20px 0;
        }
        .countdown-timer.warning {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { background-color: #fff5f5; }
            50% { background-color: #fed7d7; }
            100% { background-color: #fff5f5; }
        }
        .reservation-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background-color: #28a745;
            color: white;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            margin-right: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .event-details {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .actions {
            margin: 30px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Countdown Timer -->
        <div class="countdown-timer" id="countdown">
            ⏰ Time Remaining: <span id="timeDisplay">{{ gmdate('i:s', $sessionData['time_remaining'] ?? 0) }}</span>
        </div>

        <h1>🎫 Finalize Your Reservation</h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Event Details -->
        <div class="event-details">
            <h2>{{ $event->event_name }}</h2>
            <p><strong>📅 Date & Time:</strong> {{ \Carbon\Carbon::parse($reservation_session['reserved_date_time'])->format('M j, Y @ g:i A') }}</p>
        </div>

        <!-- Reservation Summary -->
        <div class="reservation-summary">
            <h3>📋 Reservation Summary</h3>
            
            @php $eventPricingTiers = $event->pricingTiers->keyBy('id'); @endphp
            
            @foreach($reservation_session['reservationItems'] as $item)
                @php $tier = $eventPricingTiers[$item['pricing_tier_id']]; @endphp
                <div class="summary-row">
                    <span>{{ $tier->tier }} ({{ $item['quantity'] }} × ${{ number_format($item['unit_price'], 2) }})</span>
                    <span>${{ number_format($item['subtotal'], 2) }}</span>
                </div>
            @endforeach
            
            <div class="summary-row">
                <span>Total Amount:</span>
                <span>${{ number_format($reservation_session['total_price'], 2) }}</span>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="alert alert-warning">
            <strong>⚠️ Important:</strong> Please review your reservation carefully. Once confirmed, this reservation cannot be changed. Your session will expire automatically if not confirmed within the time limit.
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <form method="POST" action="{{ route('reservations.confirm', [$event, $token]) }}" style="display: inline;">
                @csrf
                <a href="{{ route('reservations.create', $event) }}" class="btn btn-secondary">
                    ← Modify Reservation
                </a>
                <button type="submit" class="btn btn-primary" id="confirmBtn">
                    ✅ Confirm Reservation
                </button>
            </form>
        </div>

        <!-- Additional Info -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #6c757d; text-align: center;">
            <p><small>Session expires automatically for security. No charges will be made until you confirm.</small></p>
        </div>
    </div>

    <script>
        // Countdown timer functionality
        let timeRemaining = {{ $sessionData['time_remaining'] ?? 0 }};
        const countdownElement = document.getElementById('countdown');
        const timeDisplayElement = document.getElementById('timeDisplay');
        const confirmBtn = document.getElementById('confirmBtn');

        function updateCountdown() {
            if (timeRemaining <= 0) {
                // Session expired
                clearInterval(timerInterval);
                countdownElement.innerHTML = '⏰ <strong style="color: #dc3545;">SESSION EXPIRED</strong>';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Session Expired';
                
                // Show expiration message and redirect after 3 seconds
                setTimeout(() => {
                    alert('Your reservation session has expired. You will be redirected to start over.');
                    window.location.href = '{{ route("reservations.create", $event) }}';
                }, 3000);
                
                return;
            }

            // Calculate minutes and seconds
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            
            // Format time display
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            timeDisplayElement.textContent = timeString;

            // Add warning animation when less than 30 seconds
            if (timeRemaining <= 30) {
                countdownElement.classList.add('warning');
                countdownElement.style.color = '#dc3545';
            }

            // Update every second
            timeRemaining--;
        }

        // Start the countdown timer
        const timerInterval = setInterval(updateCountdown, 1000);
        
        // Update immediately
        updateCountdown();

        // Optional: Check session status via AJAX every 10 seconds
        setInterval(() => {
            fetch('{{ route("reservations.session.status", $token) }}')
                .then(response => response.json())
                .then(data => {
                    if (!data.exists) {
                        clearInterval(timerInterval);
                        alert('Session expired! Redirecting...');
                        window.location.href = '{{ route("reservations.create", $event) }}';
                    }
                })
                .catch(error => {
                    console.warn('Could not check session status:', error);
                });
        }, 10000);

        // Prevent accidental page refresh
        window.addEventListener('beforeunload', function(e) {
            if (timeRemaining > 0) {
                e.preventDefault();
                e.returnValue = 'You have an active reservation session. Are you sure you want to leave?';
            }
        });
    </script>
</body>
</html>