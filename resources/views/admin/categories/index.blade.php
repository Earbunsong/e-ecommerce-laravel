@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Categories</h1>
        <p class="page-subtitle">Manage your product categories</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Category
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Card -->
<div class="custom-card mb-4">
    <div class="custom-card-body">
        <form action="{{ route('admin.categories.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search categories..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select name="sort_by" class="form-select">
                        <option value="display_order" {{ request('sort_by') == 'display_order' ? 'selected' : '' }}>Display Order</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="products_count" {{ request('sort_by') == 'products_count' ? 'selected' : '' }}>Products Count</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Categories Grid -->
<div class="row g-4 mb-4">
    @forelse($categories as $category)
        <div class="col-md-6 col-lg-4">
            <div class="custom-card h-100">
                <div class="custom-card-body">
                    <!-- Category Image/Icon -->
                    <div class="text-center mb-3">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="rounded"
                                 style="width: 100%; height: 180px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded"
                                 style="width: 100%; height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-folder text-white" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Category Info -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">{{ $category->name }}</h5>
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2">
                            <code>{{ $category->slug }}</code>
                        </p>
                        @if($category->description)
                            <p class="text-muted small mb-0">{{ Str::limit($category->description, 80) }}</p>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block">Products</small>
                            <strong class="text-primary">{{ $category->products_count }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Order</small>
                            <strong>{{ $category->display_order }}</strong>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.categories.show', $category) }}"
                           class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-success flex-fill">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.toggle-status', $category) }}"
                              method="POST" class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                <i class="bi bi-lightning"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>
                            @if($category->products_count > 0)
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    This category has <strong>{{ $category->products_count }} products</strong>.
                                    Please reassign or delete products first.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                    Delete Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="custom-card text-center py-5">
                <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 mb-2">No Categories Found</h4>
                <p class="text-muted mb-4">Get started by creating your first category</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create First Category
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($categories->hasPages())
    <div class="d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
@endif
@endsection