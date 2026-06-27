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
                    <x-admin.input label="Resume URL" name="resume_url" wire:model="resume_url" />
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="available_for_work" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Available for work
                    </label>
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
