@props(['title', 'maxWidth' => 'lg', 'closeAction' => 'closeModal'])

@php
    $panelWidth = match ($maxWidth) {
        'sm', 'max-w-sm' => 'max-w-sm',
        'md', 'max-w-md' => 'max-w-md',
        'lg', 'max-w-lg' => 'max-w-lg',
        'xl', 'max-w-xl' => 'max-w-xl',
        '2xl', 'max-w-2xl' => 'max-w-2xl',
        '3xl', 'max-w-3xl' => 'max-w-3xl',
        default => 'max-w-lg',
    };
@endphp

<div
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/60 px-4 py-8 sm:py-12"
    wire:keydown.escape.window="{{ $closeAction }}"
>
    <div class="absolute inset-0" wire:click="{{ $closeAction }}" aria-hidden="true"></div>

    <div {{ $attributes->merge(['class' => "relative w-full {$panelWidth} rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900"]) }}>
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
            <h2 class="text-lg font-semibold">{{ $title }}</h2>
            <button type="button" wire:click="{{ $closeAction }}" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Close">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
