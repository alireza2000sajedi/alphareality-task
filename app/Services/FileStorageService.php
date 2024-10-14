<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    protected string $disk;

    public function __construct(string $disk = 'public')
    {
        $this->disk = $disk;
    }

    public function storeImage(UploadedFile $file, string $nationalCode): string
    {
        $uuid = Str::uuid()->toString();
        $time = now()->format('Y-m-d_H-i-s');
        $filePath = "{$nationalCode}/{$uuid}-{$time}." . $file->getClientOriginalExtension();
        $file->storeAs('images', $filePath, $this->disk);

        return $filePath;
    }

    public function getImagePath(string $imagePath): string
    {
        return storage_path("app/{$this->disk}/images/{$imagePath}");
    }

    public function existsImage(string $imagePath): bool
    {
        return Storage::disk($this->disk)->exists("images/{$imagePath}");
    }
}
