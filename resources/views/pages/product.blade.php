@extends('layoutUser')
@section('content')
<div id="productPage" class="mt-12 min-h-screen bg-gradient-to-b from-white to-red-50">
  <div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="mb-8 text-center">
      <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
        <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-800">
          Temukan Koleksi Kami
        </span>
      </h1>
      <p class="text-gray-600 max-w-2xl mx-auto">Temukan gaya sempurna Anda dari pilihan sepatu premium kami yang dipilih dengan cermat</p>
    </div>

    <!-- Search & Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
      <form method="GET" action="{{ route('productPage') }}" class="space-y-4 md:space-y-0 md:flex md:flex-wrap md:items-end md:gap-4">
        <!-- Search Input -->
        <div class="flex-1 min-w-[250px]">
          <label class="input input-bordered flex items-center gap-2 w-full hover:border-red-400 transition-colors">
            <input type="text" name="search" class="grow" placeholder="Search products..." value="{{ request('search') }}"/>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-red-500">
              <path d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"/>
            </svg>
          </label>
        </div>

        <!-- Category Select -->
        <div class="w-full md:w-auto">
          <select name="category" class="select select-bordered w-full md:w-[200px] hover:border-red-400 transition-colors" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Brand Select -->
        <div class="w-full md:w-auto">
          <select name="brand" class="select select-bordered w-full md:w-[200px] hover:border-red-400 transition-colors" onchange="this.form.submit()">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
              <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                {{ $brand->brand_name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- sortable  --}}
        <div class="w-full md:w-auto">
          <select name="sortOrder" class="select select-bordered w-full md:w-[200px] hover:border-red-400 transition-colors" onchange="this.form.submit()">
              <option value="" selected disabled>Pilih Urutan</option>
              <option value="asc" {{ request('sortOrder') == 'asc' ? 'selected' : '' }}>Name (A-Z)</option>
              <option value="desc" {{ request('sortOrder') == 'desc' ? 'selected' : '' }}>Name (Z-A)</option>
          </select>
        </div>

        <!-- Search Button -->
        <button type="submit" class="btn bg-gradient-to-r from-red-600 to-red-800 text-white hover:from-red-700 hover:to-red-900 w-full md:w-auto">
          Search
        </button>
      </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach ($products as $product)
      <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-duration="800">
        <!-- Image Container -->
        <figure class="relative pt-[100%] overflow-hidden group">
          <img 
            src="{{ asset('storage/' . $product->product_img[0]) }}" 
            alt="{{ $product->product_name }}"
            class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
          >
          <!-- Discount Badge -->
          @if($product->discount > 0)
          <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
            -{{ round(($product->discount / $product->price) * 100) }}%
          </div>
          @endif
        </figure>

        <div class="card-body p-6">
          <!-- Brand Badge -->
          <div class="flex gap-2 mb-2">
            @if ($product->brand->brand_name == 'Patrobas')
              <span class="px-3 py-1 bg-black text-white text-xs rounded-full">{{ $product->brand->brand_name }}</span>
            @elseif($product->brand->brand_name == 'Ventela')
              <span class="px-3 py-1 bg-blue-900 text-white text-xs rounded-full">{{ $product->brand->brand_name }}</span>
            @else
              <span class="px-3 py-1 bg-red-600 text-white text-xs rounded-full">{{ $product->brand->brand_name }}</span>
            @endif
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $product->category->category_name }}</span>
          </div>

          <!-- Product Title -->
          <h2 class="text-lg font-bold text-gray-900 hover:text-red-600 transition-colors line-clamp-2">
            {{ $product->product_name }}
          </h2>

          <!-- Description -->
          <p class="text-gray-600 text-sm line-clamp-2 mt-2">
            {{ Str::limit($product->description, 80, '...') }}
          </p>

          <!-- Pricing -->
          <div class="mt-4 space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-xl font-bold text-red-600">
                Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
              </span>
              @if($product->discount > 0)
              <span class="text-sm text-gray-400 line-through">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </span>
              @endif
            </div>
          </div>

          <!-- View Button -->
          <a href="{{ route('detailProduct', $product->id) }}" 
              class="btn bg-gradient-to-r from-red-600 to-red-800 text-white hover:from-red-700 hover:to-red-900 w-full mt-4">
            View Details
          </a>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
      {{ $products->links() }}
    </div>
  </div>
</div>
@endsection