<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kyc>
 */
class KycFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Storage::fake('public');
        $nationalCode = generateValidNationalCode();
        return [
            'national_code' => $nationalCode,
            'birth_date' => $this->faker->date('Y-m-d'),
            'selfie_image_path' => 'images/' . $nationalCode . '/' . UploadedFile::fake()->image('selfie.jpg')->store('images', 'public'),
        ];
    }
}
