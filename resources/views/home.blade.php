<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Product Store</title>
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
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 60px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .product-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .btn-add-cart {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-add-cart:hover {
            transform: scale(1.05);
            color: white;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 50px;
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 2px;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .stock-low {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .stock-good {
            color: #27ae60;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
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
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
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
                            <span class="cart-count" id="cartCount">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">Welcome to Our Store</h1>
            <p class="hero-subtitle">Discover amazing products at unbeatable prices</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#products" class="btn btn-light btn-lg px-5">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
                <a href="#about" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-info-circle"></i> Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="section-title">Featured Products</h2>
            
            <div class="row g-4">
                @forelse($featuredProducts as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="product-card">
                        <div class="position-relative">
                            @if($product->hasImages())
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <img src="https://via.placeholder.com/300x250/cccccc/666666?text=No+Image" alt="{{ $product->name }}" class="product-image">
                            @endif
                            @if($product->stock < 10)
                                <span class="product-badge">Low Stock</span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <span class="small {{ $product->stock < 10 ? 'stock-low' : 'stock-good' }}">
                                    {{ $product->stock }} in stock
                                </span>
                            </div>
                            <button class="btn btn-add-cart w-100" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No featured products available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Latest Products</h2>
            
            <div class="row g-4">
                @forelse($latestProducts as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="product-card">
                        <div class="position-relative">
                            @if($product->hasImages())
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <img src="https://via.placeholder.com/300x250/cccccc/666666?text=No+Image" alt="{{ $product->name }}" class="product-image">
                            @endif
                            @if($product->stock < 10)
                                <span class="product-badge">Low Stock</span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <span class="small {{ $product->stock < 10 ? 'stock-low' : 'stock-good' }}">
                                    {{ $product->stock }} in stock
                                </span>
                            </div>
                            <button class="btn btn-add-cart w-100" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No latest products available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-shopping-bag"></i> Online Store</h5>
                    <p>Your trusted online shopping destination for quality products.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#products" class="text-white text-decoration-none">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white text-decoration-none">Cart</a></li>
                        <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-envelope"></i> info@onlinestore.com</p>
                    <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Store St, City, State</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2024 Online Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast show" role="alert">
                <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(productId) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                    showToast('Product added to cart!', 'success');
                } else if (data.redirect) {
                    // Redirect to login if user is not authenticated
                    window.location.href = data.redirect;
                } else {
                    showToast(data.message || 'Error adding product to cart', 'error');
                }
            })
            .catch(error => {
                showToast('Error adding product to cart', 'error');
            });
        }

        function updateCartCount(count) {
            document.getElementById('cartCount').textContent = count;
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

        // Load initial cart count
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("cart.index") }}')
                .then(response => response.text())
                .then(html => {
                    // Extract cart count from the page (simplified approach)
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cartCountElement = doc.getElementById('cartCount');
                    if (cartCountElement) {
                        updateCartCount(cartCountElement.textContent);
                    }
                })
                .catch(error => {
                    console.log('Could not load cart count');
                });
        });
    </script>
</body>
</html>
