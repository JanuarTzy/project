@props(['status' => 'aman'])

@php
    $classes = match ($status) {
        'aman' => 'bg-green-100 text-green-800 border-green-200',
        'menipis' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'habis' => 'bg-red-100 text-red-800 border-red-200',
        default => 'bg-gray-100 text-gray-800 border-gray-200',
    };

    $defaultText = match ($status) {
        'aman' => 'Aman',
        'menipis' => 'Menipis',
        'habis' => 'Habis',
        default => ucfirst($status),
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $classes]) }}>
    {{ $slot->isEmpty() ? $defaultText : $slot }}
</span>