<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Online Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
        }
        .sidebar a {
            color: #ecf0f1;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #34495e;
            padding-left: 30px;
        }
        .sidebar a.active {
            background-color: #3498db;
            border-left: 4px solid #2980b9;
        }
        .sidebar-title {
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card {
            padding: 20px;
            border-left: 4px solid #3498db;
        }
        .stat-card h5 {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
        }
        .stat-card h3 {
            color: #2c3e50;
            margin-top: 10px;
        }
    </style>
    @yield('extra-styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-title">
            <i class="fas fa-shopping-bag"></i> Admin Panel
        </div>
        <a href="{{ route('admin.dashboard') }}" class="@if(Route::is('admin.dashboard')) active @endif">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="@if(Route::is('admin.users.*')) active @endif">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{ route('admin.products.index') }}" class="@if(Route::is('admin.products.*')) active @endif">
            <i class="fas fa-box"></i> Products
        </a>
        <hr style="border-color: #34495e; margin: 20px 0;">
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <span class="navbar-text ms-auto">
                    <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                </span>
            </div>
        </nav>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errors:</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-scripts')
</body>
</html>
