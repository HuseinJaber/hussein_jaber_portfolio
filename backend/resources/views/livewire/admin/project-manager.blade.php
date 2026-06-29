<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$projects->count()" label="projects — drag rows to reorder" />
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.project-categories') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-white/5">
                Categories
            </a>
            <a href="{{ route('admin.tech-stacks') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-white/5">
                Tech stacks
            </a>
            @unless ($showCancelled)
            <a href="{{ route('admin.projects.create') }}" wire:navigate>
                <x-admin.button>+ Add project</x-admin.button>
            </a>
            @endunless
        </div>
    </div>

    <x-admin.card class="overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-white/10">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:bg-white/5">
                    <tr>
                        <th class="w-10 px-3 py-3"></th>
                        <th class="px-5 py-3">Project</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Linked to</th>
                        <th class="px-5 py-3">Engagement</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-100 dark:divide-white/5"
                    x-data
                    x-init="$nextTick(() => window.initAdminSortable && window.initAdminSortable($el, @this, 'updateSortOrder'))"
                    data-sortable-list
                >
                    @forelse ($projects as $project)
                        <tr wire:key="project-row-{{ $project->id }}" data-sort-id="{{ $project->id }}" @class(['bg-amber-50/50 dark:bg-amber-900/10' => $project->cancelled, 'hover:bg-slate-50 dark:hover:bg-white/5' => ! $project->cancelled])>
                            <td class="px-3 py-3">
                                @if (! $project->cancelled)
                                    <x-admin.sort-handle />
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium">{{ $project->title }}</p>
                                <p class="text-xs text-slate-400">{{ collect($project->tech_stack ?? [])->implode(' · ') }}</p>
                                @if ($project->contribution_labels)
                                    <p class="mt-0.5 text-xs text-indigo-500 dark:text-indigo-300">{{ collect($project->contribution_labels)->join(' · ') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-500">
                                {{ collect($project->categories ?? [])->join(', ') ?: '—' }}
                            </td>
                            <td class="px-5 py-3 text-xs">
                                @if ($project->work_context === 'company' && $project->experience)
                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">{{ $project->experience->company }}</span>
                                @elseif ($project->work_context === 'freelance')
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Freelance</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs',
                                    'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300' => $project->engagement_type === 'support',
                                    'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300' => $project->engagement_type !== 'support',
                                ])>
                                    {{ $project->engagement_type === 'support' ? 'Support' : 'Development' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                @if ($project->cancelled)
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Cancelled</span>
                                @else
                                    @if ($project->is_featured)<span class="mr-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700 dark:text-amber-300">Featured</span>@endif
                                    @if ($project->is_published)<span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700 dark:text-emerald-300">Live</span>@else<span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs dark:bg-white/10">Draft</span>@endif
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-2">
                                    @if ($project->cancelled)
                                        <button wire:click="restore({{ $project->id }})" class="rounded-md px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                    @else
                                        <a href="{{ route('admin.projects.edit', $project) }}" wire:navigate class="rounded-md px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">Edit</a>
                                        <button wire:click="delete({{ $project->id }})" wire:confirm="Cancel this project? It will be hidden from the site but kept in admin." class="rounded-md px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">No projects yet — add your first one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
</div>
