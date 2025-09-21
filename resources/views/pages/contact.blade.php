@extends('layoutUser')
@section('content')
<div class="h-auto my-5">
<h1 class='mt-24 text-center text-4xl font-bold'>Contact</h1>
<div class="w-[90%] mx-auto mt-10">
    <div class="flex flex-wrap justify-center items-center md:gap-10 gap-y-10 lg:gap-y-0" data-aos="zoom-in">
        @foreach($contactData as $item)
            <a href="{{$item['url']}}" class="w-64 h-40 border-2 border-black rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out bg-white hover:bg-red-500 hover:text-white hover:scale-105 active:scale-95">
                <div class="flex flex-col items-center justify-center text-center h-full">
                    <i class="{{ $item['icon'] }} fa-2xl mt-4" aria-hidden="true"></i>
                    <h1 class="text-lg font-bold pt-4">{{ $item['name'] }}</h1>
                    <h1 class="text-lg font-semibold">{{ $item['contact'] }}</h1>
                </div>
            </a>
        @endforeach
    </div>
    
    <div class="mt-10 pb-10" data-aos="fade-up">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center space-x-2 w-full">
                <div class="w-24 md:w-96 border-2 border-black mt-3"></div>
                <h1 class='text-3xl md:text-5xl font-bold text-center'>Location</h1>
                <i class="fa-solid fa-location-dot text-red-500 mt-1 text-3xl md:text-[2.6rem]"></i>
                <div class="w-24 md:w-96 border-2 border-black mt-3"></div>
            </div>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.21638620506!2d110.45319007475763!3d-6.983771093017139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708df8c7c3545d%3A0x8967a413d7ad9cfc!2sOrilookstore!5e0!3m2!1sid!2sid!4v1739692354875!5m2!1sid!2sid" 
                class='w-full h-[300px] md:h-[500px] mt-5 rounded-2xl' 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
</div>
@endsection