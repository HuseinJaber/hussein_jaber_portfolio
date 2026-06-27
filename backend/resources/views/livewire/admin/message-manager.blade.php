<div>
    <div class="grid gap-6 lg:grid-cols-5">
        <div class="lg:col-span-3">
            <x-admin.card class="!p-0 overflow-hidden">
                <div class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse ($messages as $m)
                        <button wire:key="msg-{{ $m->id }}" wire:click="select({{ $m->id }})"
                                class="flex w-full items-start justify-between gap-3 px-5 py-4 text-left transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $selectedId === $m->id ? 'bg-indigo-50 dark:bg-white/5' : '' }}">
                            <div class="min-w-0">
                                <p class="flex items-center gap-2 font-medium">
                                    {{ $m->name }}
                                    @unless ($m->is_read)<span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>@endunless
                                </p>
                                <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ $m->subject ?: $m->message }}</p>
                            </div>
                            <span class="whitespace-nowrap text-xs text-slate-400">{{ $m->created_at->diffForHumans() }}</span>
                        </button>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-slate-400">No messages yet.</p>
                    @endforelse
                </div>
            </x-admin.card>
            <div class="mt-4">{{ $messages->links() }}</div>
        </div>

        <div class="lg:col-span-2">
            @if ($this->selected)
                <x-admin.card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold">{{ $this->selected->name }}</p>
                            <a href="mailto:{{ $this->selected->email }}" class="text-sm text-indigo-500 hover:underline">{{ $this->selected->email }}</a>
                        </div>
                        <span class="text-xs text-slate-400">{{ $this->selected->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    @if ($this->selected->subject)
                        <p class="mt-4 font-medium">{{ $this->selected->subject }}</p>
                    @endif
                    <p class="mt-2 whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $this->selected->message }}</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="mailto:{{ $this->selected->email }}"><x-admin.button>Reply by email</x-admin.button></a>
                        <x-admin.button variant="secondary" wire:click="markUnread({{ $this->selected->id }})">Mark unread</x-admin.button>
                        <x-admin.button variant="danger" wire:click="delete({{ $this->selected->id }})" wire:confirm="Delete this message?">Delete</x-admin.button>
                    </div>
                </x-admin.card>
            @else
                <x-admin.card>
                    <p class="py-10 text-center text-sm text-slate-400">Select a message to read it.</p>
                </x-admin.card>
            @endif
        </div>
    </div>
</div>
