@extends('layoutUser')

@section('content')
<body class="bg-base-200 ">
    <div class="w-full mx-auto bg-base-100 p-6 rounded-lg shadow-lg mt-12">
        <div class="flex justify-between items-center mb-6">
            <div class="steps w-full">
                @php
                    // Daftar status
                    $statuses = ['pending', 'pesanan dibuat', 'pesanan diantar', 'pesanan sampai', 'menunggu confirm user', 'completed'];
                    
                    // Mencari indeks status saat ini 
                    $currentStatusIndex = array_search($orderDetail->status, $statuses);
                @endphp
        
                @foreach ($statuses as $index => $status)
                    <div class="step {{ $index <= $currentStatusIndex ? 'step-primary' : 'step-neutral' }}">
                        {{ ucfirst($status) }}
                    </div>
                @endforeach
            </div>
        </div>
        @php
            $status = $orderDetail->status;
            // warna class alert setiap status 
            $alertClass = match ($status) {
                'pending' => 'alert-error',
                'pesanan dibuat' => 'alert-warning', 
                'pesanan diantar' => 'alert-info', 
                'pesanan sampai' => 'alert-primary', 
                'menunggu confirm user' => 'alert-secondary', 
                'completed' => 'alert-success', 
                default => 'alert-neutral',
            };
        @endphp

        <div class="alert {{ $alertClass }} shadow-lg mb-6">
            <div>
                <i class="fas fa-check-circle text-lg"></i>
                <span>Your Product {{$orderDetail->status}}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Order details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-semibold">{{$orderDetail->user->name}}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">No Telp</p>
                        <p class="font-semibold">{{$orderDetail->user->no_hp}}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Pembelian</p>
                        <p class="font-semibold">{{$orderDetail->created_at}}00</p>
                    </div>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold mb-2">Product Detail</h4>
                    <div class="flex items-center space-x-4">
                            <img src="{{ asset('storage/'.$orderDetail->product->product_img[0]) }}" class="w-full md:w-32 h-32 object-cover rounded-md" alt="{{ $orderDetail->product->product_name }}">
                        <div>
                            <p class="font-semibold">{{$orderDetail->product->product_name}}</p>
                            <p class="text-gray-500">Size {{$orderDetail->sizeStock->size}}</p>
                            <p class="text-gray-500">Rp {{ number_format($orderDetail->product->discounted_price, 2) }}</p>
                            <p class="text-gray-500">{{$orderDetail->qty}} Barang</p>
                            @php
                                $totalProduct = $orderDetail->product->discounted_price * $orderDetail->qty;
                            @endphp
                            <p>Total Harga Product: Rp {{ number_format($totalProduct, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                
                @if (!empty($orderDetail->city?->city_name))
                <div class="bg-gray-100 p-4 rounded-lg mt-5">
                    <h4 class="text-lg font-semibold mb-2">Ongkir</h4>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">addres:</p>
                            <p class="text-gray-500">{{$orderDetail->addres}}</p>
                            <p class="text-gray-500">Kota {{$orderDetail->city->city_name}}</p>
                            <p class="text-gray-500">Provinsi {{$orderDetail->city->province->province_name}}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold">Rp {{ number_format($orderDetail->city->shipping_price,) }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (!empty($orderDetail->voucher?->voucher_name))
                    <div class="bg-gray-100 p-4 rounded-lg mt-5">
                        <h4 class="text-lg font-semibold mb-2">Voucher</h4>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-pink-500 font-semibold">{{ $orderDetail->voucher->voucher_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-500">
                                    -<span class="ms-1">
                                        Rp {{ number_format((float) ($orderDetail->voucher->discount_voucher ?? 0), 0, ',', '.') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="bg-gray-100 p-4 rounded-lg mt-5">
                    <h4 class="text-lg font-semibold mb-4">Total</h4>
                    <div class="grid gap-2 text-sm md:text-base">
                        <div class="flex justify-between">
                            <p class="text-gray-700">Total Harga Barang</p>
                            <p class="font-semibold">Rp {{ number_format($totalProduct, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-gray-700">Ongkir</p>
                            @if (!empty($orderDetail->city?->shipping_price))
                                <p class="font-semibold">Rp {{ number_format($orderDetail->city->shipping_price, 0, ',', '.') }}</p>
                                @else 
                                    -
                            @endif
                        </div>
                        @if (!empty($orderDetail->voucher?->discount_voucher))
                            <div class="flex justify-between">
                                <p class="text-gray-700">Voucher Discount</p>
                                <p class="font-semibold text-red-500">- Rp {{ number_format((float) ($orderDetail->voucher->discount_voucher ?? 0), 0, ',', '.') }}</p>
                            </div>
                        @endif
                        <div class="border-t border-gray-300 mt-2 pt-2 flex justify-between">
                            <h1 class="font-extrabold text-lg md:text-xl">Subtotal</h1>
                            <h1 class="font-extrabold text-lg md:text-xl text-black-600">Rp {{ number_format($orderDetail->total_amount, 0, ',', '.') }}</h1>
                        </div>
                    </div>
                </div>
                @if ($orderDetail->status === 'menunggu confirm user')
                    <form action="{{ route('updateStatusCompleted', $orderDetail->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="mt-10 px-4 py-3 w-full bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Selesaikan Pesanan
                        </button>
                    </form>
                @endif

            </div>

            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-xl font-extrabold mb-4 text-center">Payment</h3>
                <div class="mb-4">
                    <p class="text-gray-500">Payment Method</p>
                    <p class="font-semibold">{{$orderDetail->payment->payment_name}}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-500">Bukti Transaksi</p>
                    @if($orderDetail->payment->payment_name === 'Ambil Ditempat' && empty($orderDetail->payment_proof))
                        <p class="text-black font-bold">Bayar di Tempat</p>
                    @else
                        <div class="w-full h-auto">
                        <img src="{{ asset('storage/'.$orderDetail->payment_proof) }}" class="w-full object-cover" alt="bukti pembayaran">
                        </div>
                    @endif
                </div>
                    
                <button onclick="window.print()" class="btn btn-success w-full">Download Invoice</button>
            </div>
        </div>
    </div>
</body>

@endsection
