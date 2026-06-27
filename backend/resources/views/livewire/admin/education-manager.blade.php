<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $educations->count() }} education entries.</p>
        <x-admin.button wire:click="create">+ Add education</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit education' : 'New education'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Degree" name="degree" wire:model="degree" />
                <x-admin.input label="Institution" name="institution" wire:model="institution" />
                <x-admin.input label="Location" name="location" wire:model="location" />
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <x-admin.input label="Start" name="start_date" wire:model="start_date" />
                <x-admin.input label="End" name="end_date" wire:model="end_date" />
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Description" name="description" wire:model="description" rows="3" />
                </div>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="space-y-3">
        @foreach ($educations as $edu)
            <div wire:key="edu-{{ $edu->id }}" class="flex items-start justify-between rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-5">
                <div>
                    <p class="font-semibold">{{ $edu->degree }}</p>
                    <p class="text-xs text-slate-400">{{ $edu->institution }} · {{ $edu->start_date }} – {{ $edu->end_date }}</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $edu->description }}</p>
                </div>
                <div class="flex gap-1 text-xs">
                    <button wire:click="edit({{ $edu->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                    <button wire:click="delete({{ $edu->id }})" wire:confirm="Delete this entry?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
