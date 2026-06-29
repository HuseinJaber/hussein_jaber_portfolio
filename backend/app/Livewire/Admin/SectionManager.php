<?php

namespace App\Livewire\Admin;

use App\Models\Profile;
use App\Support\PortfolioSections;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Sections')]
class SectionManager extends Component
{
    /** @var array<string, bool> */
    public array $sections = [];

    /** @var list<string> */
    public array $sectionOrder = [];

    /** @var array<string, array{nav_label: string, eyebrow: string, title: string, subtitle: string|null, align: string}> */
    public array $sectionCopy = [];

    public ?string $expandedKey = null;

    public function mount(): void
    {
        $profile = Profile::current();
        $this->sections = $profile->sections;
        $this->sectionOrder = $profile->section_order;
        $this->sectionCopy = $profile->section_copy;
    }

    public function save(): void
    {
        Profile::current()->update([
            'sections' => PortfolioSections::sanitize($this->sections),
            'section_order' => PortfolioSections::sanitizeOrder($this->sectionOrder),
            'section_copy' => PortfolioSections::sanitizeCopy($this->sectionCopy),
        ]);

        $profile = Profile::current();
        $this->sections = $profile->sections;
        $this->sectionOrder = $profile->section_order;
        $this->sectionCopy = $profile->section_copy;
        $this->expandedKey = null;

        session()->flash('status', 'Section settings updated. Changes are live on your site.');
    }

    /** @param  list<string>  $orderedKeys */
    public function updateSectionOrder(array $orderedKeys): void
    {
        $this->sectionOrder = PortfolioSections::sanitizeOrder($orderedKeys);

        Profile::current()->update([
            'section_order' => $this->sectionOrder,
        ]);

        session()->flash('status', 'Section order updated.');
    }

    public function toggleCopy(string $key): void
    {
        $this->expandedKey = $this->expandedKey === $key ? null : $key;
    }

    public function enableAll(): void
    {
        foreach (array_keys($this->sections) as $key) {
            $this->sections[$key] = true;
        }

        $this->save();
    }

    public function render()
    {
        $definitions = PortfolioSections::definitions();
        $orderedDefinitions = [];

        foreach ($this->sectionOrder as $key) {
            if (isset($definitions[$key])) {
                $orderedDefinitions[$key] = $definitions[$key];
            }
        }

        foreach ($definitions as $key => $meta) {
            if (! isset($orderedDefinitions[$key])) {
                $orderedDefinitions[$key] = $meta;
            }
        }

        return view('livewire.admin.section-manager', [
            'orderedDefinitions' => $orderedDefinitions,
        ]);
    }
}
