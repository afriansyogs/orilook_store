<div id="platformSection" class="w-full py-16 px-6 lg:px-20">
    <h1 class="text-4xl font-bold text-gray-900 text-center mb-10" data-aos="fade-up" data-aos-duration="600">Temukan Kami di Platform</h1>
    <div class="flex flex-wrap justify-center gap-6">
        @foreach ($platformsData as $platform)
            <a href="{{ $platform['url'] }}" target="_blank"
                class="w-full md:w-[450px] lg:w-[500px] flex items-center bg-white shadow-md rounded-lg p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02]"
                data-aos="fade-up" data-aos-duration="800">
                <div
                    class="w-16 h-16 bg-gray-100 flex-shrink-0 rounded-lg overflow-hidden flex justify-center items-center">
                    <img src="{{ asset($platform['img']) }}" alt="{{ $platform['platform'] }}"
                        class="w-full h-full object-contain">
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $platform['platform'] }}</h2>
                    <p class="text-gray-600 text-sm">{{ $platform['name'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
