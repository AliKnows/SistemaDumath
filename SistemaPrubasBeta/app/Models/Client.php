<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'second_last_name',
        'document_number',
        'phone_number',
        'email',
        'address',
        'birth_date',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
