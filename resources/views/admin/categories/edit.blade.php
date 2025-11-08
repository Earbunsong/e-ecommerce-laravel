@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Category</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active">Edit: {{ $category->name }}</li>
        </ol>
    </nav>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="custom-card-body">
                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $category->name) }}" required
                               placeholder="e.g., Laptops, Accessories">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="form-label">URL Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                               placeholder="Auto-generated from name">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty to auto-generate</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-0">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5"
                                  placeholder="Enter category description...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Image Upload -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-image me-2"></i>Category Image</h5>
                </div>
                <div class="custom-card-body">
                    @if($category->image)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="img-fluid rounded"
                                 id="current-image"
                                 style="max-height: 200px;">
                            <p class="text-muted mt-2 mb-0 small">Current image</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label">
                            {{ $category->image ? 'Replace Image' : 'Upload Image' }}
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">Recommended: 500x500px, max 2MB</small>
                    </div>

                    <div id="image-preview" class="text-center d-none">
                        <p class="text-muted mb-2 small">New image preview:</p>
                        <img id="preview" src="" alt="Preview" class="img-fluid rounded"
                             style="max-height: 200px;">
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-gear me-2"></i>Settings</h5>
                </div>
                <div class="custom-card-body">
                    <!-- Display Order -->
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                               id="display_order" name="display_order"
                               value="{{ old('display_order', $category->display_order) }}" min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>

                    <!-- Active Status -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Active</strong>
                            <small class="d-block text-muted">Visible on website</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-graph-up me-2"></i>Statistics</h5>
                </div>
                <div class="custom-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block">Total Products</small>
                            <h4 class="mb-0">{{ $category->products()->count() }}</h4>
                        </div>
                        <div class="text-end">
                            <i class="bi bi-box-seam fs-2 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block">Active Products</small>
                            <h4 class="mb-0 text-success">{{ $category->products()->where('is_active', true)->count() }}</h4>
                        </div>
                        <div class="text-end">
                            <i class="bi bi-check-circle fs-2 text-success"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Created</small>
                            <small class="text-dark">{{ $category->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($category->updated_at != $category->created_at)
                        <div class="text-end">
                            <small class="text-muted d-block">Updated</small>
                            <small class="text-dark">{{ $category->updated_at->format('M d, Y') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="custom-card">
                <div class="custom-card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-2"></i>Update Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Auto-generate slug from name (only if manually editing)
    let slugManuallyEdited = {{ $category->slug ? 'true' : 'false' }};

    document.getElementById('slug').addEventListener('input', function() {
        slugManuallyEdited = this.value !== '';
    });

    document.getElementById('name').addEventListener('input', function() {
        if (!slugManuallyEdited) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        }
    });

    // Image preview
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection