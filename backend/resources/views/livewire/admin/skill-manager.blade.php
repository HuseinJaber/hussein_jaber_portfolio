<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$skills->count()" label="skills — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add skill</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-2">
        @foreach ($skills as $skill)
            <div wire:key="skill-{{ $skill->id }}" data-sort-id="{{ $skill->id }}" @class(['rounded-xl border p-4', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $skill->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $skill->cancelled])>
                <div class="flex items-center gap-3">
                    @if (! $skill->cancelled)
                        <x-admin.sort-handle />
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium">{{ $skill->name }}</p>
                                <p class="text-xs text-slate-400">{{ $skill->category }} · {{ $skill->level }}%</p>
                            </div>
                            <div class="flex gap-1 text-xs">
                                @if ($skill->cancelled)
                                    <button wire:click="restore({{ $skill->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $skill->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $skill->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $skill->id }})" wire:confirm="Cancel this skill?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-slate-100 dark:bg-white/10">
                            <div class="h-2 rounded-full bg-indigo-600" style="width: {{ $skill->level }}%"></div>
                        </div>
                        @if ($editingId === $skill->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Name" name="name" wire:model="name" />
                                <x-admin.input label="Category" name="category" wire:model="category" />
                                <label class="block sm:col-span-2">
                                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Level: {{ $level }}%</span>
                                    <input type="range" min="0" max="100" wire:model.live="level" class="w-full accent-indigo-600">
                                </label>
                                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
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
        <x-admin.card title="New skill" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Name" name="name" wire:model="name" />
                <x-admin.input label="Category" name="category" wire:model="category" />
                <label class="block sm:col-span-2">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Level: {{ $level }}%</span>
                    <input type="range" min="0" max="100" wire:model.live="level" class="w-full accent-indigo-600">
                </label>
                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
