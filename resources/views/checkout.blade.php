@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
    <h2>Checkout</h2>
    <form>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control">
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" class="form-control">
        </div>
        <button class="btn btn-success">Place Order</button>
    </form>
@endsection
