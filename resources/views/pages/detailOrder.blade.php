@extends('layoutUser')

@section('content')
<body class="bg-base-200 ">
    <div class="w-full mx-auto bg-base-100 p-6 rounded-lg shadow-lg mt-12">
        <div class="flex justify-between items-center mb-6 mt-8">
            <div class="steps w-full">
                @php
                    // Daftar status
                    $statuses = ['pending', 'pesanan dibuat', 'pesanan diantar', 'pesanan sampai', 'menunggu confirm user', 'completed'];
                    
                    // Mencari indeks status saat ini 
                    $currentStatusIndex = array_search($orderDetail->status, $statuses);
                @endphp
        
                @foreach ($statuses as $index => $status)
                    <div class="step {{ $index <= $currentStatusIndex ? 'step-success' : 'step-neutral' }}">
                        {{ ucfirst($status) }}
                    </div>
                @endforeach
            </div>
        </div>
        @php
            // $status = $orderDetail->status;

            // $alertClass = match ($status) {
            //     'pending' => 'alert-error',
            //     'pesanan dibuat' => 'alert-warning', 
            //     'pesanan diantar' => 'alert-info', 
            //     'pesanan sampai' => 'alert-primary', 
            //     'menunggu confirm user' => 'alert-secondary', 
            //     'completed' => 'alert-success', 
            //     default => 'alert-neutral',
            // };
            $status = ['pending', 'pesanan_dibuat', 'completed', 'cancel', 'request cancel', 'not_shipped', 'delivered', 'returned'];
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
                    <div class="flex flex-col gap-6">
                        @foreach ($orderItems as $item)
                            <div class="flex items-center gap-4 p-4">
                                <img src="{{ asset('storage/'.$item['product']->product_img[0]) }}" class="w-32 h-32 object-cover rounded-md" alt="{{ $item['product']->product_name }}">
                                <div class="flex flex-col">
                                    <p class="font-semibold">{{ $item['product']->product_name }}</p>
                                    <p class="text-gray-500">Size {{ $item['size_stock']->size }}</p>
                                    <p class="text-gray-500">Rp {{ number_format($item['product']->discounted_price, 2) }}</p>
                                    <p class="text-gray-500">{{ $item['qty'] }} Barang</p>
                                    @php
                                        $totalProduct = ($item['product']->discounted_price ?? 0) * $item['qty'];
                                    @endphp
                                    <p class="font-semibold">Total Harga Product: Rp {{ number_format($totalProduct, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                @if (!empty($orderDetail->city?->city_name))
                <div class="bg-gray-100 p-4 rounded-lg mt-5">
                    <div>
                        <p class="font-semibold">Alamat:</p>
                        <p class="text-gray-500">{{$orderDetail->addres}}</p>
                        <p class="text-gray-500">Kota {{$orderDetail->city->city_name}}</p>
                        <p class="text-gray-500">Provinsi {{$orderDetail->city->province->province_name}}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-xl font-extrabold mb-4 text-center">Payment</h3>
                <div class="">
                    <p class="font-semibold">Metode Pengiriman</p>
                    <p class="font-semibold text-gray-500">{{$orderDetail->payment->payment_name}}</p>
                </div>
                
                
                
                @if (!empty($orderDetail->city?->city_name))
                    <div class="bg-gray-100 p-4 rounded-lg mt-5">
                        <div class="flex justify-between">
                            <h4 class="text-lg font-semibold mb-2">Ongkir:</h4>
                            <h4 class="font-semibold">
                                Rp {{ number_format((float) ($orderDetail->voucher->discount_voucher ?? 0), 0, ',', '.') }}
                            </h4>
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
                @if ($orderDetail->status === 'menunggu confirm user')
                    <form action="{{ route('updateStatusCompleted', $orderDetail->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="mt-10 px-4 py-3 w-full bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Selesaikan Pesanan
                        </button>
                    </form>
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
                            <h1 class="font-bold text-lg md:text-xl">Subtotal</h1>
                            <h1 class="font-bold text-lg md:text-xl text-black-600">Rp {{ number_format($orderDetail->total_amount, 0, ',', '.') }}</h1>
                        </div>
                    </div>
                </div>
                <a href="{{ route('generatePdf', ['id' => $orderDetail->id]) }}" class="btn bg-gradient-to-r from-red-600 to-red-800 text-white hover:from-red-700 hover:to-red-900 w-full mt-3">Download Invoice</a>
                {{-- <button onclick="window.print()" class="btn btn-success w-full mt-3">Download Invoice</button> --}}
            </div>
        </div>
    </div>
</body>

@endsection
