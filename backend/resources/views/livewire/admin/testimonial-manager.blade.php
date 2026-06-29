<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$testimonials->count()" label="testimonials — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add testimonial</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="grid gap-4 sm:grid-cols-2">
        @foreach ($testimonials as $t)
            <div wire:key="t-{{ $t->id }}" data-sort-id="{{ $t->id }}" @class(['rounded-xl border p-5', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $t->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $t->cancelled])>
                <div class="flex items-start gap-3">
                    @if (! $t->cancelled)<x-admin.sort-handle />@endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold">{{ $t->name }}</p>
                                <p class="text-xs text-slate-400">{{ $t->role }}{{ $t->company ? ', '.$t->company : '' }}</p>
                            </div>
                            <div class="flex gap-1 text-xs">
                                @if ($t->cancelled)
                                    <button wire:click="restore({{ $t->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $t->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $t->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $t->id }})" wire:confirm="Cancel this testimonial?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-amber-500">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">“{{ $t->content }}”</p>
                        @if ($editingId === $t->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Name" name="name" wire:model="name" />
                                <x-admin.input label="Role" name="role" wire:model="role" />
                                <x-admin.input label="Company" name="company" wire:model="company" />
                                <x-admin.input label="Avatar URL" name="avatar" wire:model="avatar" />
                                <div class="sm:col-span-2"><x-admin.textarea label="Testimonial" name="content" wire:model="content" rows="3" /></div>
                                <label class="block sm:col-span-2">
                                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Rating: {{ $rating }}★</span>
                                    <input type="range" min="1" max="5" wire:model.live="rating" class="w-full accent-amber-500">
                                </label>
                                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published</label>
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
        <x-admin.card title="New testimonial" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Name" name="name" wire:model="name" />
                <x-admin.input label="Role" name="role" wire:model="role" />
                <x-admin.input label="Company" name="company" wire:model="company" />
                <x-admin.input label="Avatar URL" name="avatar" wire:model="avatar" />
                <div class="sm:col-span-2"><x-admin.textarea label="Testimonial" name="content" wire:model="content" rows="3" /></div>
                <label class="block sm:col-span-2">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Rating: {{ $rating }}★</span>
                    <input type="range" min="1" max="5" wire:model.live="rating" class="w-full accent-amber-500">
                </label>
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
