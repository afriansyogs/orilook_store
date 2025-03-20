@extends('layoutUser')
@section('content')
<div class="pt-20 md:pt-0 min-h-screen bg-gradient-to-br bg-base-200 flex items-center justify-center p-6">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl lg:max-w-7xl flex flex-col lg:flex-row items-center lg:items-center p-6 lg:h-auto">
    
    {{-- Bagian Gambar (Tengah atas-bawah di Laptop, Atas di Mobile & Tablet) --}}
    <div class="w-full flex justify-center lg:w-1/3">
      <div class="w-40 h-40 md:w-48 md:h-48 lg:h-56 lg:w-56 rounded-full ring-4 ring-blue-500 ring-offset-2 overflow-hidden">
        @if(!empty($userDetail->user_img))
          <img src="{{ asset('storage/profile/' . $userDetail->user_img) }}" alt="User Profile" class="object-cover w-full h-full">
        @else
          <div class="flex items-center justify-center w-full h-full bg-gray-200">
            <i class="fas fa-user text-gray-500 text-6xl"></i>
          </div>
        @endif
      </div>
    </div>

    {{-- Bagian Informasi User --}}
    <div class="w-full lg:w-2/3 mt-6 md:mt-4 lg:mt-0 lg:text-left flex flex-col justify-center lg:px-8">
      <dl class="space-y-4">
        <div class="px-4 py-5 bg-gray-50 rounded-lg">
            <dt class="text-sm font-medium text-gray-500">Nama</dt>
            <dd class="text-sm text-gray-900">{{ $userDetail->name }}</dd>
        </div>
        <div class="px-4 py-5 bg-white rounded-lg">
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="text-sm text-gray-900">{{ $userDetail->email }}</dd>
        </div>
        <div class="px-4 py-5 bg-gray-50 rounded-lg">
            <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
            <dd class="text-sm text-gray-900">{{ $userDetail->no_hp }}</dd>
        </div>
        <div class="px-4 py-5 bg-white rounded-lg">
            <dt class="text-sm font-medium text-gray-500">addres</dt>
            <dd class="text-sm text-gray-900">{{ $userDetail->addres }}</dd>
        </div>
      </dl>

      {{-- Bagian Tombol --}}
      <div class="mt-6 flex flex-col md:flex-row gap-4 w-full justify-center lg:justify-start">
        <a href="{{route('updateProfilePage')}}" class="w-full md:w-auto text-center bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">Edit Profile</a>
        <a href="{{route('logout')}}" class="w-full md:w-auto text-center bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition">Logout</a>
      </div>
    </div>

  </div>
</div>
@endsection
