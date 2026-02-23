<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesPublicImage
{
    protected function storePublicImage(UploadedFile $file, string $dir): string
    {
        return $file->store($dir, 'public');
    }

    protected function deletePublicImage(?string $path): void
    {
        if (!$path) return;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}