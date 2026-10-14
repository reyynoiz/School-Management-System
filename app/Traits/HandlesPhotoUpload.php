<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HandlesPhotoUpload
{
    /**
     * Simpan foto ke public/uploads/{folder} dengan nama YmdHis-nama-file.ext
     */
    protected function storePhoto(UploadedFile $file, string $folder): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'photo';
        $extension = $file->guessExtension() ?: 'jpg';
        $fileName = now()->format('YmdHis') . '-' . $name . '.' . $extension;

        $file->move(public_path('uploads/' . $folder), $fileName);

        return $fileName;
    }

    protected function deletePhoto(?string $fileName, string $folder): void
    {
        if ($fileName) {
            File::delete(public_path('uploads/' . $folder . '/' . $fileName));
        }
    }
}