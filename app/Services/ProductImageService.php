<?php
namespace App\Services;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ProductImageService
{
    public function store(UploadedFile $file, string $directory = 'products'): string
    {
        $path = $file->storePublicly($directory, ['disk' => 'public']);


        return $path;
    }


    public function replace(?string $existingPath, UploadedFile $file, string $directory = 'products'): string
    {
        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }


        return $this->store($file, $directory);
    }


    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
