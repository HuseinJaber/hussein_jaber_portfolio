<div>
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $projects->count() }} projects — these power the work section of your site.</p>
        <x-admin.button wire:click="create">+ Add project</x-admin.button>
    </div>

    {{-- Form panel --}}
    @if ($showForm)
        <x-admin.card :title="$editingId ? 'Edit project' : 'New project'" class="mb-6">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Title" name="title" wire:model="title" />
                <x-admin.input label="Category" name="category" wire:model="category" />
                <div class="sm:col-span-2">
                    <x-admin.input label="Short description" name="short_description" wire:model="short_description" />
                </div>
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Full description" name="description" wire:model="description" rows="4" />
                </div>
                <x-admin.input label="Cover image URL" name="cover_image" wire:model="cover_image" />
                <x-admin.input label="Tech stack (comma separated)" name="tech_stack" wire:model="tech_stack" />
                <x-admin.input label="Live URL" name="live_url" wire:model="live_url" />
                <x-admin.input label="Source URL" name="source_url" wire:model="source_url" />
                <x-admin.input label="Client" name="client" wire:model="client" />
                <x-admin.input label="Year" name="year" type="number" wire:model="year" />
                <x-admin.input label="Sort order" name="sort_order" type="number" wire:model="sort_order" />
                <div class="flex items-center gap-6 sm:col-span-2">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_featured" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Featured</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published</label>
                </div>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">{{ $editingId ? 'Update' : 'Create' }}</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif

    {{-- List --}}
    <x-admin.card class="overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-white/10 text-sm">
                <thead class="bg-slate-50 dark:bg-white/5 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Project</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse ($projects as $project)
                        <tr wire:key="project-{{ $project->id }}" class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="px-5 py-3">
                                <p class="font-medium">{{ $project->title }}</p>
                                <p class="text-xs text-slate-400">{{ collect($project->tech_stack ?? [])->implode(' · ') }}</p>
                            </td>
                            <td class="px-5 py-3">{{ $project->category }}</td>
                            <td class="px-5 py-3">
                                @if ($project->is_featured)<span class="mr-1 rounded-full bg-amber-100 dark:bg-amber-900/40 px-2 py-0.5 text-xs text-amber-700 dark:text-amber-300">Featured</span>@endif
                                @if ($project->is_published)<span class="rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2 py-0.5 text-xs text-emerald-700 dark:text-emerald-300">Live</span>@else<span class="rounded-full bg-slate-100 dark:bg-white/10 px-2 py-0.5 text-xs">Draft</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $project->id }})" class="rounded-md px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</button>
                                    <button wire:click="delete({{ $project->id }})" wire:confirm="Delete this project?" class="rounded-md px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">No projects yet — add your first one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
</div>
