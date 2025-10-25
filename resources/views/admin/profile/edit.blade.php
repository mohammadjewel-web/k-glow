@extends('layouts.admin')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-[--brand-orange] mb-4">Edit Profile</h2>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[--brand-orange]" required>
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[--brand-orange]" required>
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-gray-700">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[--brand-orange]" required>
            @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>


        <div>
            <label class="block text-gray-700">Password <span class="text-gray-400">(leave blank to keep
                    current)</span></label>
            <input type="password" name="password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[--brand-orange]">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[--brand-orange]">
        </div>

        <div>
            <label class="block text-gray-700">Avatar</label>
            <input type="file" name="avatar" class="w-full">
            @if($user->avatar)
            <img src="{{ asset('storage/avatars/'.$user->avatar) }}" class="w-20 h-20 rounded-full mt-2" alt="Avatar">
            @endif
        </div>

        <button type="submit"
            class="bg-[--brand-orange] text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">Update
            Profile</button>
    </form>
</div>
@endsection