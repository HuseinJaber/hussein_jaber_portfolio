<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $services->count() }} services advertised to your clients.</p>
        <x-admin.button wire:click="create">+ Add service</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit service' : 'New service'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Title" name="title" wire:model="title" />
                <x-admin.input label="Icon key (optional)" name="icon" wire:model="icon" />
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Description" name="description" wire:model="description" rows="3" />
                </div>
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Active</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($services as $service)
            <div wire:key="service-{{ $service->id }}" class="rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-5">
                <div class="flex items-start justify-between">
                    <p class="font-semibold">{{ $service->title }}</p>
                    <div class="flex gap-1 text-xs">
                        <button wire:click="edit({{ $service->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                        <button wire:click="delete({{ $service->id }})" wire:confirm="Delete this service?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                    </div>
                </div>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $service->description }}</p>
                @unless ($service->is_active)<span class="mt-3 inline-block rounded-full bg-slate-100 dark:bg-white/10 px-2 py-0.5 text-xs">Hidden</span>@endunless
            </div>
        @endforeach
    </div>
</div>
