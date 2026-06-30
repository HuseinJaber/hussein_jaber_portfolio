<form wire:submit="save" class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-3">
        <x-admin.card title="Identity" class="lg:col-span-2">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input label="Full name" name="name" wire:model="name" />
                <x-admin.input label="Title / Role" name="title" wire:model="title" />
                <div class="sm:col-span-2">
                    <x-admin.input label="Headline (hero tagline)" name="headline" wire:model="headline" />
                </div>
                <div class="sm:col-span-2">
                    <x-admin.textarea label="Short bio" name="bio" wire:model="bio" rows="3" />
                </div>
                <div class="sm:col-span-2">
                    <x-admin.textarea label="About (long form)" name="about" wire:model="about" rows="6" />
                </div>
            </div>
        </x-admin.card>

        <div class="space-y-6">
            <x-admin.card title="Contact">
                <div class="space-y-4">
                    <x-admin.input label="Email" name="email" type="email" wire:model="email" />
                    <x-admin.input label="Phone" name="phone" wire:model="phone" />
                    <x-admin.input label="Location" name="location" wire:model="location" />
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="available_for_work" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Available for work
                    </label>
                </div>
            </x-admin.card>

            <x-admin.card title="CV buttons (hero)">
                <div class="space-y-4">
                    <x-admin.input
                        label="CV PDF path or URL"
                        name="resume_url"
                        wire:model="resume_url"
                        placeholder="/files/hussein-jaber-cv.pdf"
                    />
                    <p class="text-xs text-slate-500">
                        Upload the PDF to your server (e.g. <code class="rounded bg-slate-100 px-1">public/files/</code> on the API host) and enter the path here.
                    </p>
                    <x-admin.input
                        label="Download button text"
                        name="cv_download_label"
                        wire:model="cv_download_label"
                        placeholder="Download CV"
                    />
                    <x-admin.input
                        label="Custom CV page button text"
                        name="cv_view_label"
                        wire:model="cv_view_label"
                        placeholder="View custom CV"
                    />
                    <p class="text-xs text-slate-500">
                        The custom CV button always links to the live résumé page on your site (<code class="rounded bg-slate-100 px-1">/cv</code>).
                    </p>
                </div>
            </x-admin.card>

            <x-admin.card title="Stats">
                <div class="grid grid-cols-3 gap-3">
                    <x-admin.input label="Years" name="years_experience" type="number" wire:model="years_experience" />
                    <x-admin.input label="Projects" name="projects_completed" type="number" wire:model="projects_completed" />
                    <x-admin.input label="Clients" name="happy_clients" type="number" wire:model="happy_clients" />
                </div>
            </x-admin.card>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <x-admin.button type="submit">
            <span wire:loading.remove wire:target="save">Save changes</span>
            <span wire:loading wire:target="save">Saving…</span>
        </x-admin.button>
    </div>
</form>
