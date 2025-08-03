<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/', function() { return view('home'); });
Route::get('/products', function() { return view('products.index'); });
Route::get('/product/{id}', function($id) { return view('products.show'); });
Route::get('/cart', function() { return view('cart.index'); });
Route::get('/checkout', function() { return view('checkout'); });
Route::post('/cart/add', function (Illuminate\Http\Request $request) {
    $cart = session()->get('cart', []);

    $id = $request->id;
    if (isset($cart[$id])) {
        $cart[$id]['quantity'] += $request->quantity;
    } else {
        $cart[$id] = [
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image,
            'quantity' => $request->quantity,
        ];
    }

    session()->put('cart', $cart);
    return redirect('/cart')->with('success', 'Product added to cart!');
});

Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart'));
});

require __DIR__.'/auth.php';
