<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @include('link')
</head>

<body>
    <div class="navbar fixed top-0 left-0 w-full bg-base-100 shadow-md ">
        <div class="navbar-start">
            <div class="ms-1 w-12 h-12">
                <img src="{{ asset('assets/img/logo_orilook.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
        </div>
        <div class="navbar-center hidden md:flex">
            <a class="btn btn-ghost text-xl">Home</a>
            <a class="btn btn-ghost text-xl">About</a>
            <a class="btn btn-ghost text-xl">Product</a>
        </div>
        <div class="navbar-end">
            <div class="flex gap-x-2">
                <button class="rounded-full px-3 py-2 hover:bg-black hover:text-white">
                    <i class="fa-solid fa-box"></i>
                </button>
                <button class="rounded-full px-3 py-2 hover:bg-black hover:text-white">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
                <button class="rounded-full px-3 py-2 hover:bg-black hover:text-white">
                    <i class="fa-solid fa-user"></i>
                </button>
                <!-- Toggle Navbar -->
                <button id="navbar-toggle" class="rounded-full px-3 py-2 hover:bg-black hover:text-white md:hidden" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Collapsible Navbar -->
    <div id="collapsible-navbar" class="hidden md:hidden bg-base-100 shadow-md w-full">
        <ul class="menu bg-base-100 w-full mt-14 p-4">
            <li><a href="#">Home</a></li>
            <li><a href="#">Portfolio</a></li>
            <li><a href="#">About</a></li>
        </ul>
    </div>

    <script>
        const toggleButton = document.getElementById("navbar-toggle");
        const collapsibleNavbar = document.getElementById("collapsible-navbar");

        toggleButton.addEventListener("click", () => {
            collapsibleNavbar.classList.toggle("hidden");
        });
    </script>

</body>

</html>
