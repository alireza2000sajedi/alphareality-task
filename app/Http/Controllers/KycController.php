<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKycRequest;
use App\Http\Resources\KycResource;
use App\Services\FileStorageService;
use App\Services\KycService;
use Ars\Responder\Facades\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    private KycService $kycService;
    private FileStorageService $fileStorageService;

    public function __construct(KycService $kycService, FileStorageService $fileStorageService)
    {
        $this->kycService = $kycService;
        $this->fileStorageService = $fileStorageService;
    }

    public function store(StoreKycRequest $request): JsonResponse
    {
        $result = DB::transaction(function () use ($request) {
            $nationalCode = $request->get('national_code');
            $birthDate = $request->get('birth_date');
            //Save file
            $path = $this->fileStorageService->storeImage($request->file('selfie_image'), $nationalCode);

            return $this->kycService->storeKyc([
                'national_code'     => $nationalCode,
                'birth_date'        => $birthDate,
                'selfie_image_path' => $path,
            ]);
        });


        if (!$result) {
            return Responder::internalError();
        }

        $data = KycResource::make($result);

        return Responder::created($data);
    }

    public function show(string $nationalCode): JsonResponse
    {
        $result = $this->kycService->getKyc($nationalCode);

        $data = KycResource::make($result);

        return Responder::ok($data);
    }
}
