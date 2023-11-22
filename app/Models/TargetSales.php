<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetSales extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $attributes = [
        'januari' => 1500,
        'februari' => 1500,
        'maret' => 1500,
        'april' => 1500,
        'mei' => 1500,
        'juni' => 1500,
        'juli' => 1500,
        'agustus' => 1500,
        'september' => 1500,
        'oktober' => 1500,
        'november' => 1500,
        'desember' => 1500,
    ];
}
