<?php

namespace App\Repositories;

use App\Models\Kyc;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KycRepository
{
    private const CACHE_KEY_NATIONAL_CODE = 'KycEntity:NationalCode:';

    public function storeKycData(array $data): Kyc
    {
        return Kyc::query()->create($data);
    }

    public function findByNationalCode(string $nationalCode): Kyc
    {
        return Kyc::query()->where('national_code', $nationalCode)->firstOrFail();
    }

    public function findByNationalCodeOnCache(string $nationalCode): Kyc
    {
        return Cache::remember(self::CACHE_KEY_NATIONAL_CODE . $nationalCode, now()->addDay(), function () use ($nationalCode) {
            return $this->findByNationalCode($nationalCode);
        });
    }
}
