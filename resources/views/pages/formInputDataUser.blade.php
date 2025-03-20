<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    @include('link')
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-b from-red-600 to-gray-100 p-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl transform transition-all hover:scale-[1.02]">
        <div class="flex justify-center mb-6">
            <div class="bg-red-500 text-white p-4 rounded-full shadow-lg">
                <i class="fas fa-user-edit text-3xl"></i>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">Lengkapi Data Anda</h2>
        <p class="text-gray-600 text-center mb-6">Silakan isi detail di bawah ini</p>
        {{-- form  --}}
        <form action="{{ route('updateDataUserProcess') }}" method="POST">
            @csrf
            <div class="mb-4 relative">
                <label for="no_hp" class="block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500"><i class="fas fa-phone"></i></span>
                    <input type="number" name="no_hp" id="no_hp"
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none transition @error('no_hp') border-red-500 @enderror"
                        placeholder="Masukkan nomor telepon" value="{{ old('no_hp') }}">
                </div>
                @error('no_hp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="addres" class="block text-sm font-semibold text-gray-700">addres</label>
                <textarea name="addres" id="addres" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none transition @error('addres') border-red-500 @enderror"
                    placeholder="Masukkan addres lengkap">{{ old('addres') }}</textarea>
                @error('addres')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-red-600 to-red-800  hover:from-red-700 hover:to-red-900 text-white py-3 rounded-lg font-semibold text-lg shadow-md transition duration-200">
                <i class="fas fa-save mr-2"></i> Save
            </button>
        </form>

        <div class="text-center mt-4 text-gray-600 text-sm">
            <i class="fas fa-info-circle text-red-500"></i> Data Anda aman bersama kami.
        </div>
    </div>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Data Anda',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</body>
</html>
