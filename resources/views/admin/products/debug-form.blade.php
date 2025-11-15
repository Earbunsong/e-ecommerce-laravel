@extends('layouts.admin')

@section('title', 'Debug Product Form')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Product Form Debug Information</h1>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Form Submission Data</h5>
    </div>
    <div class="card-body">
        @if(session('debug_data'))
            <h6>Data Received:</h6>
            <pre class="bg-light p-3">{{ print_r(session('debug_data'), true) }}</pre>
        @else
            <p>No data submitted yet.</p>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Validation Errors</h5>
    </div>
    <div class="card-body">
        @if(session('debug_errors'))
            @foreach(session('debug_errors') as $field => $errors)
                <div class="mb-2">
                    <strong>{{ $field }}:</strong>
                    <ul>
                        @foreach($errors as $error)
                            <li class="text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @else
            <p>No validation errors.</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Expected Fields</h5>
    </div>
    <div class="card-body">
        <ul>
            <li><strong>name:</strong> Required, string, max 255</li>
            <li><strong>price:</strong> Required, numeric, min 0</li>
            <li><strong>category_id:</strong> Required, must exist in categories table</li>
            <li><strong>brand:</strong> Required, string, max 100</li>
            <li><strong>sku:</strong> Required, string, max 50, must be unique</li>
            <li><strong>stock_quantity:</strong> Required, integer, min 0</li>
            <li><strong>image:</strong> Optional, must be image file (jpeg,png,jpg,gif), max 2MB</li>
            <li><strong>description:</strong> Optional, string</li>
            <li><strong>original_price:</strong> Optional, numeric, min 0</li>
            <li><strong>rating:</strong> Optional, numeric, 0-5</li>
            <li><strong>review_count:</strong> Optional, integer, min 0</li>
            <li><strong>specs:</strong> Optional, array</li>
            <li><strong>colors:</strong> Optional, array</li>
            <li><strong>storage_options:</strong> Optional, array</li>
            <li><strong>is_featured:</strong> Optional, boolean</li>
            <li><strong>is_active:</strong> Optional, boolean</li>
        </ul>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Back to Create Form</a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">View All Products</a>
</div>

@endsection
