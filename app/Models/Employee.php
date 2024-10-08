<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['team_id', 'role_id', 'name', 'email', 'gender', 'age', 'phone', 'photo', 'is_verified', 'verified_at'];

    public function Team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function Role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
