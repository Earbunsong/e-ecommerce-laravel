@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Product</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">Edit: {{ $product->name }}</li>
        </ol>
    </nav>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Product Information -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>Product Information</h5>
                </div>
                <div class="custom-card-body">
                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand & SKU Row -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                   id="brand" name="brand" value="{{ old('brand', $product->brand) }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="mb-4">
                        <label class="form-label">Specifications</label>
                        <div id="specs-container">
                            @if($product->specs)
                                @foreach($product->specs as $spec)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-check2"></i></span>
                                        <input type="text" class="form-control" name="specs[]" value="{{ $spec }}">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="bi bi-check2"></i></span>
                                    <input type="text" class="form-control" name="specs[]" placeholder="e.g., Intel Core i5 Processor">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-spec">
                            <i class="bi bi-plus-circle me-1"></i>Add Specification
                        </button>
                    </div>

                    <!-- Colors -->
                    <div class="mb-4">
                        <label class="form-label">Available Colors</label>
                        <div id="colors-container">
                            @if($product->colors)
                                @foreach($product->colors as $color)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-palette"></i></span>
                                        <input type="text" class="form-control" name="colors[]" value="{{ $color }}">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-color">
                            <i class="bi bi-plus-circle me-1"></i>Add Color
                        </button>
                    </div>

                    <!-- Storage Options -->
                    <div class="mb-0">
                        <label class="form-label">Storage Options</label>
                        <div id="storage-container">
                            @if($product->storage_options)
                                @foreach($product->storage_options as $storage)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-hdd"></i></span>
                                        <input type="text" class="form-control" name="storage_options[]" value="{{ $storage }}">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-storage">
                            <i class="bi bi-plus-circle me-1"></i>Add Storage Option
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Product Image -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-image me-2"></i>Product Image</h5>
                </div>
                <div class="custom-card-body">
                    <div class="mb-3 text-center">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             id="current-image"
                             style="max-height: 200px;">
                        <p class="text-muted mt-2 mb-0 small">Current image</p>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Replace Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">Max 2MB. Leave empty to keep current image.</small>
                    </div>

                    <div id="image-preview" class="text-center d-none">
                        <p class="text-muted mb-2 small">New image preview:</p>
                        <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-tag me-2"></i>Pricing</h5>
                </div>
                <div class="custom-card-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="original_price" class="form-label">Original Price ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror"
                                   id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}">
                        </div>
                        @error('original_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">For discount display</small>
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-folder me-2"></i>Category</h5>
                </div>
                <div class="custom-card-body">
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Inventory -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-box-seam me-2"></i>Inventory</h5>
                </div>
                <div class="custom-card-body">
                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="rating" class="form-label">Rating (0-5)</label>
                            <input type="number" min="0" max="5" class="form-control @error('rating') is-invalid @enderror"
                                   id="rating" name="rating" value="{{ old('rating', $product->rating) }}">
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="review_count" class="form-label">Reviews</label>
                            <input type="number" class="form-control @error('review_count') is-invalid @enderror"
                                   id="review_count" name="review_count" value="{{ old('review_count', $product->review_count) }}">
                            @error('review_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h5><i class="bi bi-gear me-2"></i>Settings</h5>
                </div>
                <div class="custom-card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>Featured Product</strong>
                            <small class="d-block text-muted">Show in featured section</small>
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                            <small class="text-muted d-block">Status</small>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Stock Status</small>
                            <span class="badge {{ $product->in_stock ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Created</small>
                            <small class="text-dark">{{ $product->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($product->updated_at != $product->created_at)
                        <div class="text-end">
                            <small class="text-muted d-block">Updated</small>
                            <small class="text-dark">{{ $product->updated_at->format('M d, Y') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="custom-card">
                <div class="custom-card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-2"></i>Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Add specification field
    document.getElementById('add-spec').addEventListener('click', function() {
        const container = document.getElementById('specs-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <span class="input-group-text"><i class="bi bi-check2"></i></span>
            <input type="text" class="form-control" name="specs[]" placeholder="e.g., Intel Core i5 Processor">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Add color field
    document.getElementById('add-color').addEventListener('click', function() {
        const container = document.getElementById('colors-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <span class="input-group-text"><i class="bi bi-palette"></i></span>
            <input type="text" class="form-control" name="colors[]" placeholder="e.g., Black">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Add storage option field
    document.getElementById('add-storage').addEventListener('click', function() {
        const container = document.getElementById('storage-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <span class="input-group-text"><i class="bi bi-hdd"></i></span>
            <input type="text" class="form-control" name="storage_options[]" placeholder="e.g., 256GB">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
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