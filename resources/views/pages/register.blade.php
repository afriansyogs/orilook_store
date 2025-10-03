<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @include('link')
    </head>
    <body>
        <div class="flex flex-col lg:flex-row w-full bg-white shadow-lg rounded-lg overflow-hidden lg:h-screen">
            <!-- Bagian Kiri: Gambar -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-0">
                <img src="{{ asset('assets/img/Sunday.png') }}" alt="Sunday" class="w-full max-w-md lg:max-w-lg h-auto drop-shadow-lg" />
            </div>
            <!-- Bagian Kanan: Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-10 shadow-xl">
                <div class="w-full max-w-md lg:max-w-lg">
                    <h1 class="text-4xl md:text-5xl font-bold text-center text-black mb-8">Create Account</h1>
                    <form method="POST" action="{{ route('registerProcess') }}" class="space-y-6">
                        @csrf
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                            <input id="name" name="name" type="text" placeholder="Enter your name"
                                class="border-b border-black focus:outline-none w-full pb-2 text-gray-800"
                                required />
                            @error('name')
                                <small class="text-red-400">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                            <input id="email" name="email" type="email" placeholder="Enter your email"
                                class="border-b border-black focus:outline-none w-full pb-2 text-gray-800"
                                required />
                            @error('email')
                                <small class="text-red-400">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Password -->
                        <div class="relative">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                            <input id="password" name="password" type="password" placeholder="Enter your password"
                                class="border-b border-black focus:outline-none w-full pb-2 text-gray-800" required />
                            <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                                class="absolute inset-y-0 right-2 flex items-center">
                                <i id="eyeIcon1" class="fa fa-eye"></i>
                            </button>
                            @error('password')
                                <small class="text-red-400">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- password confirmation  --}}
                        <div class="relative">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Enter your password"
                                class="border-b border-black focus:outline-none w-full pb-2 text-gray-800" required />
                            <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                                class="absolute inset-y-0 right-2 flex items-center">
                                <i id="eyeIcon2" class="fa fa-eye"></i>
                            </button>
                            @error('password')
                                <small class="text-red-400">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Tombol Register -->
                        <button type="submit"
                            class="w-full h-14 bg-red-500 rounded-md border-2 border-red-500 hover:bg-white hover:text-red-500 active:scale-105 transform duration-200 text-white font-semibold">
                            Register
                        </button>
                        <div class="text-center text-base font-medium my-2">Or</div>
                        <!-- Login -->
                        <div class="text-center text-base">
                            Already have an account? 
                            <a href="{{ route('loginPage') }}" class="font-bold underline underline-offset-2 cursor-pointer hover:text-blue-600">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function togglePassword(inputId, iconId) {
                let input = document.getElementById(inputId);
                let icon = document.getElementById(iconId);
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("fa-eye-slash", "fa-eye");
                }
            }
            
            document.addEventListener("DOMContentLoaded", () => {
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'OK'
                    });
                @endif
            });
        </script>
        @if (session('success'))
        <alert class="">{{ session('success') }}</alert>
    @endif
    </body>
</html>
