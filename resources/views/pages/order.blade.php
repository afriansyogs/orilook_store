@extends('layoutUser')
@section('content')
<div class="w-full max-w-6xl lg:max-w-full mx-auto p-4 md:p-6 mt-14 lg:pb-14">
    <h1 class="text-2xl font-bold mb-6 text-center">Order History</h1>
    @if ($orderItem->count() > 0)
        <div class="space-y-4">
            {{-- @foreach ($orderItem as $order)
                <div class="border rounded p-4 mb-4">
                    <h4 class="font-bold">Order ID: {{ $order->id }}</h4>

                    @foreach ($order->decoded_items as $item)
                        <div class="pl-4 mt-2 border-l-2 border-gray-300">
                            <p>Product ID: {{ $item['product_id'] }}</p>
                            <p>Quantity: {{ $item['qty'] }}</p>
                            <p>Size Stock Product ID: {{ $item['size_stock_product_id'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach --}}

            @foreach ($orderItem as $order)
                <div class="bg-white p-4 md:p-6 rounded-lg shadow-md w-full flex flex-col md:flex-row md:items-center gap-4 md:gap-6" data-aos="fade-up" data-aos-duration="600"> 
                    {{-- Bagian Gambar --}}
                    <div class="w-full md:w-1/4 flex justify-center md:justify-start">
                        <img src="{{ asset('storage/'.$order->product->product_img[0]) }}" 
                            class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-md" 
                            alt="{{ $order->product->product_name }}">
                    </div>

                    {{-- Informasi Produk --}}
                    <div class="w-full md:w-2/4 text-center md:text-left">
                        <h3 class="text-lg font-bold">{{ $order->product->product_name }}</h3>
                        <p class="text-gray-500 text-sm md:text-base">Harga: Rp {{ number_format($order->product->discounted_price, 0, ',', '.') }}</p>
                        <p class="text-sm md:text-base">Size: {{ $order->sizeStock->size ?? 'N/A' }} | Qty: {{ $order->qty }}</p>
                        <p class="font-bold text-sm md:text-lg">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>

                    {{-- Status Pesanan --}}
                    @php
                        $statusColor = match ($order->status) {
                            'cancel' => 'text-gray-400',
                            'request cancel' => 'text-gray-600',
                            'pending' => 'text-red-500',
                            'pesanan_dibuat' => 'text-orange-500',
                            'completed' => 'text-green-500',
                            default => 'text-neutral-500',
                        };

                        $statusShipped = match ($order->shipping_status) {
                            'shipped' => 'text-yellow-500',
                            'delivered' => 'text-blue-500',
                            'returned' => 'text-gray-500',
                            default => 'text-neutral-500',
                        };

                        $shippedList = match ($order->shipping_status) {
                            'not_shipped' => 'Belum Dikirim',
                            'delivered' => 'Pesanan Dikirim',
                            'returned' => 'Retuned Barang',
                            default => 'text-neutral-500',
                        };

                        $statusList = ['pending','pesanan_dibuat','completed','request cancel','canceled'];
                    @endphp
                    <div class="w-full md:w-1/2 text-center">
                        <p class="text-gray-500 text-sm">Status:</p>
                        <p class="{{ $statusColor }} font-bold text-lg">{{ $order->status }}</p>
                    </div>  
                    <div class="w-full md:w-1/2 text-center">
                        <p class="text-gray-500 text-sm">Pengiriman:</p>
                        <p class="{{ $statusShipped }} font-bold text-lg">{{ $shippedList }}</p>
                    </div>  
                    {{-- Tombol Aksi --}}
                    <div class="w-full flex flex-col md:flex-row items-center justify-center md:justify-end gap-2">
                        <a href="{{ route('detailOrder', $order->id) }}" 
                            class="w-full md:w-auto border border-black bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded text-center">
                            Show Detail
                        </a>
                        @if ($order->status === 'pending')
                            <form action="{{ route('updateStatusCompleted', $order->id) }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-800 transition">
                                    Cancel Pesanan
                                </button>
                            </form>
                        @elseif ($order->status === 'menunggu confirm user')
                            <a href="https://api.whatsapp.com/message/5NGXU3KSKFWEH1?autoload=1&app_absent=0" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-center">
                                Refund
                            </a>
                            <form action="{{ route('updateStatusCompleted', $order->id) }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    Selesaikan Pesanan
                                </button>
                            </form>
                        @elseif($order->status === 'completed' && !$order->review)
                            @if(!$order->review()->where('user_id', auth()->id())->exists())
                                <form action="{{ route('reviewPage', ['order' => $order->id]) }}" method="GET" class="w-full md:w-auto">
                                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                        Review
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="card-title justify-center text-2xl mb-4">Tidak ada pesanan</h2>
            <p class="mb-4">Silahkan membuat pesanan terlebih dahulu</p>
            <a href="{{ route('productPage') }}" class="btn btn-primary">Lanjutakn belanja</a>
        </div>
    </div>
    @endif
</div>
@endsection