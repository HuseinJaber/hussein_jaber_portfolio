<div>
    <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">
        Drag to reorder sections and toggle visibility. Expand a row to edit its heading copy.
        The hero is always first — edit it under <strong>Profile</strong>.
    </p>

    <form wire:submit="save" class="space-y-6">
        <x-admin.card title="Sections">
            <p class="mb-4 text-xs text-slate-400">Drag rows to reorder. Click <strong>Edit texts</strong> to expand copy fields.</p>

            <x-admin.sortable-list method="updateSectionOrder" class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach ($orderedDefinitions as $key => $meta)
                    <div
                        wire:key="section-{{ $key }}"
                        data-sort-id="{{ $key }}"
                        class="bg-white py-3 first:pt-0 last:pb-0 dark:bg-slate-900"
                    >
                        <div class="flex items-center gap-3">
                            <x-admin.sort-handle />

                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-slate-800 dark:text-slate-100">{{ $meta['label'] }}</p>
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                    {{ $sectionCopy[$key]['title'] ?? '' }}
                                    @if (! empty($sectionCopy[$key]['subtitle']))
                                        <span class="text-slate-400"> · </span>{{ Str::limit($sectionCopy[$key]['subtitle'], 60) }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <span class="hidden text-xs sm:inline {{ ($sections[$key] ?? true) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                    {{ ($sections[$key] ?? true) ? 'Visible' : 'Hidden' }}
                                </span>
                                <input
                                    type="checkbox"
                                    wire:model="sections.{{ $key }}"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    title="{{ ($sections[$key] ?? true) ? 'Visible on site' : 'Hidden from site' }}"
                                >
                                <button
                                    type="button"
                                    wire:click="toggleCopy('{{ $key }}')"
                                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-white/5"
                                >
                                    {{ $expandedKey === $key ? 'Close' : 'Edit texts' }}
                                    <svg
                                        class="h-3.5 w-3.5 transition {{ $expandedKey === $key ? 'rotate-180' : '' }}"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if ($expandedKey === $key)
                            <div class="mt-4 ml-8 grid gap-3 rounded-lg border border-slate-100 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/5 sm:grid-cols-2">
                                <p class="text-xs text-slate-500 dark:text-slate-400 sm:col-span-2">{{ $meta['description'] }}</p>

                                <x-admin.input
                                    label="Nav label"
                                    name="sectionCopy.{{ $key }}.nav_label"
                                    wire:model="sectionCopy.{{ $key }}.nav_label"
                                    placeholder="e.g. Work"
                                />
                                <x-admin.input
                                    label="Badge (eyebrow)"
                                    name="sectionCopy.{{ $key }}.eyebrow"
                                    wire:model="sectionCopy.{{ $key }}.eyebrow"
                                    placeholder="e.g. Portfolio"
                                />
                                <div class="sm:col-span-2">
                                    <x-admin.input
                                        label="Heading"
                                        name="sectionCopy.{{ $key }}.title"
                                        wire:model="sectionCopy.{{ $key }}.title"
                                        placeholder="e.g. Selected work"
                                    />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-admin.textarea
                                        label="Description (optional)"
                                        name="sectionCopy.{{ $key }}.subtitle"
                                        wire:model="sectionCopy.{{ $key }}.subtitle"
                                        rows="2"
                                        placeholder="Short intro under the heading"
                                    />
                                    @if ($key === 'newsletter')
                                        <p class="mt-1 text-xs text-slate-400">Use <code class="rounded bg-slate-200 px-1 dark:bg-white/10">{name}</code> for your name.</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Alignment</label>
                                    <select wire:model="sectionCopy.{{ $key }}.align" class="w-full rounded-lg border-slate-300 text-sm dark:border-white/10 dark:bg-white/5">
                                        <option value="center">Centered</option>
                                        <option value="left">Left</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </x-admin.sortable-list>
        </x-admin.card>

        <div class="flex flex-wrap items-center gap-3">
            <x-admin.button type="submit">
                <span wire:loading.remove wire:target="save">Save settings</span>
                <span wire:loading wire:target="save">Saving…</span>
            </x-admin.button>
            <x-admin.button type="button" variant="secondary" wire:click="enableAll">Show all sections</x-admin.button>
        </div>
    </form>
</div>
