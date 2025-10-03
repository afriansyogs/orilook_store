<section id="productSection" class="">
  <h1 class="font-extrabold text-5xl text-center pt-5" data-aos="fade-up" data-aos-duration="600">Cari Sepatumu!</h1>
  <!-- Products Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6 px-6 mt-6 sm:mt-10" data-aos="fade-up" data-aos-duration="800">
  @foreach ($products as $product)
  <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
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
      <div class="flex flex-wrap gap-2">
        <h2 class="text-lg font-bold text-gray-900 hover:text-red-600 transition-colors line-clamp-2">
          {{ $product->product_name }}
        </h2>
        <div class="badge badge-secondary text-xs">NEW</div>
      </div>

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
</section>

