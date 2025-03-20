<main class=" text-white h-screen bg-gradient-to-b from-red-600 to-gray-100 w-full">
    <div class="container mx-auto text-center py-16 px-6">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 mt-10" data-aos="fade-up" data-aos-duration="500">Cari Gayamu!</h1>
        <p class="text-lg md:text-xl mb-8" data-aos="fade-up" data-aos-duration="800">Temukan sepatu kece yang bikin kamu makin pede</p>
        <div class="flex justify-center mb-8 gap-x-5" data-aos="fade-up" data-aos-duration="800">
            <a href="/product"
                class="px-4 py-3 rounded-lg border-2 border-red-600 bg-white hover:bg-transparent text-red-600 font-bold transition-colors">Mulai</a>
            <a href="{{ route('aboutPage') }}"
                class="px-4 py-3 rounded-lg bg-transparent hover:bg-white border-2 hover:border-red-600 hover:text-red-600 text-white font-bold">ORILOOK
                STORE</a>
        </div>
        <div class="flex justify-center">
            <img alt="Green sneaker" class="home_img mx-auto" height="300"
                src="{{ asset('assets/img/shoesHome.png') }}" width="600"  data-aos="fade-up" data-aos-duration="1000"/>
        </div>
    </div>
</main>
