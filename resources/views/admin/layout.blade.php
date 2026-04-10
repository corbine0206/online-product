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
            z-index: 1000;
            transform: translateX(0);
            transition: transform 0.3s ease;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .sidebar-overlay.show {
            display: block;
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
        .sidebar-section-title {
            padding: 10px 20px;
            font-size: 12px;
            font-weight: bold;
            color: #bdc3c7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 0;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            cursor: pointer;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
            }
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .main-content.expanded {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
            }
            .main-content {
                padding: 10px;
            }
            .sidebar-title {
                font-size: 18px;
                padding: 15px;
            }
            .sidebar a {
                padding: 10px 15px;
            }
            .sidebar-section-title {
                padding: 8px 15px;
                font-size: 11px;
            }
        }
    </style>
    @yield('extra-styles')
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-title">
            <i class="fas fa-shopping-bag"></i> Admin Panel
        </div>
        <a href="{{ route('admin.dashboard') }}" class="@if(Route::is('admin.dashboard')) active @endif">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        
        <h6 class="sidebar-section-title">User Management</h6>
        <a href="{{ route('admin.users.index') }}" class="@if(Route::is('admin.users.*')) active @endif">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{ route('admin.customers.index') }}" class="@if(Route::is('admin.customers.*')) active @endif">
            <i class="fas fa-user-tie"></i> Customers
        </a>
        
        <h6 class="sidebar-section-title">Access Control</h6>
        <a href="{{ route('admin.roles.index') }}" class="@if(Route::is('admin.roles.*')) active @endif">
            <i class="fas fa-shield-alt"></i> Roles
        </a>
        <a href="{{ route('admin.permissions.index') }}" class="@if(Route::is('admin.permissions.*')) active @endif">
            <i class="fas fa-key"></i> Permissions
        </a>
        
        <h6 class="sidebar-section-title">Business Operations</h6>
        <a href="{{ route('admin.products.index') }}" class="@if(Route::is('admin.products.*')) active @endif">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="{{ route('admin.sales-transactions.index') }}" class="@if(Route::is('admin.sales-transactions.*')) active @endif">
            <i class="fas fa-shopping-cart"></i> Sales Transactions
        </a>
        <a href="{{ route('admin.sales-dashboard.index') }}" class="@if(Route::is('admin.sales-dashboard.*')) active @endif">
            <i class="fas fa-chart-line"></i> Sales Dashboard
        </a>
        <hr style="border-color: #34495e; margin: 20px 0;">
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="mobile-toggle me-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                        <i class="fas fa-times d-none"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1">Admin Panel</span>
                </div>
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
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.querySelector('.sidebar-overlay');
            const toggleButton = document.querySelector('.mobile-toggle');
            
            if (!sidebar || !mainContent || !toggleButton) {
                console.error('Sidebar elements not found');
                return;
            }
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            overlay.classList.toggle('show');
            
            const barsIcon = toggleButton.querySelector('.fa-bars');
            const timesIcon = toggleButton.querySelector('.fa-times');
            
            if (sidebar.classList.contains('collapsed')) {
                barsIcon.classList.remove('d-none');
                timesIcon.classList.add('d-none');
            } else {
                barsIcon.classList.add('d-none');
                timesIcon.classList.remove('d-none');
            }
        }
        
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggleButton.contains(event.target) &&
                !sidebar.classList.contains('collapsed')) {
                toggleSidebar();
            }
        });
        
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.querySelector('.sidebar-overlay');
            const toggleButton = document.querySelector('.mobile-toggle');
            const barsIcon = toggleButton ? toggleButton.querySelector('.fa-bars') : null;
            const timesIcon = toggleButton ? toggleButton.querySelector('.fa-times') : null;
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                overlay.classList.remove('show');
                if (barsIcon) barsIcon.classList.add('d-none');
                if (timesIcon) timesIcon.classList.add('d-none');
            }
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.querySelector('.sidebar-overlay');
            const toggleButton = document.querySelector('.mobile-toggle');
            
            if (!sidebar || !mainContent || !toggleButton) {
                console.error('Sidebar elements not found');
                return;
            }
            
            const barsIcon = toggleButton.querySelector('.fa-bars');
            const timesIcon = toggleButton.querySelector('.fa-times');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('expanded');
                overlay.classList.remove('show');
                if (barsIcon) barsIcon.classList.remove('d-none');
                if (timesIcon) timesIcon.classList.add('d-none');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                overlay.classList.remove('show');
                if (barsIcon) barsIcon.classList.add('d-none');
                if (timesIcon) timesIcon.classList.add('d-none');
            }
        });
    </script>
    @yield('extra-scripts')
</body>
</html>
