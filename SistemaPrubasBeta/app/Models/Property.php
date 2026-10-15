<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'created_by',
        'title',
        'description',
        'price',
        'currency',
        'ubicacion',
        'latitud',
        'longitud',
        'm2_terreno',
        'm2_construidos',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function propertyImages(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
