<div class="mb-12 md:mb-14 lg:mb-20 mt-12 md:mt-20 lg:mt-24 w-full md:px-4">
  <div class="flex flex-col md:flex-row md:gap-24 lg:gap-x-36 space-y-14 md:space-y-0 justify-center">
    @foreach ($serviceData as $items)
      <div class="flex flex-col items-center" data-aos="zoom-out">
          <div class="w-20 h-20 rounded-full bg-black border-8 border-gray-300 text-xl text-white flex items-center justify-center mb-2">
              <i class="{{ $items['icon'] }}"></i>
          </div>
          <div class='items-center justify-center'>
              <h1 class='font-semibold text-sm lg:text-xl text-center'>{{ $items['title'] }}</h1>
              <p class='text-sm text-center'>{{ $items['description'] }}</p>
          </div>
      </div>
    @endforeach
  </div>
</div>