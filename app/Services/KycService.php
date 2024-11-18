<?php

namespace App\Services;

use App\Models\Kyc;
use App\Repositories\KycRepository;

class KycService
{
    protected KycRepository $kycRepository;
    protected FileStorageService $fileStorageService;

    public function __construct(KycRepository $kycRepository, FileStorageService $fileStorageService)
    {
        $this->kycRepository = $kycRepository;
        $this->fileStorageService = $fileStorageService;
    }

    public function storeKyc(array $data): ?Kyc
    {
        return $this->kycRepository->storeKycData($data);
    }

    public function getKyc(string $nationalCode): ?Kyc
    {
        return $this->kycRepository->findByNationalCodeOnCache($nationalCode);
    }
}
