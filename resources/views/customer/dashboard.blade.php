@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
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
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('customer.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                        <h5>{{ $customer->first_name ?? 'Customer' }} {{ $customer->last_name ?? '' }}</h5>
                        <p class="text-muted">{{ $customer->email ?? '' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>First Name:</strong> {{ $customer->first_name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Last Name:</strong> {{ $customer->last_name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong> {{ $customer->email ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}
                            </div>
                            <div class="col-12 mb-3">
                                <strong>Address:</strong> {{ $customer->address ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>City:</strong> {{ $customer->city ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>State:</strong> {{ $customer->state ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Postal Code:</strong> {{ $customer->postal_code ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Country:</strong> {{ $customer->country ?? 'N/A' }}
                            </div>
                            <div class="col-12 mb-3">
                                <strong>Status:</strong> 
                                <span class="badge bg-success">{{ $customer->status ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('cart.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
