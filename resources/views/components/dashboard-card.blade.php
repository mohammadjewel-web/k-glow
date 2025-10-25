@props(['title','value','icon','color'])

<div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-500">{{ $title }}</h2>
        <span class="text-xl {{ $color }}">{{ $icon }}</span>
    </div>
    <p class="mt-4 text-3xl font-bold {{ $color }}">{{ $value }}</p>
</div>