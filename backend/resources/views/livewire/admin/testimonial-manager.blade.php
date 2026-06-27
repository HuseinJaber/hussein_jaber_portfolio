<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $testimonials->count() }} client testimonials.</p>
        <x-admin.button wire:click="create">+ Add testimonial</x-admin.button>
    </div>

    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit testimonial' : 'New testimonial'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Name" name="name" wire:model="name" />
                <x-admin.input label="Role" name="role" wire:model="role" />
                <x-admin.input label="Company" name="company" wire:model="company" />
                <x-admin.input label="Avatar URL" name="avatar" wire:model="avatar" />
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Testimonial" name="content" wire:model="content" rows="3" />
                </div>
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Rating: {{ $rating }}★</span>
                    <input type="range" min="1" max="5" wire:model.live="rating" class="w-full accent-amber-500">
                </label>
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($testimonials as $t)
            <div wire:key="t-{{ $t->id }}" class="rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold">{{ $t->name }}</p>
                        <p class="text-xs text-slate-400">{{ $t->role }}{{ $t->company ? ', '.$t->company : '' }}</p>
                    </div>
                    <div class="flex gap-1 text-xs">
                        <button wire:click="edit({{ $t->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                        <button wire:click="delete({{ $t->id }})" wire:confirm="Delete this testimonial?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Del</button>
                    </div>
                </div>
                <p class="mt-2 text-amber-500 text-sm">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">“{{ $t->content }}”</p>
            </div>
        @endforeach
    </div>
</div>
