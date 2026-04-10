<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Online Product Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .checkout-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        
        .btn-checkout:hover {
            transform: scale(1.02);
            color: white;
        }
        
        .btn-checkout:disabled {
            opacity: 0.7;
            transform: none;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 20px;
        }
        
        .price-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method:hover {
            border-color: var(--primary-color);
        }
        
        .payment-method.selected {
            border-color: var(--primary-color);
            background-color: #f8f9ff;
        }
        
        .StripeElement {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            background-color: white;
        }
        
        .StripeElement--focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-shopping-bag"></i> Online Store
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i> Cart
                            @if($cartCount = Auth::guard('web')->check() ? \App\Models\CartItem::where('user_id', Auth::guard('web')->id())->sum('quantity') : 0)
                                <span class="badge bg-primary">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @if(auth()->guard('web')->check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->guard('web')->user()->first_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('cart.index') }}">Cart</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="checkout-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-credit-card"></i> Checkout</h1>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
        </div>

        @if(empty($cartItems))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Your cart is empty.
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <h4 class="mb-4">
                            <i class="fas fa-shopping-bag"></i> Order Summary
                        </h4>
                        
                        @foreach($cartItems as $item)
                            <div class="order-summary-item">
                                <div>
                                    <strong>{{ $item['product']->name }}</strong>
                                    <br>
                                    <small class="text-muted">Qty: {{ $item['quantity'] }} × ${{ number_format($item['product']->price, 2) }}</small>
                                </div>
                                <div>
                                    <strong>${{ number_format($item['subtotal'], 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="order-summary-total">
                            <div>
                                <strong>Total</strong>
                            </div>
                            <div>
                                <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-card mt-4">
                        <h4 class="mb-4">
                            <i class="fas fa-info-circle"></i> Payment Information
                        </h4>
                        <p class="text-muted">You will be redirected to Stripe's secure payment page to complete your purchase. Stripe accepts all major credit cards and provides secure payment processing.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="checkout-card">
                        <h4 class="mb-4">
                            <i class="fas fa-lock"></i> Secure Checkout
                        </h4>
                        
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                Secure payment processing
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                Multiple payment methods accepted
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                Mobile-optimized payment page
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                Built-in fraud protection
                            </li>
                        </ul>
                        
                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 mt-4">
                                <i class="fas fa-credit-card"></i> Proceed to Payment - ${{ number_format($total, 2) }}
                            </button>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Powered by Stripe
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
