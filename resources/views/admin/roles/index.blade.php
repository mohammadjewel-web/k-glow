@extends('layouts.admin')
@section('title','Roles & Permissions')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[--brand-orange]">Roles & Permissions</h1>
        <a href="{{ route('admin.roles.create') }}"
            class="bg-[--brand-orange] text-white px-4 py-2 rounded hover:bg-orange-600 transition">Create Role</a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permissions</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($roles as $role)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @foreach($role->permissions as $perm)
                        <span
                            class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mr-1 mb-1">{{ $perm->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                            class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection