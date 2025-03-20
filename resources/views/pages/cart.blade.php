<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    @include('link')
</head>
<body style="background-color: #F5F5F5;">    
@include('components.navbar')
<div class="container mx-auto px-4 pt-8 pb-28">
    <h1 class="text-2xl font-bold mb-8 mt-12">Shopping Cart</h1>

    @if ($cartItems->count() > 0)
        <div class="grid grid-cols-1 gap-6 space-y-10">
            <div id="cart-items">
                @foreach ($cartItems as $cartItem)
                    @php
                        $productPrice = $cartItem->product->discounted_price ?? $cartItem->product->price;
                        $stockValue = optional($cartItem->sizeStock)->stock ?? 0;
                    @endphp

                    <div class="card lg:card-side bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 p-4 rounded-lg mb-4 flex flex-col lg:flex-row items-center lg:items-start">
                        <input type="checkbox" class="cart-checkbox checkbox checkbox-lg mx-auto my-auto mr-5" data-id="{{ $cartItem->id }}" data-price="{{ $productPrice }}" name="selected_cart_ids[]" value="{{ $cartItem->id }}">
                        {{-- Bagian Gambar --}}
                        <figure class="w-32 h-32 md:w-40 md:h-40 lg:w-40 lg:h-40 flex-shrink-0">
                            <img src="{{ asset('storage/' . $cartItem->product->product_img[0]) }}"
                                alt="{{ $cartItem->product->product_name }}" class="object-cover w-full h-full rounded-lg" />
                        </figure>
                        {{-- Bagian Informasi --}}
                        <div class="card-body w-full flex flex-col">
                            <div class="flex flex-col lg:flex-row justify-between w-full">
                                <div class="space-y-2">
                                    <h2 class="card-title text-xl font-semibold">{{ $cartItem->product->product_name }}</h2>
                                    <p class="text-sm text-gray-600">Size: {{ optional($cartItem->sizeStock)->size ?? 'Tidak ada ukuran' }}</p>
                                    <p class="cart-item text-sm text-gray-600" data-id="{{ $cartItem->id }}" data-stock="{{ $stockValue }}">
                                        {{-- @dd($stockValue) --}}
                                    </p>
                                    {{-- Harga --}}
                                    <div class="flex items-center gap-2">
                                        @if ($cartItem->product->discounted_price)
                                            <span class="text-lg font-semibold text-primary">
                                                Rp <span class="price">{{ number_format($cartItem->product->discounted_price, 0, ',', '.') }}</span>
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                Rp {{ number_format($cartItem->product->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-lg font-semibold text-primary">
                                                Rp <span class="price">{{ number_format($cartItem->product->price, 0, ',', '.') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-lg font-semibold text-primary item-total-price" data-id="{{ $cartItem->id }}">
                                        Total: Rp <span class="total-price">{{ number_format($cartItem->product->discounted_price * 1, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                                {{-- Bagian Button --}}
                                <div class="flex flex-col lg:flex-row items-center lg:items-end gap-4 mt-4 lg:mt-0">
                                    <div class="flex items-center space-x-2">
                                        <button class="btn-qty px-4 py-2 border rounded-lg" data-id="{{ $cartItem->id }}" data-price="{{ $productPrice }}" data-stock="{{ $stockValue }}" data-action="decrease">-</button>
                                        <span class="quantity text-lg font-semibold" data-id="{{ $cartItem->id }}" data-qty="1">1</span>
                                        <button class="btn-qty px-4 py-2 border rounded-lg" data-id="{{ $cartItem->id }}" data-price="{{ $productPrice }}" data-stock="{{ $stockValue }}" data-action="increase">+</button>
                                    </div>
                                    {{-- Button Delete --}}
                                    <form action="{{ route('deleteItemCart', $cartItem->id) }}" method="POST" class="w-full lg:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-sm btn-outline w-full lg:w-auto pt-3 pb-5 flex justify-center items-center">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Fixed Total Section --}}
        <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 z-50">
            <div class="flex flex-col md:flex-row md:justify-between items-center">
                <h3 class="text-md md:text-xl font-semibold">{{ $cartItems->count() }} Barang Belum Di Checkout</h3>
                <span id="grand-total" class="text-2xl font-bold text-primary">Rp 0</span>
            </div>
            <form action="{{ route('formCheckout') }}" method="GET">
                <input type="hidden" name="selected_cart_ids" id="selectedCartIds">
                <input type="hidden" name="cart_quantities" id="cart-quantities">
                <button type="submit" class="btn bg-red-500 text-white hover:bg-red-600 w-full mt-4">Proceed to Checkout</button>
            </form>
        </div>
    @else
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body text-center">
                <h2 class="card-title justify-center text-2xl mb-4">Keranjang anda kosong</h2>
                <p class="mb-4">Tambahkan product ke keranjang anda.</p>
                <a href="{{ route('productPage') }}" class="btn btn-primary">Lanjutakn belanja</a>
            </div>
        </div>
    @endif
</div>
    <script src="assets/js/cart.js"></script>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: 'success',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: 'error',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</body>
</html>
