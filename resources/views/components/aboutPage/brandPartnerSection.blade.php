<div id="brandSection" class="w-full py-16 px-6 lg:px-20 text-center">
    <h1 class="text-4xl font-bold text-gray-900 mb-10" data-aos="fade-up" data-aos-duration="600">Brand Partner</h1>
    <div class="flex flex-wrap justify-center gap-6 lg:gap-10">
        @foreach ($brandPartnerData as $brand)
            <a href="{{ $brand['url'] }}" target="_blank"
                class="w-40 h-40 sm:w-48 sm:h-48 lg:w-52 lg:h-52 bg-white rounded-xl shadow-lg flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 hover:shadow-xl"
                data-aos="fade-up" data-aos-duration="800">
                <img src="{{ asset($brand['img']) }}" alt="{{ $brand['name'] }}" class="w-full h-full object-contain">
            </a>
        @endforeach
    </div>
</div>
