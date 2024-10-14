<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KycResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'national_code' => $this->national_code,
            'birth_date' => $this->birth_date,
            'selfie_image' => imageSiteUrl($this->selfie_image_path),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
