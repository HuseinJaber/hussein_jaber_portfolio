@props(['variant' => 'primary'])
@php
    $classes = match ($variant) {
        'secondary' => 'border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };
@endphp
<button {{ $attributes->merge(['type' => 'button', 'class' => "inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition disabled:opacity-50 $classes"]) }}>
    {{ $slot }}
</button>
