<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $links->count() }} social links shown across your site.</p>
        <x-admin.button wire:click="create">+ Add link</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit link' : 'New link'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Platform (e.g. github)" name="platform" wire:model="platform" />
                <x-admin.input label="Label" name="label" wire:model="label" />
                <div class="sm:col-span-2">
                    <x-admin.input label="URL" name="url" wire:model="url" />
                </div>
                <x-admin.input label="Icon key" name="icon" wire:model="icon" />
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="space-y-2">
        @foreach ($links as $link)
            <div wire:key="link-{{ $link->id }}" class="flex items-center justify-between rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 px-5 py-3">
                <div>
                    <p class="font-medium capitalize">{{ $link->label ?: $link->platform }}</p>
                    <a href="{{ $link->url }}" target="_blank" class="text-xs text-indigo-500 hover:underline">{{ $link->url }}</a>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    @unless ($link->is_active)<span class="rounded-full bg-slate-100 dark:bg-white/10 px-2 py-0.5">Hidden</span>@endunless
                    <button wire:click="edit({{ $link->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                    <button wire:click="delete({{ $link->id }})" wire:confirm="Delete this link?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
