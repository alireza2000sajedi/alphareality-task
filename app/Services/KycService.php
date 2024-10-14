<?php

namespace App\Services;

use App\Repositories\KycRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class KycService
{
    protected KycRepository $kycRepository;
    protected FileStorageService $fileStorageService;

    public function __construct(KycRepository $kycRepository, FileStorageService $fileStorageService)
    {
        $this->kycRepository = $kycRepository;
        $this->fileStorageService = $fileStorageService;
    }

    public function storeKyc(Request $request): ?Model
    {
        DB::beginTransaction();
        try {
            $path = $this->fileStorageService->storeImage($request->file('selfie_image'), $request->national_code);

            $kyc = $this->kycRepository->storeKycData([
                'national_code' => $request->national_code,
                'birth_date' => $request->birth_date,
                'selfie_image_path' => $path,
            ]);

            DB::commit();

            return $kyc;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e);

            return null; // Return null instead of false for better type consistency
        }
    }

    public function getKyc(string $nationalCode): Model
    {
        return $this->kycRepository->findByNationalCodeOnCache($nationalCode);
    }
}
