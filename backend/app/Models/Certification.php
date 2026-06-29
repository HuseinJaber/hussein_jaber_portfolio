<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certification extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $appends = ['has_credential_pdf', 'credential_pdf_url'];

    protected $casts = [
        'is_published' => 'boolean',
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->notCancelled();
    }

    public function getHasCredentialPdfAttribute(): bool
    {
        return filled($this->credential_file);
    }

    public function getCredentialPdfUrlAttribute(): ?string
    {
        if (! $this->credential_file) {
            return null;
        }

        return url("/api/certifications/{$this->id}/credential");
    }

    public function deleteCredentialFile(): void
    {
        if (! $this->credential_file) {
            return;
        }

        Storage::disk('certifications')->delete(basename($this->credential_file));
    }
}
