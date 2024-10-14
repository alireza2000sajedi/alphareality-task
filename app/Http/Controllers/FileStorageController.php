<?php

namespace App\Http\Controllers;

use App\Services\FileStorageService;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileStorageController extends Controller
{
    private FileStorageService $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function image(string $imagePath): BinaryFileResponse
    {
        if (!$this->fileStorageService->existsImage($imagePath)) {
            abort(404);
        }

        $filePath = $this->fileStorageService->getImagePath($imagePath);

        return response()->file($filePath);
    }
}
