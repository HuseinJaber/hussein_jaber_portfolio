<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$educations->count()" label="education entries — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add education</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-3">
        @foreach ($educations as $edu)
            <div wire:key="edu-{{ $edu->id }}" data-sort-id="{{ $edu->id }}" @class(['rounded-xl border p-5', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $edu->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $edu->cancelled])>
                <div class="flex items-start gap-3">
                    @if (! $edu->cancelled)<x-admin.sort-handle />@endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold">{{ $edu->degree }}</p>
                                <p class="text-xs text-slate-400">{{ $edu->institution }} · {{ $edu->start_date }} – {{ $edu->end_date }}</p>
                            </div>
                            <div class="flex shrink-0 gap-1 text-xs">
                                @if ($edu->cancelled)
                                    <button wire:click="restore({{ $edu->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $edu->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $edu->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $edu->id }})" wire:confirm="Cancel this entry?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        @if ($edu->description)
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $edu->description }}</p>
                        @endif
                        @if ($editingId === $edu->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Degree" name="degree" wire:model="degree" />
                                <x-admin.input label="Institution" name="institution" wire:model="institution" />
                                <x-admin.input label="Location" name="location" wire:model="location" />
                                <x-admin.input label="Start" name="start_date" wire:model="start_date" />
                                <x-admin.input label="End" name="end_date" wire:model="end_date" />
                                <div class="sm:col-span-2"><x-admin.textarea label="Description" name="description" wire:model="description" rows="3" /></div>
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
        <x-admin.card title="New education" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Degree" name="degree" wire:model="degree" />
                <x-admin.input label="Institution" name="institution" wire:model="institution" />
                <x-admin.input label="Location" name="location" wire:model="location" />
                <x-admin.input label="Start" name="start_date" wire:model="start_date" />
                <x-admin.input label="End" name="end_date" wire:model="end_date" />
                <div class="sm:col-span-2"><x-admin.textarea label="Description" name="description" wire:model="description" rows="3" /></div>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
