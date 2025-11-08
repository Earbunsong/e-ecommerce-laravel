<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KHQR Payment - K2 Computer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">KHQR Payment</h1>
                <p class="text-gray-600">Order #{{ $order['order_number'] }}</p>
            </div>

            <!-- Payment Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Amount -->
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                    <p class="text-4xl font-bold text-gray-900">${{ number_format($amount, 2) }}</p>
                    <p class="text-sm text-gray-500 mt-1">≈ {{ number_format($amount * 4100, 0) }} KHR</p>
                </div>

                <!-- QR Code -->
                <div class="flex justify-center mb-6">
                    <div class="bg-white p-4 rounded-lg border-2 border-gray-200">
                        <img src="data:image/png;base64,{{ $qrCode }}"
                             alt="KHQR Payment QR Code"
                             class="w-64 h-64">
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        How to Pay
                    </h3>
                    <ol class="text-sm text-blue-900 space-y-2 ml-7">
                        <li>1. Open your banking app (ABA, ACLEDA, etc.)</li>
                        <li>2. Select "Scan QR" or "KHQR"</li>
                        <li>3. Scan the QR code above</li>
                        <li>4. Confirm the payment amount</li>
                        <li>5. Complete the transaction</li>
                    </ol>
                </div>

                <!-- Payment Status -->
                <div id="payment-status" class="text-center">
                    <div class="flex items-center justify-center space-x-2 text-yellow-600">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="status-text">Waiting for payment...</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        We'll automatically verify your payment once completed
                    </p>
                </div>

                <!-- Timer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Time remaining: <span id="timer" class="font-semibold text-gray-900">15:00</span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-3">
                    <button id="verify-btn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        I've Completed Payment
                    </button>
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Cancel Payment
                    </a>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Having trouble? <a href="#" class="text-blue-600 hover:underline">Contact Support</a></p>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const MD5_HASH = '{{ $md5 }}';
        const ORDER_NUMBER = '{{ $order['order_number'] }}';
        const CHECK_INTERVAL = 3000; // Check every 3 seconds
        const TIMEOUT_MINUTES = 15;

        let checkInterval;
        let timeRemaining = TIMEOUT_MINUTES * 60; // in seconds
        let timerInterval;

        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Start checking for payment
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
            startPaymentCheck();
        });

        // Timer function
        function startTimer() {
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    clearInterval(checkInterval);
                    handleTimeout();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            document.getElementById('timer').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function handleTimeout() {
            updateStatus('error', 'Payment timeout. Please try again.');
            document.getElementById('verify-btn').disabled = true;
        }

        // Auto-check payment status
        function startPaymentCheck() {
            checkInterval = setInterval(checkPaymentStatus, CHECK_INTERVAL);
        }

        async function checkPaymentStatus() {
            try {
                const response = await fetch('{{ route('payment.khqr.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ md5: MD5_HASH })
                });

                const data = await response.json();

                if (data.success && data.paid) {
                    clearInterval(checkInterval);
                    clearInterval(timerInterval);
                    await verifyAndComplete();
                }
            } catch (error) {
                console.error('Error checking payment:', error);
            }
        }

        // Manual verify button
        document.getElementById('verify-btn').addEventListener('click', async function() {
            this.disabled = true;
            await verifyAndComplete();
        });

        async function verifyAndComplete() {
            updateStatus('loading', 'Verifying payment...');

            try {
                const response = await fetch('{{ route('payment.khqr.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        md5: MD5_HASH,
                        order_number: ORDER_NUMBER
                    })
                });

                const data = await response.json();

                if (data.success) {
                    updateStatus('success', 'Payment successful! Redirecting...');
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1500);
                } else {
                    updateStatus('error', data.message || 'Payment verification failed');
                    document.getElementById('verify-btn').disabled = false;
                }
            } catch (error) {
                console.error('Error verifying payment:', error);
                updateStatus('error', 'Failed to verify payment. Please try again.');
                document.getElementById('verify-btn').disabled = false;
            }
        }

        function updateStatus(type, message) {
            const statusDiv = document.getElementById('payment-status');
            const statusText = document.getElementById('status-text');

            let icon = '';
            let colorClass = '';

            switch(type) {
                case 'loading':
                    icon = `<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>`;
                    colorClass = 'text-yellow-600';
                    break;
                case 'success':
                    icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>`;
                    colorClass = 'text-green-600';
                    break;
                case 'error':
                    icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>`;
                    colorClass = 'text-red-600';
                    break;
            }

            statusDiv.innerHTML = `
                <div class="flex items-center justify-center space-x-2 ${colorClass}">
                    ${icon}
                    <span>${message}</span>
                </div>
            `;
        }
    </script>
</body>
</html>