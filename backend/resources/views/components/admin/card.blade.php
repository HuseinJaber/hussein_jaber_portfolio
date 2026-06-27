@props(['title' => null])
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-6 shadow-sm']) }}>
    @if ($title)
        <h2 class="mb-4 font-semibold">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
