<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Online Product Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .cart-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .quantity-btn:hover {
            background: var(--secondary-color);
        }
        
        .remove-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .remove-btn:hover {
            background: #c0392b;
        }
        
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 20px;
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
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .empty-cart i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .price-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .cart-container {
                padding: 10px;
            }
            
            .cart-item {
                padding: 15px;
            }
            
            .product-image {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                        <a class="nav-link" href="#products">Products</a>
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
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" id="cartCount">{{ count($cartItems) }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="cart-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-shopping-cart"></i> Shopping Cart</h1>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>

        @if(count($cartItems) > 0)
            <div class="row">
                <div class="col-lg-8">
                    @foreach($cartItems as $item)
                    <div class="cart-item" id="cart-item-{{ $item['product']->id }}">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($item['product']->hasImages())
                                    <img src="{{ $item['product']->primary_image_url }}" alt="{{ $item['product']->name }}" class="product-image">
                                @else
                                    <img src="https://via.placeholder.com/100x100/cccccc/666666?text=No+Image" alt="{{ $item['product']->name }}" class="product-image">
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-1">{{ $item['product']->name }}</h5>
                                <p class="text-muted small mb-0">{{ Str::limit($item['product']->description, 100) }}</p>
                                <p class="text-muted small mb-0">SKU: {{ $item['product']->sku }}</p>
                            </div>
                            <div class="col-md-2">
                                <div class="price-display">${{ number_format($item['product']->price, 2) }}</div>
                                <small class="text-muted">per item</small>
                            </div>
                            <div class="col-md-2">
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" 
                                            @if($item['quantity'] <= 1) disabled @endif>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" id="quantity-{{ $item['product']->id }}" 
                                           value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}"
                                           onchange="updateQuantity({{ $item['product']->id }}, this.value)">
                                    <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})"
                                            @if($item['quantity'] >= $item['product']->stock) disabled @endif>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Stock: {{ $item['product']->stock }}</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="price-display" id="subtotal-{{ $item['product']->id }}">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </div>
                                <button class="remove-btn mt-2" onclick="removeFromCart({{ $item['product']->id }})">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="mb-4">Order Summary</h4>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span id="subtotal">${{ number_format($total, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <span>$0.00</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total</h5>
                            <h5 id="total">${{ number_format($total, 2) }}</h5>
                        </div>
                        
                        @if(auth()->guard('web')->check())
                            <a href="{{ route('checkout.index') }}" class="btn btn-checkout">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>
                        @else
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i> Please login to proceed with checkout
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-checkout w-100 mb-2">
                                <i class="fas fa-sign-in-alt"></i> Login to Checkout
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user-plus"></i> Create Account
                            </a>
                        @endif
                        
                        <div class="mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1 || quantity > 10) return;
            
            fetch('{{ route("cart.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update subtotal
                    document.getElementById(`subtotal-${productId}`).textContent = `$${data.subtotal.toFixed(2)}`;
                    
                    // Update quantity input
                    document.getElementById(`quantity-${productId}`).value = quantity;
                    
                    // Update cart count
                    updateCartCount(data.cart_count);
                    
                    // Update totals
                    updateTotals();
                    
                    // Show success message
                    showToast('Cart updated successfully', 'success');
                } else {
                    showToast(data.message || 'Error updating cart', 'error');
                }
            })
            .catch(error => {
                showToast('Error updating cart', 'error');
            });
        }

        function removeFromCart(productId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                fetch('{{ route("cart.remove") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove cart item
                        document.getElementById(`cart-item-${productId}`).remove();
                        
                        // Update cart count
                        updateCartCount(data.cart_count);
                        
                        // Update totals
                        updateTotals();
                        
                        // Check if cart is empty
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                        
                        showToast('Item removed from cart', 'success');
                    } else {
                        showToast(data.message || 'Error removing item', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error removing item', 'error');
                });
            }
        }

        function updateCartCount(count) {
            document.getElementById('cartCount').textContent = count;
        }

        function updateTotals() {
            // This would ideally be calculated server-side, but for simplicity we'll recalculate
            const cartItems = document.querySelectorAll('.cart-item');
            let total = 0;
            
            cartItems.forEach(item => {
                const priceText = item.querySelector('.price-display').textContent;
                const price = parseFloat(priceText.replace('$', ''));
                const quantity = item.querySelector('.quantity-input').value;
                total += price * quantity;
            });
            
            document.getElementById('subtotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        function showToast(message, type) {
            const toastHtml = `
                <div class="toast show" role="alert">
                    <div class="toast-header bg-${type === 'success' ? 'success' : 'danger'} text-white">
                        <strong class="me-auto">${type === 'success' ? 'Success' : 'Error'}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            const toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1050';
            toastContainer.innerHTML = toastHtml;
            document.body.appendChild(toastContainer);
            
            setTimeout(() => {
                toastContainer.remove();
            }, 3000);
        }
    </script>
</body>
</html>
