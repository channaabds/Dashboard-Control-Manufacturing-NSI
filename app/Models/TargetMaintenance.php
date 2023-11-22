<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetMaintenance extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $attributes = [
        'target_maintenance' => 2750,
    ];
}
