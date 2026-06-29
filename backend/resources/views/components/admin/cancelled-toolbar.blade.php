@props(['count' => 0, 'label' => 'records'])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-between gap-3']) }}>
    <p class="text-sm text-slate-500 dark:text-slate-400">
        {{ $count }} {{ $label }}
    </p>

    <div
        x-data="{ cancelled: @entangle('showCancelled') }"
        class="inline-flex rounded-lg border border-slate-200 bg-slate-100 p-0.5 text-sm dark:border-white/10 dark:bg-white/5"
        role="tablist"
        aria-label="Record view"
    >
        <button
            type="button"
            role="tab"
            :aria-selected="!cancelled"
            @click="cancelled = false"
            :class="!cancelled ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
            class="rounded-md px-3 py-1.5 font-medium transition"
        >
            Active
        </button>
        <button
            type="button"
            role="tab"
            :aria-selected="cancelled"
            @click="cancelled = true"
            :class="cancelled ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
            class="rounded-md px-3 py-1.5 font-medium transition"
        >
            Cancelled
        </button>
    </div>
</div>
