<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'name'];

    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function Responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class);
    }

    public function Employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

}
