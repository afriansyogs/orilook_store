@extends('layoutUser ')
@section('content')
<div class="max-w-2xl mx-auto mt-14 md:mt-24">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Beri Ulasan</h2>

        @if(session('success'))
            <div class="alert alert-success mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('addReview', ['order' => $order->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Rating:</label>
                <div class="rating rating-lg">
                    @for($i = 1; $i <= 5; $i++)
                        <input type="radio" name="rating" value="{{ $i }}" class="mask mask-star-2 bg-yellow-400" required />
                    @endfor
                </div>
                @error('rating') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Review:</label>
                <textarea name="review" rows="4" class="textarea textarea-bordered w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis ulasanmu..." required></textarea>
                @error('review') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full py-2 px-4 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">Kirim Ulasan</button>
        </form>
    </div>
</div>
@endsection