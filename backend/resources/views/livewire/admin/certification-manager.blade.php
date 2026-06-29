<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-admin.cancelled-toolbar :count="$certifications->count()" label="certifications — drag to reorder" />
        @unless ($showCancelled)
        <x-admin.button wire:click="create">+ Add certification</x-admin.button>
        @endunless
    </div>

    <x-admin.sortable-list method="updateSortOrder" class="space-y-3">
        @foreach ($certifications as $cert)
            <div wire:key="cert-{{ $cert->id }}" data-sort-id="{{ $cert->id }}" @class(['rounded-xl border p-5', 'border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-900/10' => $cert->cancelled, 'border-slate-200 bg-white dark:border-white/10 dark:bg-slate-900' => ! $cert->cancelled])>
                <div class="flex items-start gap-3">
                    @if (! $cert->cancelled)<x-admin.sort-handle />@endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold">{{ $cert->title }}</p>
                                <p class="text-xs text-slate-400">{{ $cert->issuer }}@if($cert->issued_at) · {{ $cert->issued_at }}@endif</p>
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                                    @if ($cert->has_credential_pdf)
                                        <a href="{{ $cert->credential_pdf_url }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline dark:text-indigo-400">View PDF ↗</a>
                                        <button type="button" wire:click="removePdf({{ $cert->id }})" wire:confirm="Remove the uploaded PDF?" class="text-rose-600 hover:underline">Remove PDF</button>
                                    @else
                                        <span class="text-amber-600 dark:text-amber-400">No PDF uploaded yet</span>
                                    @endif
                                    @if ($cert->credential_url)
                                        <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener" class="text-slate-400 hover:underline">Legacy URL ↗</a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                @if ($cert->is_published && ! $cert->cancelled)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Live</span>
                                @endif
                                @if ($cert->cancelled)
                                    <button wire:click="restore({{ $cert->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-white/5">Restore</button>
                                @else
                                    <button wire:click="edit({{ $cert->id }})" class="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-white/5">{{ $editingId === $cert->id ? 'Editing…' : 'Edit' }}</button>
                                    <button wire:click="delete({{ $cert->id }})" wire:confirm="Cancel this certification?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5">Cancel</button>
                                @endif
                            </div>
                        </div>
                        @if ($editingId === $cert->id)
                            <form wire:submit="save" class="mt-4 grid gap-4 border-t border-slate-200 pt-4 dark:border-white/10 sm:grid-cols-2">
                                <x-admin.input label="Title" name="title" wire:model="title" />
                                <x-admin.input label="Issuer" name="issuer" wire:model="issuer" />
                                <x-admin.input label="Issued" name="issued_at" wire:model="issued_at" placeholder="May 2023" />
                                <div class="sm:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Certificate PDF</label>
                                    <input type="file" accept="application/pdf,.pdf" wire:model="credential_pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/40 dark:file:text-indigo-300" />
                                    @error('credential_pdf') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <x-admin.input label="Legacy credential URL (admin only)" name="credential_url" wire:model="credential_url" placeholder="https://..." />
                                </div>
                                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published on portfolio</label>
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
        <x-admin.card title="New certification" class="mt-4">
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Title" name="title" wire:model="title" />
                <x-admin.input label="Issuer" name="issuer" wire:model="issuer" />
                <x-admin.input label="Issued" name="issued_at" wire:model="issued_at" placeholder="May 2023" />
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Certificate PDF</label>
                    <input type="file" accept="application/pdf,.pdf" wire:model="credential_pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/40 dark:file:text-indigo-300" />
                    @error('credential_pdf') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <x-admin.input label="Legacy credential URL (admin only)" name="credential_url" wire:model="credential_url" placeholder="https://..." />
                </div>
                <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published on portfolio</label>
                <div class="flex gap-3 sm:col-span-2">
                    <x-admin.button type="submit">Create</x-admin.button>
                    <x-admin.button type="button" variant="secondary" wire:click="cancel">Close</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    @endif
</div>
