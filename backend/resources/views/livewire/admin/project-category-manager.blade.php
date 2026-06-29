<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$categories->count()" label="project categories — drag to reorder" />
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.projects') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-white/5">
                ← Projects
            </a>
            @unless ($showCancelled)
            <x-admin.button wire:click="create">+ Add category</x-admin.button>
            @endunless
        </div>
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-2">
        @foreach ($categories as $category)
            <div wire:key="cat-{{ $category->id }}" data-sort-id="{{ $category->id }}" @class(['flex items-center justify-between rounded-xl border px-5 py-3', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $category->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $category->cancelled])>
                <div class="flex items-center gap-3">
                    @if (! $category->cancelled)<x-admin.sort-handle />@endif
                    <div>
                        <p class="font-medium">{{ $category->name }}</p>
                        <p class="text-xs text-slate-400">{{ $category->projects_count }} project{{ $category->projects_count === 1 ? '' : 's' }}</p>
                    </div>
                </div>
                <div class="flex gap-1 text-xs">
                    @if ($category->cancelled)
                        <button wire:click="restore({{ $category->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                    @else
                        <button wire:click="edit({{ $category->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                        <button wire:click="delete({{ $category->id }})" wire:confirm="Cancel this category?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                    @endif
                </div>
            </div>
        @endforeach
    </x-admin.sortable-list>

    @if ($showModal)
        <x-admin.modal :title="$editingId ? 'Edit category' : 'Add category'" max-width="lg">
            <form wire:submit="save" class="space-y-4">
                <x-admin.input label="Name" name="name" wire:model="name" placeholder="e.g. E-Commerce" />
                <div class="flex gap-3">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="closeModal">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.modal>
    @endif
</div>
