<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $experiences->count() }} work experience entries.</p>
        <x-admin.button wire:click="create">+ Add experience</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit experience' : 'New experience'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Role" name="role" wire:model="role" />
                <x-admin.input label="Company" name="company" wire:model="company" />
                <x-admin.input label="Location" name="location" wire:model="location" />
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <x-admin.input label="Start (e.g. 2022)" name="start_date" wire:model="start_date" />
                <x-admin.input label="End (e.g. 2024 / Present)" name="end_date" wire:model="end_date" />
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Description" name="description" wire:model="description" rows="3" />
                </div>
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_current" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Current role</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="space-y-3">
        @foreach ($experiences as $exp)
            <div wire:key="exp-{{ $exp->id }}" class="flex items-start justify-between rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-5">
                <div>
                    <p class="font-semibold">{{ $exp->role }} <span class="text-slate-400">·</span> {{ $exp->company }}</p>
                    <p class="text-xs text-slate-400">{{ $exp->start_date }} – {{ $exp->is_current ? 'Present' : $exp->end_date }} · {{ $exp->location }}</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $exp->description }}</p>
                </div>
                <div class="flex gap-1 text-xs">
                    <button wire:click="edit({{ $exp->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                    <button wire:click="delete({{ $exp->id }})" wire:confirm="Delete this entry?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
