@extends('layouts.app')
@section('title', 'Cart')

@section('content')
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Product</th><th>Price</th><th>Quantity</th><th>Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Product 1</td><td>$500</td><td>1</td><td>$500</td>
        </tr>
        </tbody>
    </table>
    <button class="btn btn-primary">Proceed to Checkout</button>
@endsection
