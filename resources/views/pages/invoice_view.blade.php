<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Invoice</title>
  @include('link')
</head>

<body style="padding: 24px; background-color: white; color: #1f2937;">
  {{-- header  --}}
  <header style="border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 16px;">
    <div style="float: left; width: 12%; text-align: left; vertical-align: middle;">
      <img src="assets/img/logo_orilook.png" alt="Logo" style="width: 60px; height: auto;">
    </div>
    <div style="float: left; width: 40%; text-align: left; vertical-align: middle;">
      <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin: 0; line-height: 2; display: inline-block; padding-top: 10px;">ORILOOK STORE</h2>
    </div>
    <div style="float: right; width: 30%; text-align: right;">
      <h4 style="font-size: 1.125rem; font-weight: 600; color: #374151; margin: 0;">Invoice</h4>
      <p style="font-size: 0.875rem; margin: 4px 0;">#{{ $order->id }}</p>
      <p style="font-size: 0.875rem; margin: 4px 0;">{{ $order->created_at->format('d M Y') }}</p>
    </div>
    <div style="clear: both;"></div>
  </header>


  <div style="font-size: 0.875rem; margin-bottom: 24px;">
    <div style="float: left; width: 50%;">
      <h5 style="font-weight: 600; margin-bottom: 4px;">Dari:</h5>
      <p>ORILOOK STORE</p>
      <p>Jl. Bledak Kantil IV No.27, Tlogosari Kulon, Kec. Pedurungan, Kota Semarang, Jawa Tengah, Indonesia</p>
      <p>No Telp: 089670388783</p>
    </div>
    <div style="float: right; width: 50%;">
      <div class="" style="margin-left: 10%;">
        <h5 style="font-weight: 600; margin-bottom: 4px;">Untuk:</h5>
        <p>Name: {{ $order->user->name }}</p>
        <p>Email: {{ $order->user->email }}</p>
        <p>No Telp: {{ $order->user->no_hp }}</p>
        <p>{{ $order->addres }}</p>
      </div>
    </div>
    {{-- menghapus style float  --}}
    <div style="clear: both;"></div>
  </div>


  <table
    style="width: 100%; font-size: 0.875rem; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; margin-bottom: 24px; border-collapse: collapse;">
    <thead style="background-color: #f3f4f6;">
      <tr>
        <th style="text-align: left; padding: 8px;">Produk</th>
        <th style="text-align: center; padding: 8px;">Ukuran</th>
        <th style="text-align: center; padding: 8px;">Jumlah</th>
        <th style="text-align: right; padding: 8px;">Harga</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($decodedItems  as $item)    
      <tr style="border-top: 1px solid #e5e7eb;">
        <td style="padding: 8px; text-align: left">{{ $item['product_name'] ?? '-' }}</td>
        <td style="padding: 8px; text-align: center">{{ $item['size'] ?? '-' }}</td>
        <td style="padding: 8px; text-align: center">{{ $item['qty'] }}</td>
        <td style="padding: 8px; text-align: right">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
      </tr>
        @endforeach
    </tbody>
  </table>

  @php
    $subtotal = $order->product->price * $order->qty;
  @endphp

  <div style="display: flex; justify-content: flex-end; font-size: 0.875rem;">
    <div style="width: 50%;">
      <div style="display: flex; justify-content: space-between; padding: 4px 0;">
        <span style="color: #4b5563;">Subtotal</span>
        <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 4px 0;">
        <span style="color: #4b5563;">Ongkir</span>
        <span>Rp{{ number_format($order->city->shipping_price ?? 0, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 4px 0;">
        <span style="color: #4b5563;">Voucher</span>
        <span style="color: #c60101;">- Rp{{ number_format($order->voucher->discount_voucher ?? 0, 0, ',', '.') }}</span>
      </div>
      <div
        style="display: flex; justify-content: space-between; padding: 8px 0 0 0; margin-top: 8px; border-top: 1px solid #e5e7eb; font-weight: bold;">
        <span>Total</span>
        <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

  <footer style="margin-top: 10%; font-size: 0.875rem; text-align: center; color: #4b5563;">
    Terima kasih telah berbelanja di <strong style="color: #1f2937;">ORILOOK STORE</strong>!
  </footer>
</body>

</html>
