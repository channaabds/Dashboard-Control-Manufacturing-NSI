<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineRepair extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['dataMesin'];

    public function dataMesin(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'mesin_id');
    }
}
