<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SizeStockProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\AuthMiddleware;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Province;
use App\Models\Review;
use App\Models\Voucher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Barryvdh\DomPDF\Facade\Pdf;


class MasterController extends Controller
{

  // register 
  public function registerPage()
  {
    return view('pages.register');
  }

  public function registerProcess(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ], [
      'password.confirmed' => 'Confirm password does not match',
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'user',
    ]);

    $login = [
      'name' => $request->name,
      'password' => $request->password,
    ];

    if (Auth::attempt($login)) {
      return redirect()->route('formUpdateDataUser')->with('success', 'Acount Berhasil Dibuat Lengkapi Data Anda.');
    } else {
      return redirect()->route('registerPage')->with('failed', 'Incorrect Username, Email or Password');
    }
  }

  // login 
  public function loginPage()
  {
    return view('pages.login');
  }

  public function loginProcess(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) { // Gunakan remember token
      $user = Auth::user();
      if ($user->role !== 'user') {
        Auth::logout();
        return redirect()->route('loginPage')->withErrors(['email' => 'Unauthorized access.']);
      }
      return redirect()->route('home');
    }
    return back()->withErrors(['email' => 'Invalid credentials.']);
  }

  // logout 
  public function logout()
  {
    Auth::logout();
    return redirect()->route('loginPage')->with('success', 'You have Successfully Logout');
  }

  // updateDataUser 
  public function formUpdateDataUser()
  {
    $user = Auth::user()->id;
    if ($user) {
      return view('pages.formInputDataUser');
    }
    return redirect()->route('loginPage');
  }

  public function updateDataUserProcess(Request $request)
  {
    $request->validate([
      'no_hp' => 'required|string|max:15',
      'addres' => 'required|string',
    ]);

    $user = Auth::user();
    $user->update([
      'no_hp' => $request->no_hp,
      'addres' => $request->addres,
    ]);

    return redirect()->route('home')->with('success', 'Data Berhasil Disimpan!');
  }

  // homePage 
  public function homePage(Request $request)
  {
    $products = $this->getProduct();
    $brands = $this->getBrand();
    $categories = $this->getCategory();
    $review = $this->getReview();

    return view('pages.home', compact('products', 'review', 'categories', 'brands'));
  }

  private function getProduct()
  {
    return Product::orderBy('created_at', 'desc')->limit(4)->get();
  }

  private function getCategory()
  {
    return Category::all();
  }
  private function getBrand()
  {
    return Brand::all();
  }

  private function getReview()
  {
    return Review::orderBy('created_at', 'desc')->limit(3)->get();
  }

  // aboutPage 
  public function aboutPage()
  {
    return view('pages.about');
  }

  // contactPage 
  public function contactPage()
  {
    return view('pages.contact');
  }

  // profile 
  public function profilePage()
  {
    $userDetail = Auth::user();
    if ($userDetail) {
      return view('pages.profile', compact('userDetail'));
    }
  }

  public function updateProfilePage()
  {
    $user = Auth::user();
    if ($user) {
      return view('pages.updateProfile', compact('user'));
    }
    return redirect()->route('loginPage');
  }

  public function updateProfile(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'no_hp' => 'required|numeric',
      'addres' => 'required|string',
      'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
      'user_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ], [
      'no_hp.numeric' => 'No Telp harus berupa angka',
    ]);

    $userData = [
      'name' => $request->name,
      'no_hp' => $request->no_hp,
      'addres' => $request->addres,
      'email' => $request->email,
    ];

    // delete foto lama 
    if ($request->hasFile('user_img')) {
      if ($user->user_img) {
        Storage::disk('public')->delete('profile/' . $user->user_img);
      }

      // hash & Upload foto baru
      $fileName = time() . '.' . $request->file('user_img')->extension();
      $request->file('user_img')->storeAs('profile', $fileName, 'public');

      $userData['user_img'] = $fileName;
    }

    $user->update($userData);

    return redirect()->route('profilePage')->with('success', 'Profile berhasil diupdate.');
  }

  // productPage 
  public function productPage(Request $request)
  {
    $search = $request->input('search');
    $category_id = $request->input('category');
    $brand_id = $request->input('brand');
    $sortOrder = $request->query('sortOrder', 'asc');
    // dd($request->all());

    if (!in_array($sortOrder, ['asc', 'desc'])) {
      $sortOrder = 'asc';
    }

    $query = Product::with(['category', 'brand']);

    // search product by name
    if ($search) {
      $query->where('product_name', 'like', "%$search%");
    }

    // Filter category
    if ($category_id) {
      $query->where('category_id', $category_id);
    }

    // Filter brand
    if ($brand_id) {
      $query->where('brand_id', $brand_id);
    }

    $query->orderBy('product_name', $sortOrder);
    $products = $query->paginate(8);

    // Ambil semua kategori dan brand untuk dropdown filter
    $categories = Category::all();
    $brands = Brand::all();

    return view('pages.product', compact('products', 'categories', 'brands'));
  }

  public function detailProduct($id): View
  {
    $productDetail = Product::with('sizeStock')->findOrFail($id);
    return view('pages.detailProduct', compact('productDetail'));
  }

  // cart 
  public function cartPage()
  {
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

  public function addToCart(Request $request)
  {
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

  public function deleteItemCart($id)
  {
    Cart::destroy($id);
    return redirect()->route('cartPage');
  }

  // formCheckout 
  public function formCheckout(Request $request)
  {
    $user = auth::user();
    $selectedCartIds = json_decode($request->input('selected_cart_ids', '[]'), true);
    // dd($selectedCartIds);

    // Ambil hanya item yang dichecklist
    $cartItems = Cart::where('user_id', $user->id)
      ->whereIn('id', $selectedCartIds)
      ->get();
    // Ambil qty dari localStorage
    $cartQuantities = json_decode($request->input('cart_quantities', '{}'), true);

    // Update total berdasarkan qty dari localStorage
    $total = 0;
    foreach ($cartItems as $item) {
      $qty = $cartQuantities[$item->id] ?? 1;
      $total += $item->product->discounted_price ? $item->product->discounted_price * $qty : $item->product->price * $qty;
    }
    $payments = Payment::all();
    $provinces = Province::all();

    return view('pages.formChekout', compact('user', 'payments', 'cartItems', 'cartQuantities', 'total', 'provinces'));
  }

  public function getCities($province_id)
  {
    $cities = City::where('province_id', $province_id)->get(['id', 'city_name', 'shipping_price']);
    return response()->json($cities);
  }

  public function checkVoucher(Request $request)
  {
    $voucherName = $request->input('voucher_name');
    $voucher = Voucher::where('voucher_name', $voucherName)->first();

    if ($voucher) {
      return response()->json([
        'success' => true,
        'voucher' => $voucher,
        'message' => 'Voucher berhasil digunakan'
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => 'Voucher tidak ditemukan'
    ]);
  }

  // payment 
  public function checkout(Request $request)
  {
    $user = Auth::user();
    Log::info('Checkout attempt by user', ['user_id' => $user->id, 'request_data' => $request->all()]);

    DB::beginTransaction();
    try {
      $selectedCartIds = array_map('intval', array_keys($request->input('selectedCartIds', [])));

      if (empty($selectedCartIds)) {
        return response()->json(['error' => 'No cart items selected'], 400);
      }

      $cartQuantities = array_map('intval', $request->input('cartQuantities', []));

      $cartItems = Cart::where('user_id', $user->id)
        ->whereIn('id', $selectedCartIds)
        ->with(['product', 'sizeStock'])
        ->get();

      if ($cartItems->isEmpty()) {
        return response()->json(['error' => 'No valid cart items found'], 404);
      }

      $total = 0;
      $itemDetails = [];
      $productData = []; 

      $shippingCost = 0;
      if ($request->city_id) {
        $city = City::find($request->city_id);
        $shippingCost = $city ? $city->shipping_price : 0;
      }

      $voucherDiscount = 0;
      $voucherId = null;
      $voucherName = $request->voucher_name;
      if ($voucherName) {
        $voucher = Voucher::where('voucher_name', $voucherName)->first();
        if ($voucher) {
          $voucherId = $voucher->id;
          $voucherDiscount = $voucher->discount_voucher;
        }
      }

      foreach ($cartItems as $item) {
        $sizeStock = SizeStockProduct::where('product_id', $item->product->id)
          ->where('size', optional($item->sizeStock)->size)
          ->firstOrFail();

        $qty = $cartQuantities[$item->id] ?? 1;
        $basePrice = $item->product->discounted_price * $qty;
        $total += $basePrice;

        $itemDetails[] = [
          'id' => $item->product->id,
          'name' => $item->product->product_name ?? 'Unknown Product',
          'price' => $item->product->discounted_price, 
          'quantity' => $qty
        ];

        $productData[] = [
          'product_id' => $item->product->id,
          'qty' => $qty,
          'size_stock_product_id' => $sizeStock->id
        ];
      }

      if ($shippingCost > 0) {
        $itemDetails[] = [
          'id' => 'SHIPPING',
          'name' => 'Shipping Cost',
          'price' => $shippingCost,
          'quantity' => 1
        ];
        $total += $shippingCost;
      }

      if ($voucherDiscount > 0) {
        $itemDetails[] = [
          'id' => 'VOUCHER',
          'name' => 'Voucher Discount',
          'price' => -$voucherDiscount, 
          'quantity' => 1
        ];
        $total -= $voucherDiscount;
      }

      $total = max(0, $total);

      // **Buat Order Code**
      $order_code = 'ORDER-' . time();

      // transaksi Midtrans**
      $transaction = [
        'transaction_details' => [
          'order_id' => $order_code,
          'gross_amount' => $total
        ],
        'item_details' => $itemDetails,
        'customer_details' => [
          'first_name' => $user->name,
          'email' => $user->email,
          'billing_address' => [
            'address' => $request->address
          ]
        ]
      ];

      Log::info('Transaction Data', $transaction);
      $snapToken = Snap::getSnapToken($transaction);

      $order = Order::create([
        'user_id' => $user->id,
        'product_id' => $item->product->id,
        'payment_id' => $request->payment_method,
        'payment_status' => 'pending',
        'payment_token' => $snapToken,
        'city_id' => $request->city_id,
        'size_stock_product_id' => $sizeStock->id,
        'voucher_id' => $voucherId,
        'order_code' => $order_code,
        'addres' => $request->addres,
        'qty' => array_sum($cartQuantities),
        'total_amount' => $total,
        'status' => 'pending',
        'order_item' => json_encode($productData)
      ]);

      Cart::where('user_id', $user->id)
        ->whereIn('id', $selectedCartIds)
        ->delete();

      DB::commit();

      return response()->json([
        'snap_token' => $snapToken,
        'order_id' => $order->id
      ]);

    } catch (\Exception $e) {
      Log::error('Checkout error: ' . $e->getMessage(), [
        'exception' => $e,
        'request' => $request->all()
      ]);
      return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
  }

  // public function checkPaymentStatus($order_code)
  // {
  //   try {
  //     $status = \Midtrans\Transaction::status($order_code);
  //     $transactionStatus = $status->transaction_status; // Status dari Midtrans

  //     $order = Order::where('order_code', $order_code)->first();
  //     if (!$order) {
  //       return response()->json(['error' => 'Order not found'], 404);
  //     }

  //     // Cek status transaksi dari Midtrans dan update `payment_status`
  //     if (in_array($transactionStatus, ['settlement', 'capture'])) {
  //       $order->update([
  //         'payment_status' => 'paid',
  //         'status' => 'processed' // Jika ingin mengubah status pesanan juga
  //       ]);
  //     } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
  //       $order->update([
  //         'payment_status' => 'failed',
  //         'status' => 'canceled'
  //       ]);
  //     }

  //     return response()->json([
  //       'message' => 'Order status updated',
  //       'payment_status' => $order->payment_status
  //     ]);
  //   } catch (\Exception $e) {
  //     Log::error("Check Payment Error: " . $e->getMessage());
  //     return response()->json(['error' => 'Server error'], 500);
  //   }
  // }

  public function updatePayment(Request $request)
  {
    $order = Order::where('order_code', $request->order_id)->first();

    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
    }

    $order->payment_status = $request->status;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Status pembayaran diperbarui']);
  } 

  // orderPage
  public function orderPage()
  {
    $users = Auth::user();
    if ($users) {
      $orderItem = Order::where('user_id', $users->id)->with(['user', 'product', 'payment', 'city', 'sizeStock', 'voucher'])->latest()->get();

      foreach ($orderItem as $order) {
        $order->decoded_items = json_decode($order->order_item, true);
      }
      return view('pages.order', compact('orderItem'));
    }
    return redirect()->route('/');
  }

  public function detailOrder($id): View
  {
    $orderDetail = Order::with(['user', 'product', 'payment', 'city', 'sizeStock', 'voucher'])->findOrFail($id);
    return view('pages.detailOrder', compact('orderDetail'));
  }

  public function generatePdf($id)
{
    // Ambil order beserta relasi yang dibutuhkan menggunakan eager loading
    $order = Order::with(['user', 'product', 'payment', 'city', 'sizeStock', 'voucher'])->findOrFail($id);

    // Decode order_item dan pastikan format JSON valid
    $decodedItems = json_decode($order->order_item, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Menangani kesalahan decode jika JSON tidak valid
        $decodedItems = [];
    }

    // Ambil produk yang relevan berdasarkan product_id
    $productIds = collect($decodedItems)->pluck('product_id')->unique();
    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    $sizeStockIds = collect($decodedItems)->pluck('size_stock_product_id')->unique();
    $sizeStock = SizeStockProduct::whereIn('id', $sizeStockIds)->get()->keyBy('id');

    // Menambahkan nama produk dan harga ke setiap item dalam decodedItems
    $decodedItems = collect($decodedItems)->map(function ($item) use ($products, $sizeStock) {
        $product = $products->get($item['product_id']);
        $size = $sizeStock->get($item['size_stock_product_id']);
        $item['product_name'] = $product ? $product->product_name : 'Produk tidak ditemukan';
        $item['price'] = $product ? $product->price : 0;
        $item['size'] = $size ? $size->size : '-';
        return $item;
    })->toArray();

    // Generate PDF menggunakan data yang telah diproses
    $pdf = Pdf::loadView('pages.invoice_view', [
        'order' => $order,
        'decodedItems' => $decodedItems
    ]);

    return $pdf->download('invoice-' . $order->id . '.pdf');
}





  public function updateStatusCompleted($id)
  {
    $order = Order::findOrFail($id);

    if ($order->status === 'pending') {
      $order->status = 'request cancel';
      $order->save();

      return back()->with('success', 'Status berhasil diperbarui!');
    } elseif ($order->status === 'menunggu confirm user') {
      $order->status = 'completed';
      $order->save();

      return back()->with('success', 'Status berhasil diperbarui!');
    }
    return back()->with('error', 'Status tidak dapat diperbarui.');
  }

  public function createOrder(Request $request)
  {
    $request->validate([
      'payment_method' => 'required',
      'cartQuantities' => 'required|array',
      'city_id' => 'nullable|exists:cities,id',
      'address' => 'nullable|string',
      'voucher_id' => 'nullable|exists:vouchers,id',
      'payment_proof' => 'nullable|file'
    ]);

    $userId = auth()->user()->id;
    Log::info('User ID:', ['user_id' => $userId]);

    // 🔥 Ambil cartQuantities dari request
    $cartQuantities = $request->input('cartQuantities', []);

    // 🔥 Perbaikan format selectedCartIds
    $selectedCartIds = $request->input('selectedCartIds', []);
    if (is_array($selectedCartIds)) {
      $selectedCartIds = array_keys($selectedCartIds);
    } elseif (is_string($selectedCartIds)) {
      $selectedCartIds = explode(',', $selectedCartIds);
    }
    $selectedCartIds = array_map('intval', is_array($selectedCartIds) ? $selectedCartIds : []);

    Log::info('Selected Cart IDs (Fixed):', ['ids' => $selectedCartIds]);

    // 🔥 Pastikan whereIn tidak error meskipun hanya ada 1 ID
    $whereInData = !empty($selectedCartIds) ? $selectedCartIds : [0];

    // 🔥 Debugging Query SQL
    $query = Cart::where('user_id', $userId)
      ->whereIn('id', $whereInData)
      ->toSql();

    $bindings = Cart::where('user_id', $userId)
      ->whereIn('id', $whereInData)
      ->getBindings();

    Log::info('Query SQL (Fixed):', ['query' => $query, 'bindings' => $bindings]);

    // Ambil item yang dichecklist
    $cartItems = Cart::where('user_id', $userId)
      ->whereIn('id', $whereInData)
      ->with(['product', 'sizeStock'])
      ->get();

    Log::info('Jumlah cartItems (Fixed): ' . count($cartItems));

    DB::beginTransaction();
    try {
      $order = null;
      Log::info('Jumlah cartItems: ' . count($cartItems));

      foreach ($cartItems as $item) {
        $sizeStock = SizeStockProduct::where('product_id', $item->product->id)
          ->where('size', optional($item->sizeStock)->size)
          ->firstOrFail();

        // 🔥 Perbaikan: Ambil jumlah kuantitas dengan aman
        $quantity = isset($cartQuantities[$item->id]) ? intval($cartQuantities[$item->id]) : 0;

        if ($sizeStock->stock < $quantity) {
          return back()->with('error', 'Stock produk tidak cukup.');
        }

        $basePrice = $item->product->discounted_price * $quantity;

        // Menghitung ongkir
        $shippingCost = 0;
        if ($request->city_id) {
          $city = City::find($request->city_id);
          $shippingCost = $city ? $city->shipping_price : 0;
        }

        // 🔥 Perbaikan: Ambil voucher_id langsung dari request
        $voucherDiscount = 0;
        $voucherId = $request->voucher_id;
        if ($voucherId) {
          $voucher = Voucher::find($voucherId);
          if ($voucher) {
            $voucherDiscount = $voucher->discount_voucher;
          }
        }

        // Menghitung total keseluruhan
        $totalAmount = max(0, $basePrice + $shippingCost - $voucherDiscount);

        // Handle file upload
        $buktiPembayaran = null;
        if ($request->hasFile('payment_proof')) {
          $buktiPembayaran = $request->file('payment_proof')->store('payment_proof', 'public');
        }

        DB::enableQueryLog();
        $order = Order::create([
          'user_id' => $userId,
          'product_id' => $item->product->id,
          'payment_id' => $request->payment_method,
          'city_id' => $request->city_id ?? null,
          'size_stock_product_id' => $sizeStock->id,
          'voucher_id' => $voucherId,
          'addres' => $request->addres, // 🔥 FIX: Menggunakan `address` yang benar
          'payment_proof' => $buktiPembayaran,
          'qty' => $quantity,
          'total_amount' => $totalAmount,
          'status' => 'pending'
        ]);

        Log::info('Order detail: ' . json_encode($order));
        Log::info('Query yang dijalankan:', DB::getQueryLog());
      }

      if ($order) {
        Log::info('Order berhasil dibuat: ' . json_encode($order));
      } else {
        Log::info('Query yang dijalankan:', DB::getQueryLog());
      }

      // Hapus item dari keranjang setelah dibuat order
      Cart::where('user_id', $userId)
        ->whereIn('id', $whereInData)
        ->delete();

      DB::commit();
      return redirect()->route('orderPage')->with('success', 'Pesanan berhasil dibuat!');
    } catch (\Exception $e) {
      Log::error('Error saat membuat pesanan: ' . $e->getMessage());
      DB::rollBack();

      // 🔥 Perbaikan: Kembalikan stok produk jika terjadi error
      foreach ($cartItems as $item) {
        if (isset($cartQuantities[$item->id])) {
          $sizeStock = SizeStockProduct::where('product_id', $item->product->id)
            ->where('size', optional($item->sizeStock)->size)
            ->first();

          if ($sizeStock) {
            $sizeStock->increment('stock', $cartQuantities[$item->id]);
          }
        }
      }
      return redirect()->route('cartPage')->with('error', 'Pesanan gagal dibuat!');
    }
  }



  // review 
  public function reviewPage(Order $order)
  {
    if ($order->review) {
      return redirect()->back()->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
    }
    return view('pages.review', compact('order'));
  }

  public function addReview(Request $request, Order $order)
  {
    $request->validate([
      'rating' => 'required|integer|min:1|max:5',
      'review' => 'required|string|max:500',
    ]);

    if ($order->review) {
      return redirect()->back()->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
    }

    Review::create([
      'user_id' => Auth::id(),
      'order_id' => $order->id,
      'rating' => $request->rating,
      'review' => $request->review,
    ]);

    return redirect()->route('orderPage')->with('success', 'Review berhasil ditambahkan!');
  }
}
