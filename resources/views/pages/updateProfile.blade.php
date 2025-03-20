@extends('layoutUser')

@section('content')
<div class="container mx-auto w-full p-6 h-screen mt-14">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Edit Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('updateProfile') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 shadow-md rounded-lg">
        @csrf
        
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="w-full lg:w-1/2 space-y-4">
                <!-- Nama -->
                <div class="form-control">
                    <label class="label font-bold">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input input-bordered w-full" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Email -->
                <div class="form-control">
                    <label class="label font-bold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input input-bordered w-full" required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="w-full lg:w-1/2 space-y-4">
                <!-- Nomor Telepon -->
                <div class="form-control">
                    <label class="label font-bold">Phone Number</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="input input-bordered w-full" required>
                    @error('no_hp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Foto Profil -->
                <div class="form-control">
                    <label class="label font-bold">Profile Picture</label>
                    <input type="file" name="user_img" class="file-input file-input-bordered w-full" accept="image/*">
                    @error('user_img') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- addres -->
        <div class="form-control">
            <label class="label font-bold">addres</label>
            <textarea name="addres" class="textarea textarea-bordered w-full lg:h-52" required>{{ old('addres', $user->addres) }}</textarea>
            @error('addres') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Submit -->
        <div class="form-control mt-4">
            <button type="submit" class="btn btn-primary w-full">Update Profile</button>
        </div>
    </form>
</div>
@endsection
