<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificationCredentialController extends Controller
{
    public function show(int $id): BinaryFileResponse
    {
        $certification = Certification::published()->findOrFail($id);

        if (! $certification->credential_file) {
            abort(404);
        }

        $filename = basename($certification->credential_file);
        if ($filename !== $certification->credential_file) {
            abort(404);
        }

        $disk = Storage::disk('certifications');
        if (! $disk->exists($filename)) {
            abort(404);
        }

        $path = $disk->path($filename);
        $mime = mime_content_type($path) ?: '';
        if (! in_array($mime, ['application/pdf', 'application/x-pdf'], true)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.Str::slug($certification->title).'-certificate.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
