<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'logo', 'email', 'phone', 'address', 'website'];

    public function Users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function Teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function Roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
