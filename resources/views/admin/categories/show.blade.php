@extends('layouts.app')

@section('title', $category->name . ' - Admin')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Category Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Category Details</h5>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($category->image)
                        <div class="text-center mb-4">
                            <img src="{{ $category->image_url }}"
                                 alt="{{ $category->name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 250px;">
                        </div>
                    @endif

                    <h4 class="mb-3">{{ $category->name }}</h4>

                    @if($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <strong>Slug:</strong>
                        <br>
                        <code>{{ $category->slug }}</code>
                    </div>

                    <div class="mb-3">
                        <strong>Display Order:</strong>
                        <br>
                        {{ $category->display_order }}
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <br>
                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <br>
                        {{ $category->created_at->format('M d, Y \a\t h:i A') }}
                    </div>

                    @if($category->updated_at != $category->created_at)
                        <div class="mb-3">
                            <strong>Last Updated:</strong>
                            <br>
                            {{ $category->updated_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="mb-0">{{ $category->products_count }}</h3>
                            <small class="text-muted">Total Products</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="mb-0">{{ $category->products()->where('is_active', true)->count() }}</h3>
                            <small class="text-muted">Active Products</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">{{ $category->products()->where('in_stock', true)->count() }}</h3>
                            <small class="text-muted">In Stock</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">{{ $category->products()->where('is_featured', true)->count() }}</h3>
                            <small class="text-muted">Featured</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Products in this Category -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Products in {{ $category->name }}</h5>
                    <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </a>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ $product->image_url }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->is_featured)
                                                    <span class="badge bg-warning text-dark">Featured</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $product->in_stock ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($products->hasPages())
                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-3">No products in this category yet</p>
                            <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add First Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection