@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Create New Product</h1>
    <p class="page-subtitle">Add a new product to your catalog</p>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h5><i class="bi bi-info-circle me-2"></i>Product Information</h5>
                    </div>
                    <div class="custom-card-body">
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specifications -->
                        <div class="mb-3">
                            <label class="form-label">Specifications</label>
                            <div id="specs-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="specs[]" placeholder="e.g., Intel Core i5 Processor">
                                    <button type="button" class="btn btn-outline-danger remove-spec" onclick="this.parentElement.remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-spec">
                                <i class="bi bi-plus-circle me-1"></i>Add Specification
                            </button>
                        </div>

                        <!-- Colors -->
                        <div class="mb-3">
                            <label class="form-label">Available Colors</label>
                            <div id="colors-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="colors[]" placeholder="e.g., Black">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-color">
                                <i class="bi bi-plus-circle me-1"></i>Add Color
                            </button>
                        </div>

                        <!-- Storage Options -->
                        <div class="mb-3">
                            <label class="form-label">Storage Options</label>
                            <div id="storage-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="storage_options[]" placeholder="e.g., 256GB">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-storage">
                                <i class="bi bi-plus-circle me-1"></i>Add Storage Option
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Pricing -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h5><i class="bi bi-currency-dollar me-2"></i>Pricing</h5>
                    </div>
                    <div class="custom-card-body">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="original_price" class="form-label">Original Price ($)</label>
                            <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror"
                                   id="original_price" name="original_price" value="{{ old('original_price') }}">
                            @error('original_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty if no discount</small>
                        </div>
                    </div>
                </div>

                <!-- Category & Brand -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h5><i class="bi bi-folder me-2"></i>Organization</h5>
                    </div>
                    <div class="custom-card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                   id="brand" name="brand" value="{{ old('brand') }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (0-5)</label>
                            <input type="number" min="0" max="5" class="form-control @error('rating') is-invalid @enderror"
                                   id="rating" name="rating" value="{{ old('rating', 0) }}">
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="review_count" class="form-label">Review Count</label>
                            <input type="number" class="form-control @error('review_count') is-invalid @enderror"
                                   id="review_count" name="review_count" value="{{ old('review_count', 0) }}">
                            @error('review_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h5><i class="bi bi-image me-2"></i>Product Image</h5>
                    </div>
                    <div class="custom-card-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">Max 2MB, JPEG/PNG/JPG/GIF (Optional)</small>
                        </div>

                        <div id="image-preview" class="mt-2 text-center"></div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h5><i class="bi bi-gear me-2"></i>Settings</h5>
                    </div>
                    <div class="custom-card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Featured Product</strong>
                                <small class="d-block text-muted">Show on homepage</small>
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active</strong>
                                <small class="d-block text-muted">Visible on website</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Debug form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');

        console.log('Form found:', form);
        console.log('Submit button found:', submitBtn);

        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered!');
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);

            // Check if all required fields are filled
            const requiredFields = form.querySelectorAll('[required]');
            let allFilled = true;
            requiredFields.forEach(field => {
                if (!field.value) {
                    console.log('Empty required field:', field.name, field.id);
                    allFilled = false;
                }
            });

            if (!allFilled) {
                console.log('Some required fields are empty - form will not submit');
            } else {
                console.log('All required fields filled - form should submit');
            }
        });

        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked!');
        });
    });

    // Add specification field
    document.getElementById('add-spec').addEventListener('click', function() {
        const container = document.getElementById('specs-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
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
            <input type="text" class="form-control" name="storage_options[]" placeholder="e.g., 256GB">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.maxHeight = '200px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
@endsection