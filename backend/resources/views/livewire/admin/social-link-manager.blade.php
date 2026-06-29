<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$links->count()" label="social links — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add link</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-2">
        @foreach ($links as $link)
            <div wire:key="link-{{ $link->id }}" data-sort-id="{{ $link->id }}" @class(['rounded-xl border px-5 py-3', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $link->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $link->cancelled])>
                <div class="flex items-center gap-3">
                    @if (! $link->cancelled)<x-admin.sort-handle />@endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium capitalize">{{ $link->label ?: $link->platform }}</p>
                                <a href="{{ $link->url }}" target="_blank" class="text-xs text-indigo-500 hover:underline">{{ $link->url }}</a>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                @unless ($link->is_active)<span class="rounded-full bg-slate-100 px-2 py-0.5 dark:bg-white/10">Hidden</span>@endunless
                                @if ($link->cancelled)
                                    <button wire:click="restore({{ $link->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $link->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $link->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $link->id }})" wire:confirm="Cancel this link?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        @if ($editingId === $link->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Platform (e.g. github)" name="platform" wire:model="platform" />
                                <x-admin.input label="Label" name="label" wire:model="label" />
                                <div class="sm:col-span-2"><x-admin.input label="URL" name="url" wire:model="url" /></div>
                                <x-admin.input label="Icon key" name="icon" wire:model="icon" />
                                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                                <div class="flex gap-3 sm:col-span-2">
                                    <x-admin.button type="submit">Update</x-admin.button>
                                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </x-admin.sortable-list>

    @if ($creatingNew)
        <x-admin.card title="New link" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Platform (e.g. github)" name="platform" wire:model="platform" />
                <x-admin.input label="Label" name="label" wire:model="label" />
                <div class="sm:col-span-2"><x-admin.input label="URL" name="url" wire:model="url" /></div>
                <x-admin.input label="Icon key" name="icon" wire:model="icon" />
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
