<div id="categorySection" class="mt-12 lg:mt-20 ms-[10px] md:ms-8 lg:ms-[70px] w-[95%] md:w-[91%] lg:w-[90%] border-b-2 border-base-300 pb-20">
    <x-point text="Categories" />
    <div class="mt-2 flex items-end">
        <div class="text-2xl md:text-4xl lg:text-[36px] font-bold">Browse By Category</div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6 md:gap-8 mt-10 md:mt-14 justify-center">
        @foreach($categories as $category)
            <a href="{{ route('productPage', ['category' => $category->id]) }}" 
                class="group w-full max-w-[140px] sm:max-w-[160px] md:max-w-[180px] lg:max-w-[200px] aspect-square border-2 border-black p-2 flex flex-col items-center justify-center hover:text-white hover:bg-red-500 transition duration-200 cursor-pointer"
                data-aos="fade-zoom-in" data-aos-duration="1200">
                <div class="w-8 sm:w-10 md:w-12 h-auto">
                    <img src="{{ asset('storage/' . $category->category_img) }}" 
                        alt="{{ $category->category_name }}" 
                        class="w-full h-full object-cover mb-1 sm:mb-2 transition duration-200 filter group-hover:invert" />
                </div>
                <div class="text-sm sm:text-base font-semibold text-center">
                    {{ $category->category_name }}
                </div>
            </a>
        @endforeach
    </div>
</div>
