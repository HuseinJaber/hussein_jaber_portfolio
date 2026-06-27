@props(['label' => null, 'name' => null, 'rows' => 4])
<label class="block">
    @if ($label)
        <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</span>
    @endif
    <textarea rows="{{ $rows }}" {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-800 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 focus:outline-none']) }}>{{ $slot }}</textarea>
    @if ($name)
        @error($name) <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span> @enderror
    @endif
</label>
