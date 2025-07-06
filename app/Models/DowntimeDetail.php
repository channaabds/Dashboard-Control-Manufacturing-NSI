<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeDetail extends Model
{
  use HasFactory;

  protected $fillable = [
    'machine_repair_id',
    'month',
    'year',
    'downtime_duration',
    'start_downtime',
    'end_downtime'
  ];
}
