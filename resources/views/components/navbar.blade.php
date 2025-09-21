@php
$currentUrl = url()->current();
@endphp

<!-- Navbar Utama -->
<div id="navbar" class="fixed top-0 left-0 w-full bg-gradient-to-r from-red-600 to-red-800 z-50 shadow-lg">
  <div class="flex justify-between items-center px-4 py-3">
    <!-- Logo -->
    <a href="{{ route('home')}}" class="w-12 h-12">
      <img src="{{ asset('assets/img/logo_orilook.png') }}" alt="Logo" class="w-full h-full object-contain invert">
    </a>

    <!-- Menu untuk Desktop -->
    <div class="hidden md:flex space-x-5">
      @foreach ($menuData as $menu)
        @if(!in_array($menu['name'], ['Box', 'Cart', 'User']))
          <a href="{{ $menu['url'] }}" class="text-xl font-semibold hover:text-white 
            {{ $menu['url'] == $currentUrl ? 'text-white' : 'text-red-300' }}">
            {{ $menu['name'] }}
          </a>
        @endif
      @endforeach
    </div>

    <!-- Menu End (User & Cart) -->
    <div class="flex items-center gap-x-2">
      @if(Auth::check() && Auth::user()->role === 'user')
        @foreach ($menuData as $menu)
          @if(in_array($menu['name'], ['Box', 'Cart', 'User']))
            <a href="{{ $menu['url'] }}" class="rounded-full px-3 py-2 hover:bg-white hover:text-red-600 
              {{ $menu['url'] == $currentUrl ? 'bg-white text-red-600' : 'text-white' }}">
              <i class="{{ $menu['icon'] }}"></i>
            </a>
          @endif
        @endforeach
      @else
        <a href="{{ route('loginPage') }}" class="px-5 py-2 border-2 border-red-700 rounded-full bg-white 
          text-red-700 hover:bg-red-700 hover:text-white hover:border-white font-bold active:scale-95">
          Login
        </a>
      @endif

      <!-- Toggle Button -->
      <button id="navbar-toggle" class="rounded-full px-3 py-2 text-white hover:bg-white hover:text-red-600 md:hidden" 
        aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </div>

  <!-- Collapsible Navbar (Mobile) -->
  <div id="collapsible-navbar" class="hidden absolute top-full left-0 w-full bg-white shadow-md z-40">
    <ul class="py-3 px-4 space-y-2">
      @foreach ($menuData as $menu)
        @if(!in_array($menu['name'], ['Box', 'Cart', 'User']))
          <li>
            <a href="{{ $menu['url'] }}" class="block px-4 py-2  hover:bg-red-300 rounded-lg {{ $menu['url'] == $currentUrl ? 'bg-red-600 text-white' : 'text-red-700' }}">
              {{ $menu['name'] }}
            </a>
          </li>
        @endif
      @endforeach
    </ul>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("navbar-toggle");
    const collapsibleNavbar = document.getElementById("collapsible-navbar");

    toggleButton.addEventListener("click", () => {
      collapsibleNavbar.classList.toggle("hidden");
    });
  });
</script>

