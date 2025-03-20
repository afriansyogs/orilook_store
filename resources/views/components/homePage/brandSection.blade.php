<div class="container mx-auto p-4" data-aos="fade-up" data-aos-duration="600">
  {{-- <h2 class="text-2xl font-bold text-center mb-6">Cari Brand Sepatumu!</h2> --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-10 justify-items-center">
      @foreach($brands as $brand)
          <a href="{{ route('productPage', ['brand' => $brand->id]) }}" 
            class="flex items-center bg-white shadow-lg rounded-lg border-2 border-black overflow-hidden w-64 transform hover:scale-105 transition-transform">
              <div class="w-24 h-24">
                  <img src="{{ asset('storage/' . $brand->brand_img) }}" 
                    alt="{{ $brand->brand_name }}" 
                    class="w-full h-full object-cover">
              </div>
              <div class="p-4">
                  <h3 class="text-lg font-semibold text-gray-800">{{ $brand->brand_name }}</h3>
              </div>
          </a>
      @endforeach
  </div>
</div>
