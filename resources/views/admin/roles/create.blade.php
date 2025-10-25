@extends('layouts.admin')
@section('title','Create Role')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-bold text-[--brand-orange] mb-4">Create Role</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block text-gray-700 mb-1">Role Name</label>
            <input type="text" name="name"
                class="w-full px-4 py-2 border rounded focus:ring-1 focus:ring-[--brand-orange]" required>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Assign Permissions</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($permissions as $permission)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        class="form-checkbox h-5 w-5 text-[--brand-orange]">
                    <span>{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit"
            class="bg-[--brand-orange] text-white px-6 py-2 rounded hover:bg-orange-600 transition">Create Role</button>
    </form>
</div>
@endsection