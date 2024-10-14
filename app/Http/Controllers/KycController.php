<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKycRequest;
use App\Http\Resources\KycResource;
use App\Services\KycService;
use Ars\Responder\Facades\Responder;
use Illuminate\Http\JsonResponse;

class KycController extends Controller
{
    private KycService $kycService;

    public function __construct(KycService $kycService)
    {
        $this->kycService = $kycService;
    }

    public function store(StoreKycRequest $request): JsonResponse
    {
        $result = $this->kycService->storeKyc($request);

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
