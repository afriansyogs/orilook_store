<div class="2">
  <div class="flex flex-wrap mt-24 md:mt-20 gap-y-8 gap-x-8 md:gap-x-24 justify-center mb-16 px-4 lg:px-0" data-aos="zoom-in" data-aos-duration="500">
    @foreach ($statisticData as $items)
        <div class="flex flex-col items-center justify-center w-56 lg:w-64 h-52 lg:h-60 border-2 border-black bg-transparent hover:bg-red-500 hover:text-white group transition duration-150 rounded-lg">
            <div class="w-20 h-20 rounded-full bg-black border-8 border-gray-300 text-xl text-white flex items-center justify-center mb-2 group-hover:border-red-300 group-hover:bg-white">
                <i class="fa-solid {{ $items['iconStatistic'] }} fa-lg group-hover:text-black transition"></i> 
            </div>
            <div class="items-center justify-center text-center">
                {{-- <h1 class="mt-2 font-bold text-3xl">{{ $items['jumlahStatistic'] }}</h1> --}}
                <p class="text-sm mt-3 mx-5">{{ $items['descriptionStatistic'] }}</p>
            </div>
        </div>
    @endforeach
  </div>
</div>