@extends('layoutUser')
@section('content')
<div class="w-full max-w-6xl lg:max-w-full mx-auto p-4 md:p-6 mt-14 lg:pb-14">
    <h1 class="text-2xl font-bold mb-6 text-center">Order History</h1>
    @if ($orders->count() > 0)
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-white rounded-lg shadow-md p-4 md:p-6 w-full" data-aos="fade-up" data-aos-duration="600">
                    <h4 class="font-bold">Order ID: {{ $order->order_code }}</h4>
                    @php
                        $orderItems = $order->getOrderItemsWithDetails();
                        $total = 0;
                    @endphp
                    
                    @foreach ($orderItems as $item)
                    <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 mt-4 border-b pb-4"> 
                        <div class="w-full md:w-1/4 lg:w-[12%] flex justify-center md:justify-start">
                            @if($item['product'] && $item['product']->product_img[0])
                                <img src="{{ asset('storage/'.$item['product']->product_img[0]) }}" 
                                    class="w-24 h-24 md:w-32 md:h-32 lg:w-44 lg:h-auto object-cover rounded-md" 
                                    alt="{{ $item['product']->product_name }}"
                                    loading="lazy">
                            @else
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 rounded-md flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                        </div>

                        {{-- Informasi Produk --}}
                        <div class="w-full md:w-2/4 text-center md:text-left">
                            <h3 class="text-lg font-bold">{{ $item['product']->product_name ?? 'Produk tidak tersedia' }}</h3>
                            <p class="text-gray-500 text-sm md:text-base">Harga: Rp {{ number_format($item['product']->discounted_price ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm md:text-base">
                                Size: {{ $item['size_stock']->size ?? 'N/A' }} | Qty: {{ $item['qty'] }}
                            </p>
                            <p class="font-bold text-sm md:text-lg">
                                Subtotal: Rp {{ number_format(($item['product']->discounted_price ?? 0) * $item['qty'], 0, ',', '.') }}
                            </p>
                            @php
                                $total += ($item['product']->discounted_price ?? 0) * $item['qty'];
                            @endphp
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

                            $statusShipped = match ($order->shipping_status ?? 'not_shipped') {
                                'shipped' => 'text-yellow-500',
                                'delivered' => 'text-blue-500',
                                'returned' => 'text-gray-500',
                                default => 'text-neutral-500',
                            };

                            $shippedList = match ($order->shipping_status ?? 'not_shipped') {
                                'not_shipped' => 'Belum Dikirim',
                                'shipped' => 'Sedang Dikirim',
                                'delivered' => 'Pesanan Dikirim',
                                'returned' => 'Retuned Barang',
                                default => 'Belum Dikirim',
                            };
                        @endphp
                        <div class="w-full md:w-1/4">
                            <div class="mb-2 text-center">
                                <p class="text-gray-500 text-sm">Status:</p>
                                <p class="{{ $statusColor }} font-bold text-lg">{{ $order->status }}</p>
                            </div>
                        </div>
                        <div class="w-full md:w-1/4 text-center">
                            <p class="text-gray-500 text-sm">Pengiriman:</p>
                            <p class="{{ $statusShipped }} font-bold text-lg">{{ $shippedList }}</p>
                        </div>
                    </div>
                    @endforeach

                    {{-- Total Order dan Tombol Aksi --}}
                    <div class="mt-4 flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <p class="font-bold text-lg">Total Order: Rp {{ number_format($order->total_price ?? $total, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div class="flex flex-col md:flex-row items-center gap-2">
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
                            @elseif($order->status === 'completed' && isset($order->review))
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
                </div>
            @endforeach
        </div>
    @else
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="card-title justify-center text-2xl mb-4">Tidak ada pesanan</h2>
            <p class="mb-4">Silahkan membuat pesanan terlebih dahulu</p>
            <a href="{{ route('cartPage') }}" class="btn bg-gradient-to-r from-red-600 to-red-800 text-white hover:from-red-700 hover:to-red-900 w-full">Buat Pesanan</a>
        </div>
    </div>
    @endif
</div>
@endsection