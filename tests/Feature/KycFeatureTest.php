<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Kyc;

class KycFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store(): void
    {
        Storage::fake('public');

        $nationalCode = generateValidNationalCode();
        $kycData = [
            'national_code' => $nationalCode,
            'birth_date' => '1990-01-01',
            'selfie_image' => UploadedFile::fake()->image('selfie.jpg')->size(500),
        ];

        $response = $this->postJson(route('kyc.store'), $kycData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'national_code', 'birth_date', 'selfie_image'],
            ]);

        $files = Storage::disk('public')->files("images/{$nationalCode}");
        $this->assertCount(1, $files);

        $uploadedFile = $files[0];
        $this->assertTrue(
            preg_match('/\.(jpg|jpeg|png)$/', $uploadedFile) === 1,
            "Uploaded file is not a valid image type."
        );
    }

    public function test_can_not_store_invalid(): void
    {
        $response = $this->postJson(route('kyc.store'));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['national_code', 'birth_date', 'selfie_image']);
    }

    public function test_can_show(): void
    {
        $kyc = Kyc::factory()->create()->toArray();

        $response = $this->getJson(route('kyc.show', $kyc['national_code']));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'national_code', 'birth_date', 'selfie_image'],
            ]);
    }

    public function test_cannot_show(): void
    {
        $response = $this->getJson(route('kyc.show', 9999));

        $response->assertStatus(404);
    }
}
