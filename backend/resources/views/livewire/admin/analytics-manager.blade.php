<div>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Anonymous visit tracking from your public Next.js site.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @foreach (['today' => 'Today', '7d' => '7 days', '30d' => '30 days', 'all' => 'All time'] as $key => $label)
                <button
                    wire:click="$set('period', '{{ $key }}')"
                    @class([
                        'rounded-lg px-3 py-1.5 text-sm font-medium transition',
                        'bg-indigo-600 text-white' => $period === $key,
                        'bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5' => $period !== $key,
                    ])
                >
                    {{ $label }}
                </button>
            @endforeach
            <x-admin.button variant="secondary" wire:click="clearOld" wire:confirm="Cancel analytics events older than 90 days? They will be kept in backup.">
                Clear old data
            </x-admin.button>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $stat)
            <x-admin.card>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($stat['value']) }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $stat['hint'] }}</p>
            </x-admin.card>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card>
                <h2 class="font-semibold">Section engagement</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Clicks on nav/anchor links and scroll-into-view on the home page.</p>

                <div class="mt-6 space-y-4">
                    @forelse ($sectionStats as $row)
                        @php
                            $total = $row->clicks + $row->views;
                            $width = round(($total / $maxSectionTotal) * 100);
                        @endphp
                        <div wire:key="section-{{ $row->section }}">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium capitalize">{{ str_replace('-', ' ', $row->section) }}</span>
                                <span class="text-slate-500 dark:text-slate-400">
                                    {{ $row->clicks }} clicks · {{ $row->views }} views
                                </span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm text-slate-400">No section activity yet. Visit the public site to generate data.</p>
                    @endforelse
                </div>
            </x-admin.card>
        </div>

        <div>
            <x-admin.card>
                <h2 class="font-semibold">Top referrers</h2>
                <ul class="mt-4 space-y-3">
                    @forelse ($topReferrers as $ref)
                        <li wire:key="ref-{{ md5($ref->referrer) }}" class="text-sm">
                            <p class="truncate font-medium" title="{{ $ref->referrer }}">{{ \Illuminate\Support\Str::limit($ref->referrer, 42) }}</p>
                            <p class="text-xs text-slate-400">{{ $ref->total }} visits</p>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-slate-400">No referrer data yet.</li>
                    @endforelse
                </ul>
            </x-admin.card>
        </div>
    </div>

    <div class="mt-8">
        <x-admin.card class="!p-0 overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-white/5">
                <h2 class="font-semibold">Recent activity</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3 font-medium">When</th>
                            <th class="px-5 py-3 font-medium">Event</th>
                            <th class="px-5 py-3 font-medium">Section</th>
                            <th class="px-5 py-3 font-medium">Page</th>
                            <th class="px-5 py-3 font-medium">Session</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse ($events as $event)
                            <tr wire:key="event-{{ $event->id }}" class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-3 text-slate-500 dark:text-slate-400">
                                    {{ $event->created_at->format('M j, g:i A') }}
                                </td>
                                <td class="px-5 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' => $event->event_type === 'page_view',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $event->event_type === 'section_view',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' => $event->event_type === 'section_click',
                                    ])>
                                        {{ $event->eventLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 capitalize">{{ $event->sectionLabel() }}</td>
                                <td class="px-5 py-3 font-mono text-xs">{{ $event->path }}</td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($event->session_id, 8, '') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">No events recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>
        <div class="mt-4">{{ $events->links() }}</div>
    </div>
</div>
