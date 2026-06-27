<div>
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $stat)
            <a href="{{ route($stat['route']) }}" wire:navigate
               class="group rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-5 shadow-sm transition hover:shadow-md">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold tracking-tight">{{ $stat['value'] }}</p>
                <span class="mt-3 inline-block h-1 w-10 rounded-full bg-{{ $stat['color'] }}-500"></span>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold">Latest messages</h2>
                @if ($unread)
                    <span class="rounded-full bg-rose-100 dark:bg-rose-900/40 px-2.5 py-0.5 text-xs font-medium text-rose-700 dark:text-rose-300">{{ $unread }} unread</span>
                @endif
            </div>
            <div class="mt-4 divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($latestMessages as $m)
                    <div class="flex items-start justify-between py-3">
                        <div>
                            <p class="font-medium">{{ $m->name }} @unless($m->is_read)<span class="ml-1 inline-block h-2 w-2 rounded-full bg-indigo-500"></span>@endunless</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $m->email }} — {{ \Illuminate\Support\Str::limit($m->message, 70) }}</p>
                        </div>
                        <span class="whitespace-nowrap text-xs text-slate-400">{{ $m->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-slate-400">No messages yet.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.messages') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-indigo-600 dark:text-indigo-400">View all messages →</a>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white">
            <h2 class="font-semibold">Welcome back 👋</h2>
            <p class="mt-2 text-sm text-indigo-100">Everything you manage here instantly powers your public portfolio through the API. Update your profile, add projects and watch your site reflect the changes.</p>
            <a href="{{ route('admin.projects') }}" wire:navigate class="mt-4 inline-flex items-center gap-1 rounded-lg bg-white/15 px-3 py-2 text-sm font-medium hover:bg-white/25">Add a project</a>
        </div>
    </div>
</div>
