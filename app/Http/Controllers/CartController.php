<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
  // cart 
  public function cartPage() {
    $user = Auth::user();
    if ($user) {
      $cartItems = Cart::with(['sizeStock', 'product'])
        ->where('user_id', $user->id)
        ->get();

      foreach ($cartItems as $item) {
        if ($item->sizeStock->stock <= 0) {
          $item->delete();
        }
      }

      // Ambil ulang item setelah penghapusan
      $cartItems = Cart::with(['sizeStock', 'product'])
        ->where('user_id', $user->id)
        ->get();

      return view('pages.cart', compact('cartItems'));
    }

    return redirect()->route('home');
  }

  public function addToCart(Request $request) {
    $userId = Auth::id();
    $productId = $request->input('product_id');
    $sizeStockProductId = $request->input('size_stock_product_id');

    if (empty($sizeStockProductId)) {
      return redirect()->back()->with('error', 'Silakan pilih ukuran sebelum menambahkan ke keranjang.');
    }

    $existingCart = Cart::where('user_id', $userId)
      ->where('product_id', $productId)
      ->where('size_stock_product_id', $sizeStockProductId)
      ->first();

    if ($existingCart) {
      return redirect()->back()->with('error', 'Produk sudah ada di keranjang Anda.');
    }

    $product = Product::findOrFail($productId);

    Cart::create([
      'user_id' => $userId,
      'product_id' => $productId,
      'size_stock_product_id' => $sizeStockProductId,
      'qty' => $request->input('qty', 1),
      'total_price' => $product->discounted_price * $request->input('qty', 1),
    ]);

    return redirect()->route('cartPage')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
  }

  public function deleteItemCart($id) {
    Cart::destroy($id);
    return redirect()->route('cartPage');
  }
}
