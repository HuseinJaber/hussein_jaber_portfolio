<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Certification;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
#[Title('Certifications')]
class CertificationManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords, WithFileUploads;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $title = '';

    public string $issuer = '';

    public string $issued_at = '';

    public string $credential_url = '';

    public $credential_pdf = null;

    public bool $is_published = true;

    protected function sortableModelClass(): string
    {
        return Certification::class;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'issuer' => 'required|string|max:120',
            'issued_at' => 'nullable|string|max:40',
            'credential_url' => 'nullable|url|max:500',
            'credential_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'is_published' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->creatingNew = true;
    }

    public function edit(int $id): void
    {
        $cert = Certification::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $cert->id;
        $this->title = $cert->title;
        $this->issuer = $cert->issuer;
        $this->issued_at = (string) $cert->issued_at;
        $this->credential_url = (string) $cert->credential_url;
        $this->is_published = $cert->is_published;
        $this->credential_pdf = null;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['credential_pdf']);

        $certification = $this->editingId
            ? Certification::findOrFail($this->editingId)
            : new Certification;

        if ($this->credential_pdf) {
            $certification->deleteCredentialFile();
            $filename = Str::uuid()->toString().'.pdf';
            $this->credential_pdf->storeAs('', $filename, 'certifications');
            $data['credential_file'] = $filename;
        }

        if (! $this->editingId) {
            $data['sort_order'] = Certification::nextSortOrder();
        }

        $certification->fill($data)->save();

        session()->flash('status', $this->editingId ? 'Certification updated.' : 'Certification created.');
        $this->resetForm();
    }

    public function removePdf(int $id): void
    {
        $certification = Certification::findOrFail($id);
        $certification->deleteCredentialFile();
        $certification->update(['credential_file' => null]);
        session()->flash('status', 'Certificate PDF removed.');
    }

    public function delete(int $id): void
    {
        Certification::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Certification cancelled.');
    }

    public function restore(int $id): void
    {
        Certification::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Certification restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'creatingNew', 'title', 'issuer', 'issued_at', 'credential_url', 'credential_pdf',
        ]);
        $this->is_published = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.certification-manager', [
            'certifications' => $this->cancelledQuery(Certification::query())->orderBy('sort_order')->get(),
        ]);
    }
}
