<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'national_code',
        'birth_date',
        'selfie_image_path',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];
}
