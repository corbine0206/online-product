@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="stat-card h5">Total Users</div>
                <h3>{{ $totalUsers }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #2ecc71;">
                <div class="stat-card h5">Total Products</div>
                <h3>{{ $totalProducts }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #27ae60;">
                <div class="stat-card h5">Active Products</div>
                <h3>{{ $activeProducts }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #e74c3c;">
                <div class="stat-card h5">Low Stock</div>
                <h3>{{ $lowStockProducts }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Users and Products -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Users</h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count())
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All Users</a>
                    @else
                        <p class="text-muted">No users found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Products</h5>
                </div>
                <div class="card-body">
                    @if($recentProducts->count())
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All Products</a>
                    @else
                        <p class="text-muted">No products found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
