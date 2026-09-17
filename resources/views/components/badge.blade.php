@props([
    'status' => 'aman', // Nilai default: aman, menipis, atau habis
])

@php
    $normalizedStatus = strtolower(trim($status));

    $classes = match ($normalizedStatus) {
        'aman' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
        'menipis' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
        'habis' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
    };

    $label = match ($normalizedStatus) {
        'aman' => 'Aman',
        'menipis' => 'Menipis',
        'habis' => 'Habis',
        default => ucfirst($status),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold $classes"]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>
