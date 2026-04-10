<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Online Product Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-container {
            max-width: 800px;
            width: 100%;
            margin: 20px;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        .order-number {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.2rem;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin: 10px;
            transition: transform 0.2s;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            color: white;
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin: 10px;
            transition: all 0.2s;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            text-align: left;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 40px 20px;
            }
            
            .success-icon {
                width: 100px;
                height: 100px;
            }
            
            .success-icon i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1 class="mb-4">Order Successful!</h1>
            <p class="text-muted mb-4">Thank you for your purchase. Your order has been successfully processed.</p>
            
            <div class="order-number">
                Transaction #{{ $transaction->transaction_number }}
            </div>
            
            <div class="order-details">
                <h5 class="mb-4">Order Details</h5>
                
                <div class="order-item">
                    <div>
                        <strong>Status:</strong>
                        <span class="badge bg-info ms-2">{{ $transaction->status }}</span>
                    </div>
                    <div>
                        <strong>Total:</strong>
                        <span class="text-primary">${{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <div class="order-item">
                    <div>
                        <strong>Payment Method:</strong>
                        <span class="ms-2">{{ ucfirst($transaction->payment_method) }}</span>
                    </div>
                    <div>
                        <strong>Payment Status:</strong>
                        <span class="badge bg-success ms-2">{{ $transaction->payment_status }}</span>
                    </div>
                </div>
                
                <div class="order-item">
                    <div>
                        <strong>Transaction Date:</strong>
                    </div>
                    <div class="text-end">
                        {{ $transaction->transaction_date ? $transaction->transaction_date->format('F j, Y, g:i A') : 'N/A' }}
                    </div>
                </div>
                
                @if($transaction->items->count() > 0)
                <h6 class="mt-4 mb-3">Items Purchased:</h6>
                @foreach($transaction->items as $item)
                <div class="order-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <strong>{{ $item->product_name }}</strong>
                            <br>
                            <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small>
                        </div>
                    </div>
                    <div>
                        <strong>${{ number_format($item->subtotal, 2) }}</strong>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Continue Shopping
                </a>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-tachometer-alt"></i> View Dashboard
                </a>
            </div>
            
            <div class="mt-4">
                <p class="text-muted small">
                    <i class="fas fa-envelope"></i> A confirmation email has been sent to your registered email
                </p>
                <p class="text-muted small">
                    <i class="fas fa-phone"></i> Need help? Contact us at support@onlinestore.com
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some celebration effects
        window.addEventListener('load', function() {
            // You could add confetti or other celebration effects here
            console.log('Order successful! Transaction #{{ $transaction->transaction_number }}');
        });
    </script>
</body>
</html>
