@extends('layouts.admin')
@section('title','Edit User Roles')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-bold text-[--brand-orange] mb-4">Edit Roles - {{ $user->name }}</h1>

    <form action="{{ route('admin.users.update-roles', $user->id) }}" method="POST"
        class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 mb-1">Assign Roles</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($roles as $role)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        class="form-checkbox h-5 w-5 text-[--brand-orange]"
                        {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                    <span>{{ $role->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit"
            class="bg-[--brand-orange] text-white px-6 py-2 rounded hover:bg-orange-600 transition">Update
            Roles</button>
    </form>
</div>
@endsection