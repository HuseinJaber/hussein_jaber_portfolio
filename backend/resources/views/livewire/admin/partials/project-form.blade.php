<form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-admin.input label="Title" name="title" wire:model="title" />
    </div>

    <x-admin.multi-select
        label="Categories"
        :items="$categories"
        :selected-ids="$category_ids"
        wire-model="category_ids"
        :manage-route="route('admin.project-categories')"
        manage-label="Manage"
        placeholder="Choose categories…"
    />

    <x-admin.multi-select
        label="Tech stack"
        :items="$techStacks"
        :selected-ids="$tech_stack_ids"
        wire-model="tech_stack_ids"
        :manage-route="route('admin.tech-stacks')"
        manage-label="Manage"
        placeholder="Choose stacks…"
    />

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Your contribution</label>
        <p class="mb-3 text-xs text-slate-500 dark:text-slate-400">What you worked on for this project — select all that apply.</p>
        <div class="flex flex-wrap gap-2">
            @foreach ($contributionOptions as $key => $label)
                <label wire:key="contribution-{{ $key }}" class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm transition hover:border-indigo-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-indigo-500/50">
                    <input
                        type="checkbox"
                        value="{{ $key }}"
                        wire:model="contribution_areas"
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('contribution_areas') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        @error('contribution_areas.*') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Engagement</label>
        <select wire:model="engagement_type" class="w-full rounded-lg border-slate-300 text-sm dark:border-white/10 dark:bg-white/5">
            <option value="development">Development (built from scratch)</option>
            <option value="support">Support (maintenance / after launch)</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Work context</label>
        <select wire:model.live="work_context" class="w-full rounded-lg border-slate-300 text-sm dark:border-white/10 dark:bg-white/5">
            <option value="none">Not linked</option>
            <option value="company">Company / experience</option>
            <option value="freelance">Freelance</option>
        </select>
    </div>
    @if ($work_context === 'company')
        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Experience / company</label>
            <select wire:model="experience_id" class="w-full rounded-lg border-slate-300 text-sm dark:border-white/10 dark:bg-white/5">
                <option value="">Select a company…</option>
                @foreach ($experiences as $exp)
                    <option value="{{ $exp->id }}">{{ $exp->company }} — {{ $exp->role }}</option>
                @endforeach
            </select>
            @error('experience_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    @endif
    <div class="sm:col-span-2">
        <x-admin.input label="Short description" name="short_description" wire:model="short_description" />
    </div>
    <div class="sm:col-span-2">
        <x-admin.textarea label="Full description" name="description" wire:model="description" rows="4" />
    </div>
    <x-admin.input label="Cover image URL" name="cover_image" wire:model="cover_image" />
    <x-admin.input label="Live URL" name="live_url" wire:model="live_url" />
    <x-admin.input label="Source URL" name="source_url" wire:model="source_url" />
    <x-admin.input label="Client" name="client" wire:model="client" />
    <x-admin.input label="Year" name="year" type="number" wire:model="year" />
    <div class="flex items-center gap-6 sm:col-span-2">
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_featured" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Featured</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Published</label>
    </div>
    <div class="flex gap-3 sm:col-span-2">
        <x-admin.button type="submit">{{ $submitLabel }}</x-admin.button>
        <a href="{{ route('admin.projects') }}" wire:navigate>
            <x-admin.button type="button" variant="secondary">Cancel</x-admin.button>
        </a>
    </div>
</form>
