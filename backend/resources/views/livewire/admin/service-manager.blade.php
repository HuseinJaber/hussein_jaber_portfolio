<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$services->count()" label="services — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add service</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="grid gap-4 sm:grid-cols-2">
        @foreach ($services as $service)
            <div wire:key="service-{{ $service->id }}" data-sort-id="{{ $service->id }}" @class(['rounded-xl border p-5', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $service->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $service->cancelled])>
                <div class="flex items-start gap-3">
                    @if (! $service->cancelled)<x-admin.sort-handle />@endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="font-semibold">{{ $service->title }}</p>
                            <div class="flex gap-1 text-xs">
                                @if ($service->cancelled)
                                    <button wire:click="restore({{ $service->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $service->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $service->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $service->id }})" wire:confirm="Cancel this service?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $service->description }}</p>
                        @unless ($service->is_active)<span class="mt-3 inline-block rounded-full bg-slate-100 px-2 py-0.5 text-xs dark:bg-white/10">Hidden</span>@endunless
                        @if ($editingId === $service->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Title" name="title" wire:model="title" />
                                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
                                <div class="sm:col-span-2"><x-admin.textarea label="Description" name="description" wire:model="description" rows="3" /></div>
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
        <x-admin.card title="New service" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Title" name="title" wire:model="title" />
                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
                <div class="sm:col-span-2"><x-admin.textarea label="Description" name="description" wire:model="description" rows="3" /></div>
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
