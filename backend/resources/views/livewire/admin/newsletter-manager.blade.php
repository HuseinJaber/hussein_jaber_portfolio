<div>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <x-admin.cancelled-toolbar :count="$totalCount" label="newsletter subscribers" />
    </div>

    <x-admin.card class="mt-6 !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Subscribed</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse ($subscribers as $subscriber)
                        <tr wire:key="subscriber-{{ $subscriber->id }}" @class(['hover:bg-slate-50 dark:hover:bg-white/5', 'bg-amber-50/50 dark:bg-amber-900/10' => $subscriber->cancelled])>
                            <td class="px-5 py-4">
                                <a href="mailto:{{ $subscriber->email }}" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                    {{ $subscriber->email }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                @if ($subscriber->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-slate-300">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-500 dark:text-slate-400">
                                {{ $subscriber->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    @if ($subscriber->cancelled)
                                        <x-admin.button variant="secondary" wire:click="restore({{ $subscriber->id }})">Restore</x-admin.button>
                                    @else
                                        <x-admin.button variant="secondary" wire:click="toggleActive({{ $subscriber->id }})">
                                            {{ $subscriber->is_active ? 'Deactivate' : 'Activate' }}
                                        </x-admin.button>
                                        <x-admin.button variant="danger" wire:click="delete({{ $subscriber->id }})" wire:confirm="Cancel this subscriber? Kept in admin backup.">
                                            Cancel
                                        </x-admin.button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                                No subscribers yet. They will appear here when someone joins from the newsletter section on your site.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $subscribers->links() }}</div>
</div>
