<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$experiences->count()" label="work experience entries — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add experience</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-3">
        @foreach ($experiences as $exp)
            <div wire:key="exp-{{ $exp->id }}" data-sort-id="{{ $exp->id }}" @class(['rounded-xl border p-5', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $exp->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $exp->cancelled])>
                <div class="flex items-start gap-3">
                    @if (! $exp->cancelled)
                        <x-admin.sort-handle />
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold">{{ $exp->role }} <span class="text-slate-400">·</span> {{ $exp->company }}</p>
                                <p class="text-xs text-slate-400">{{ $exp->start_date }} – {{ $exp->is_current ? 'Present' : $exp->end_date }} · {{ $exp->location }}</p>
                            </div>
                            <div class="flex shrink-0 gap-1 text-xs">
                                @if ($exp->cancelled)
                                    <button wire:click="restore({{ $exp->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $exp->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $exp->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $exp->id }})" wire:confirm="Cancel this entry?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        @if ($exp->description)
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $exp->description }}</p>
                        @endif

                        @if ($editingId === $exp->id)
                            <div class="mt-4 border-t border-slate-200 pt-4 dark:border-white/10">
                                <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                                    <x-admin.input label="Role" name="role" wire:model="role" />
                                    <x-admin.input label="Company" name="company" wire:model="company" />
                                    <x-admin.input label="Location" name="location" wire:model="location" />
                                    <x-admin.input label="Start (e.g. 2022)" name="start_date" wire:model="start_date" />
                                    <x-admin.input label="End (e.g. 2024 / Present)" name="end_date" wire:model="end_date" />
                                    <div class="sm:col-span-2">
                                        <x-admin.textarea label="Description" name="description" wire:model="description" rows="3" />
                                    </div>
                                    <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_current" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Current role</label>
                                    <div class="flex gap-3 sm:col-span-2">
                                        <x-admin.button type="submit">Update</x-admin.button>
                                        <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </x-admin.sortable-list>

    @if ($creatingNew)
        <x-admin.card title="New experience" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Role" name="role" wire:model="role" />
                <x-admin.input label="Company" name="company" wire:model="company" />
                <x-admin.input label="Location" name="location" wire:model="location" />
                <x-admin.input label="Start (e.g. 2022)" name="start_date" wire:model="start_date" />
                <x-admin.input label="End (e.g. 2024 / Present)" name="end_date" wire:model="end_date" />
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Description" name="description" wire:model="description" rows="3" />
                </div>
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_current" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Current role</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
