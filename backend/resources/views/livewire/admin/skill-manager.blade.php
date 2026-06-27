<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $skills->count() }} skills grouped by category.</p>
        <x-admin.button wire:click="create">+ Add skill</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit skill' : 'New skill'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Name" name="name" wire:model="name" />
                <x-admin.input label="Category" name="category" wire:model="category" />
                <label class="block sm:col-span-2">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Level: {{ $level }}%</span>
                    <input type="range" min="0" max="100" wire:model.live="level" class="w-full accent-indigo-600">
                </label>
                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($skills as $skill)
            <div wire:key="skill-{{ $skill->id }}" class="rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $skill->name }}</p>
                        <p class="text-xs text-slate-400">{{ $skill->category }}</p>
                    </div>
                    <div class="flex gap-1 text-xs">
                        <button wire:click="edit({{ $skill->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                        <button wire:click="delete({{ $skill->id }})" wire:confirm="Delete this skill?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                    </div>
                </div>
                <div class="mt-3 h-2 w-full rounded-full bg-slate-100 dark:bg-white/10">
                    <div class="h-2 rounded-full bg-indigo-600" style="width: {{ $skill->level }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
