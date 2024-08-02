<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'name', 'icon'];

    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function Employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
