<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KHQR Payment - K2 Computer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,122.7C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            pointer-events: none;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Pulse Animation */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        .pulse-ring {
            animation: pulse-ring 1.5s ease-out infinite;
        }

        /* Shine Effect */
        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        .shine-effect {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            background-size: 200% auto;
            animation: shine 2s linear infinite;
        }

        /* QR Code Container Styling */
        .qr-container {
            position: relative;
            padding: 20px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .qr-container::before {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, #667eea, #764ba2, #667eea);
            border-radius: 28px;
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Card Styling */
        .payment-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% auto;
            animation: shine 2s linear infinite;
        }

        /* Bakong Logo Styling */
        .bakong-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* Status Indicator */
        .status-waiting {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }

        .status-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Instruction Steps */
        .instruction-step {
            display: flex;
            align-items: start;
            gap: 12px;
            padding: 12px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .instruction-step:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .step-number {
            min-width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Timer Styling */
        .timer-display {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid #fbbf24;
            color: #92400e;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .payment-card {
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="gradient-bg">
        <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-lg w-full">

                <!-- Header with Logo -->
                <div class="text-center mb-8 float-animation">
                    <div class="bakong-badge mx-auto mb-4">
                        <i class="fas fa-qrcode text-xl"></i>
                        <span>Bakong KHQR Payment</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Scan to Pay</h1>
                    <p class="text-white text-opacity-90 text-lg">Order #{{ $order->order_number }}</p>
                </div>

                <!-- Payment Card -->
                <div class="payment-card p-6 md:p-8">

                    <!-- Amount Display -->
                    <div class="text-center mb-8">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Amount</p>
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <span class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                ${{ number_format($amount, 2) }}
                            </span>
                        </div>
                        <p class="text-lg text-gray-600 font-medium">
                            <i class="fas fa-equals text-xs mr-2"></i>
                            {{ number_format($amount * 4100, 0) }} KHR
                        </p>
                    </div>

                    <!-- QR Code with Bakong Branding -->
                    <div class="flex justify-center mb-8">
                        <div class="qr-container">
                            <div id="qrcode" class="flex items-center justify-center"></div>
                            <div class="text-center mt-4">
                                <div class="inline-flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-semibold text-blue-900">Powered by Bakong</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div id="payment-status" class="mb-6">
                        <div class="status-waiting text-white rounded-2xl p-4 text-center">
                            <div class="flex items-center justify-center gap-3 mb-2">
                                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="status-text" class="font-bold text-lg">Waiting for payment...</span>
                            </div>
                            <p class="text-sm text-white text-opacity-90">
                                Payment will be verified automatically
                            </p>
                        </div>
                    </div>

                    <!-- Timer -->
                    <div class="text-center mb-8">
                        <div class="timer-display mx-auto">
                            <i class="fas fa-clock"></i>
                            <span id="timer">15:00</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">QR code expires in</p>
                    </div>

                    <!-- Instructions -->
                    <div class="mb-8">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center text-lg">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            How to Pay
                        </h3>
                        <div class="space-y-3">
                            <div class="instruction-step">
                                <div class="step-number">1</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Open Banking App</p>
                                    <p class="text-xs text-gray-600">ABA, ACLEDA, Wing, or any Bakong-enabled app</p>
                                </div>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">2</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Find Scan QR Feature</p>
                                    <p class="text-xs text-gray-600">Look for "Scan QR" or "KHQR" option</p>
                                </div>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">3</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Scan the QR Code</p>
                                    <p class="text-xs text-gray-600">Point your camera at the QR code above</p>
                                </div>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">4</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Confirm & Complete</p>
                                    <p class="text-xs text-gray-600">Verify the amount and complete payment</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supported Banks -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-4 mb-6">
                        <p class="text-xs font-semibold text-gray-700 text-center mb-3">
                            <i class="fas fa-shield-alt text-blue-600 mr-1"></i>
                            Supported by all Bakong banks
                        </p>
                        <div class="flex items-center justify-center gap-4 flex-wrap text-xs text-gray-600">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-500"></i> ABA
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-500"></i> ACLEDA
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-500"></i> Wing
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-500"></i> Canadia
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-plus-circle text-blue-500"></i> More
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button id="verify-btn"
                                class="btn-primary w-full text-white font-bold py-4 px-6 rounded-xl transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>I've Completed Payment</span>
                        </button>
                        <a href="{{ route('checkout.index') }}"
                           class="btn-secondary w-full font-semibold py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Checkout</span>
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-white text-opacity-90 text-sm">
                        <i class="fas fa-headset mr-2"></i>
                        Need help?
                        <a href="#" class="font-bold underline hover:text-yellow-300 transition">Contact Support</a>
                    </p>
                </div>

                <!-- Security Badge -->
                <div class="mt-4 text-center">
                    <div class="inline-flex items-center gap-2 bg-white bg-opacity-20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm">
                        <i class="fas fa-lock"></i>
                        <span>Secure Payment · SSL Encrypted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const MD5_HASH = '{{ $md5 }}';
        const ORDER_NUMBER = '{{ $order->order_number }}';
        const KHQR_STRING = '{{ $qrCode }}';
        const CHECK_INTERVAL = 3000;
        const TIMEOUT_MINUTES = 15;

        let checkInterval;
        let timeRemaining = TIMEOUT_MINUTES * 60;
        let timerInterval;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            new QRCode(document.getElementById("qrcode"), {
                text: KHQR_STRING,
                width: 280,
                height: 280,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

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

            let icon = '';
            let statusClass = '';
            let bgClass = '';

            switch(type) {
                case 'loading':
                    icon = `<svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>`;
                    bgClass = 'status-waiting';
                    break;
                case 'success':
                    icon = `<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>`;
                    bgClass = 'status-success';
                    break;
                case 'error':
                    icon = `<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>`;
                    bgClass = 'status-error';
                    break;
            }

            statusDiv.innerHTML = `
                <div class="${bgClass} text-white rounded-2xl p-4 text-center">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        ${icon}
                        <span class="font-bold text-lg">${message}</span>
                    </div>
                    <p class="text-sm text-white text-opacity-90">
                        ${type === 'loading' ? 'Please wait...' : ''}
                    </p>
                </div>
            `;
        }
    </script>
</body>
</html>
