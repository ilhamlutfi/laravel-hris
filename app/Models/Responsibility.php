<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Responsibility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['role_id', 'name'];

    public function Role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
