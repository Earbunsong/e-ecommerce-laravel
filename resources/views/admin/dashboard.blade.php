@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with your store.</p>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3>{{ number_format($stats['total_products']) }}</h3>
            <p>Total Products</p>
            <div class="stats-trend up">
                <i class="bi bi-arrow-up"></i> {{ $stats['active_products'] }} active
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-folder"></i>
            </div>
            <h3>{{ number_format($stats['total_categories']) }}</h3>
            <p>Categories</p>
            <div class="stats-trend up">
                <i class="bi bi-arrow-up"></i> {{ $stats['active_categories'] }} active
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-star-fill"></i>
            </div>
            <h3>{{ number_format($stats['featured_products']) }}</h3>
            <p>Featured Products</p>
            <div class="stats-trend">
                <i class="bi bi-star"></i> Promoted items
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3>{{ number_format($stats['out_of_stock']) }}</h3>
            <p>Out of Stock</p>
            <div class="stats-trend {{ $stats['out_of_stock'] > 0 ? 'down' : 'up' }}">
                @if($stats['out_of_stock'] > 0)
                    <i class="bi bi-arrow-down"></i> Needs attention
                @else
                    <i class="bi bi-check-circle"></i> All stocked
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts & Tables Row -->
<div class="row g-3 mb-4">
    <!-- Recent Products -->
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="custom-card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-clock-history me-2"></i>Recent Products</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="custom-card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded me-3"
                                                 style="width: 45px; height: 45px; object-fit: cover;">
                                            <div>
                                                <strong class="d-block">{{ Str::limit($product->name, 30) }}</strong>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($product->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->in_stock ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock_quantity }} units
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">No products yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="col-lg-4">
        <div class="custom-card">
            <div class="custom-card-header">
                <h5><i class="bi bi-bar-chart-fill me-2"></i>Top Categories</h5>
            </div>
            <div class="custom-card-body">
                <div class="list-group list-group-flush">
                    @forelse($categories_with_products as $category)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->products_count }} products</small>
                            </div>
                            <div class="text-end">
                                <div class="progress" style="width: 100px; height: 8px;">
                                    <div class="progress-bar bg-primary"
                                         style="width: {{ ($category->products_count / $stats['total_products']) * 100 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ number_format(($category->products_count / $stats['total_products']) * 100, 1) }}%</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted my-4">No categories yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@if($low_stock_products->count() > 0)
<div class="row g-3">
    <div class="col-12">
        <div class="custom-card">
            <div class="custom-card-header">
                <h5><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Low Stock Alert</h5>
            </div>
            <div class="custom-card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($low_stock_products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded me-3"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <strong>{{ Str::limit($product->name, 40) }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-exclamation-circle"></i> {{ $product->stock_quantity }} left
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Update Stock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row g-3 mt-4">
    <div class="col-md-6">
        <div class="custom-card text-center p-4">
            <i class="bi bi-plus-circle fs-1 text-primary mb-3"></i>
            <h5>Add New Product</h5>
            <p class="text-muted">Create a new product listing</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Create Product
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card text-center p-4">
            <i class="bi bi-folder-plus fs-1 text-success mb-3"></i>
            <h5>Add New Category</h5>
            <p class="text-muted">Create a new product category</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-2"></i>Create Category
            </a>
        </div>
    </div>
</div>
@endsection