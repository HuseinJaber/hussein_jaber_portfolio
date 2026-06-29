<div>
    <div class="mb-6">
        <a href="{{ route('admin.projects') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline dark:text-indigo-400">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to projects
        </a>
        <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $this->pageTitle() }}</h2>
    </div>

    <x-admin.card>
        @include('livewire.admin.partials.project-form', [
            'submitLabel' => $editingId ? 'Update project' : 'Create project',
        ])
    </x-admin.card>
</div>
