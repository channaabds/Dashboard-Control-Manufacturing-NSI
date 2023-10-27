<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryQuality extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'target_cam_ipqc' => 0,
        'target_cnc_ipqc' => 0,
        'target_mfg_ipqc' => 0,
        'target_cam_oqc' => 0,
        'target_cnc_oqc' => 0,
        'target_mfg_oqc' => 0,
        'aktual_cam_ipqc' => 0,
        'aktual_cnc_ipqc' => 0,
        'aktual_mfg_ipqc' => 0,
        'aktual_cam_oqc' => 0,
        'aktual_cnc_oqc' => 0,
        'aktual_mfg_oqc' => 0,
    ];
}
