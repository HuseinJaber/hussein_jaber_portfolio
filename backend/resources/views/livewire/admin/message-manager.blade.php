<div>
    <x-admin.cancelled-toolbar :count="$messages->total()" label="contact messages" class="mb-4" />

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
                                    @if ($m->cancelled)<span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Cancelled</span>@endif
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
                        @unless ($this->selected->cancelled)
                            <x-admin.button wire:click="openReply">Reply</x-admin.button>
                            <x-admin.button variant="secondary" wire:click="markUnread({{ $this->selected->id }})">Mark unread</x-admin.button>
                            <x-admin.button variant="danger" wire:click="delete({{ $this->selected->id }})" wire:confirm="Cancel this message? It will be kept in admin backup.">Cancel</x-admin.button>
                        @else
                            <x-admin.button variant="secondary" wire:click="restore({{ $this->selected->id }})">Restore</x-admin.button>
                        @endunless
                    </div>
                </x-admin.card>
            @else
                <x-admin.card>
                    <p class="py-10 text-center text-sm text-slate-400">Select a message to read it.</p>
                </x-admin.card>
            @endif
        </div>
    </div>

    @if ($showReplyModal && $this->selected)
        <x-admin.modal title="Reply to {{ $this->selected->name }}" max-width="xl" close-action="closeReplyModal">
            <p class="mb-4 text-sm text-slate-500 dark:text-slate-400">
                Sends to <strong class="text-slate-700 dark:text-slate-200">{{ $this->selected->email }}</strong>
                from your portfolio mail settings. They can reply directly to your inbox.
            </p>
            <form wire:submit="sendReply" class="space-y-4">
                <x-admin.input label="Subject" name="replySubject" wire:model="replySubject" />
                <x-admin.textarea label="Message" name="replyBody" wire:model="replyBody" rows="8" placeholder="Write your reply…" />
                <div class="flex gap-3">
                    <x-admin.button type="submit">
                        <span wire:loading.remove wire:target="sendReply">Send reply</span>
                        <span wire:loading wire:target="sendReply">Sending…</span>
                    </x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="closeReplyModal">Cancel</x-admin.button>
                </div>
            </form>
        </x-admin.modal>
    @endif
</div>
