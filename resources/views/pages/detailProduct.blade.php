@extends('layoutUser')
@section('content')
<div class="bg-transparant mt-8">
  <div class="max-w-7xl mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- product img -->
      <div class=" md:sticky top-20 pt-5">
        <div class="w-full h-96 relative">
          <div class="flex flex-row items-center">
            <div class="w-12 h-12">
              <img src="{{ asset('assets/img/logo_orilook.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="font-bold text-2xl ms-1">ORILOOK STORE</h1>
          </div>
          <img  id="mainImage" src="{{ asset('storage/' . $productDetail->product_img[0]) }}" alt="{{ $productDetail->product_name }}" class="w-full h-full object-cover rounded-lg mt-2">
        </div>
      <div class="flex mt-[4rem] py-2 space-x-2 overflow-x-auto">
        @foreach ($productDetail->product_img as $image)
          <img src="{{ asset('storage/' . $image) }}" alt="{{ $productDetail->product_name }}" class="w-20 h-20 object-cover rounded cursor-pointer hover:ring-1 hover:ring-black" onclick="changeImage(this.src)"/>
        @endforeach
      </div>
    </div>
      <!-- Product Info -->
    <div class="product_info space-y-6 mt-10 h-screen overflow-y-auto">
      {{-- <div class="flex items-center justify-between">
      </div> --}}
      <h1 class="text-3xl font-bold">{{ $productDetail->product_name }}</h1>
      <div class="text-3xl font-bold text-red-600">
        Rp {{ number_format($productDetail->price, 0, ',', '.') }}
      </div>
      <div>
        <h3 class="font-medium mb-2">Ukuran</h3>
        <div class="flex flex-wrap space-x-2">
          @foreach ($productDetail->sizeStock as $sizeStock)
            <div class="flex flex-col items-center"> <!-- Bungkus size dan stock dalam div -->
              <button class="btn btn-outline size-btn" onclick="selectVariant(this, '{{ $sizeStock->id }}')" data-stock="{{ $sizeStock->stock }}">{{ $sizeStock->size }}</button>
              <p>Stock</p>
              <span class="text-sm mt-1 text-black">{{ $sizeStock->stock }}</span>
            </div>
          @endforeach
        </div>
      </div>
      <p class="whitespace-pre-line">{{ $productDetail->description }}</p>
      <div>
        <div class="">
          @if (!empty($productDetail->sizeStock) && count($productDetail->sizeStock) > 0)
          <form action="{{route('addToCart')}}" method="post">
            @csrf
            <input type="hidden" name="product_id" value="{{ $productDetail->id }}">
            <input type="hidden" name="size_stock_product_id" id="size_stock_product_id" value="">
            <input type="hidden" name="qty" value="1" min="1">
            <button type="submit" class="flex justify-center items-center px-6 py-3 border border-black text-white bg-red-500 hover:bg-red-600 rounded-lg w-full"
                @guest onclick="event.preventDefault(); window.location.href='{{ route('loginPage') }}';" @endguest>
              <i class="fa-solid fa-cart-plus"></i>
              <span class="ms-3">Keranjang</span> 
            </button>
          </form>
          @else
            <a href="{{route('productPage')}}" class="btn btn-neutral w-full">Product Belum Redy</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // changeImage 
  function changeImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
  }

  // selectVAriant 
  function selectVariant(button, sizeStockId) {
  document.querySelectorAll('.size-btn').forEach(btn => {
      btn.classList.remove('btn-neutral');
      btn.classList.add('btn-outline');
  });
  button.classList.add('btn-neutral');
  button.classList.remove('btn-outline');

  // Simpan ID size
  const stock = button.getAttribute('data-stock');

  // check stock
  if (parseInt(stock) === 0) {
    Swal.fire({
          icon: 'error',
          title: 'Stok Habis',
          text: 'Ukuran ini tidak tersedia, silakan pilih ukuran lain.',
          confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          button.classList.remove('btn-primary');
          button.classList.add('btn-outline');
        }
    });
      return;
  }
  // Simpan ID size
  document.getElementById('size_stock_product_id').value = sizeStockId;
  }
</script>
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
@endsection