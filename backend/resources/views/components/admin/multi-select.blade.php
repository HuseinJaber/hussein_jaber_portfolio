@props([
    'label',
    'items',
    'wireModel',
    'selectedIds' => [],
    'manageRoute' => null,
    'manageLabel' => 'Manage',
    'placeholder' => 'Select…',
])

@php
    $selected = collect($items)->filter(fn ($item) => in_array($item->id, $selectedIds, true));
    $summary = match (true) {
        $selected->isEmpty() => $placeholder,
        $selected->count() === 1 => $selected->first()->name,
        $selected->count() === 2 => $selected->pluck('name')->join(', '),
        default => $selected->take(2)->pluck('name')->join(', ').' +'.($selected->count() - 2),
    };
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }} x-data="{ open: false }" @click.outside="open = false">
    <div class="mb-1.5 flex items-center justify-between gap-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</label>
        @if ($manageRoute)
            <a href="{{ $manageRoute }}" wire:navigate class="text-xs text-indigo-600 hover:underline dark:text-indigo-400">{{ $manageLabel }}</a>
        @endif
    </div>

    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-left text-sm text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:bg-white/10"
        :aria-expanded="open"
    >
        <span @class(['truncate', 'text-slate-400' => $selected->isEmpty()])>{{ $summary }}</span>
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.origin.top
        @click.stop
        class="absolute z-30 mt-1 max-h-52 w-full min-w-[12rem] overflow-y-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg dark:border-white/10 dark:bg-slate-900"
    >
        @forelse ($items as $item)
            <label wire:key="{{ $wireModel }}-{{ $item->id }}" class="flex cursor-pointer items-center gap-2 px-3 py-2 text-sm transition hover:bg-slate-50 dark:hover:bg-white/5">
                <input
                    type="checkbox"
                    value="{{ $item->id }}"
                    wire:model.live="{{ $wireModel }}"
                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >
                <span class="truncate">{{ $item->name }}</span>
            </label>
        @empty
            <p class="px-3 py-2 text-sm text-slate-400">Nothing available yet.</p>
        @endforelse
    </div>

    @error($wireModel) <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    @error($wireModel.'.*') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
</div>
