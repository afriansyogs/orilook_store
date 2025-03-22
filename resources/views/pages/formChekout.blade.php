@extends('layoutUser')
@section('content')
    <div class="container mx-auto p-4 lg:p-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Checkout</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <!-- Keranjang Belanja -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Produk yang Dibeli</h3>
                    <div class="divide-y divide-gray-200">
                        @foreach ($cartItems as $item)
                            <div class="py-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <!-- Gambar Produk -->
                                        <img src="{{ asset('storage/' . $item->product->product_img[0]) }}"
                                            alt="{{ $item->product->product_name }}"
                                            class="w-16 h-16 object-cover rounded-md" />
                                        <div class="space-y-1">
                                            <p class="font-medium">{{ $item->product->product_name }}</p>
                                            <p class="text-sm text-gray-600">Size: {{ $item->sizeStock->size }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ $cartQuantities[$item->id] ?? 1 }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="font-semibold">
                                        Rp
                                        {{ number_format($item->total_price * ($cartQuantities[$item->id] ?? 1), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('checkout') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="address_section" class="white p-6 rounded-lg shadow-lg hidden">
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium">addres</label>
                            <textarea name="address" class="textarea textarea-bordered w-full" required>{{ auth()->user()->addres }}</textarea>
                            @error('address')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Pilihan Provinsi -->
                        <div class="flex flex-col lg:flex-row gap-x-4">
                            <div class="mb-4 flex-1">
                                <label class="block text-sm font-medium">Provinsi</label>
                                <select id="province" name="province" class="select select-bordered w-full" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->province_name }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 flex-1">
                                <label class="block text-sm font-medium">Kota</label>
                                <select id="city_id" name="city_id" class="select select-bordered w-full" disabled
                                    required>
                                    <option value="">Pilih Kota</option>
                                </select>
                                @error('city_id')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Ongkir -->
                        <div class="flex justify-between font-bold">
                            <span>Ongkir:</span>
                            <span class="text-blue-800">Rp <span id="shipping_price">0</span></span>
                        </div>
                    </div>
            </div>
            <!-- Form Checkout -->

            <div class="mt-6 bg-white p-4 rounded-lg shadow h-auto">
                <!-- Hidden Input untuk Cart Items -->
                @foreach ($cartItems as $item)
                    <input type="hidden" name="cartQuantities[{{ $item->id }}]"
                        value="{{ $cartQuantities[$item->id] ?? 1 }}">
                    <input type="hidden" name="selectedCartIds[{{ $item->id }}]" value="{{ $selectedCartIds[$item->id] ?? 1 }}">
                @endforeach

                <!-- Alert untuk Error -->
                @if ($errors->any())
                    <div class="alert alert-error mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Pilihan Pembayaran -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Metode Pengiriman</label>
                    <select id="payment_method" name="payment_method" class="select select-bordered w-full" required>
                        <option value="">Pilih Metode Pengiriman</option>
                        @foreach ($payments as $payment)
                            <option value="{{ $payment->id }}" data-name="{{ $payment->payment_name }}"
                                data-img="{{ asset('storage/' . $payment->payment_img) }}">
                                {{ $payment->payment_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tampilkan Gambar Pembayaran -->
                <div id="payment_image_section" class="mb-4 hidden">
                    {{-- <img id="payment_image" src="" alt="Metode Pembayaran" class="w-full h-auto object-contain"> --}}
                </div>

                <!-- Upload Bukti Pembayaran -->
                {{-- <div class="mb-4">
                    <label class="block text-sm font-medium">Bukti Pembayaran</label>
                    <input type="file" name="bukti_transaksi" id="bukti_transaksi"
                        class="file-input file-input-bordered w-full" />
                    @error('bukti_transaksi')
                        <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Voucher Section -->
                <div class="mb-4 mt-4">
                    <label class="block text-sm font-medium">Voucher</label>
                    <div class="flex gap-2">
                        <input type="text" id="voucher_input" name="voucher_name" class="input input-bordered flex-1"
                            placeholder="Masukkan kode voucher">
                        <button type="button" id="check_voucher" class="btn btn-secondary">Cek Voucher</button>
                    </div>
                    <div id="voucher_message" class="text-sm mt-1 text-red-500"></div>
                </div>

                <!-- Total Harga -->
                <div class="flex flex-col gap-2 mt-4">
                    <div class="flex justify-between font-bold">
                        <span>Subtotal:</span>
                        <span>Rp <span id="subtotal">{{ number_format($total, 0, ',', '.') }}</span></span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>Ongkir:</span>
                        <span class="text-blue-800">Rp <span id="shipping_price_total">0</span></span>
                    </div>
                    <div class="text-red-500 flex justify-between font-bold hidden" id="voucher_discount_section">
                        <span>Voucher:</span>
                        <span class="">- Rp <span id="voucher_discount">0</span></span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t">
                        <span>Total Bayar:</span>
                        <span>Rp <span id="total_price">{{ number_format($total, 0, ',', '.') }}</span></span>
                    </div>
                </div>

                <!-- Tombol Checkout -->
                <button type="submit" class="btn bg-gradient-to-r from-red-600 to-red-800 text-white hover:from-red-700 hover:to-red-900 w-full mt-3" id="pay-button">Bayar Sekarang</button>
            </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); 
            const formData = new FormData(this);
            fetch('{{ route('checkout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mendapatkan token pembayaran');
                }
                return response.json();
            })
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil!');
                            window.location.href = '/order'; // Redirect ke halaman sukses
                        },
                        onPending: function(result) {
                            alert('Menunggu pembayaran...');
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                        }
                    });
                } else {
                    alert('Error: Token pembayaran tidak ditemukan');
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        });
    </script>
    <script>
        window.orderSubtotal = {{ $total }};
    </script>
    <script src="assets/js/checkout.js"></script>
@endsection
