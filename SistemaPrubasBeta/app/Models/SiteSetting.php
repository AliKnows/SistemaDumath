<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_url',
        'imagen_fondo_url',
        'color_primario',
        'color_secundario',
        'texto_bienvenida',
        'telefono_contacto',
        'whatsapp',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
    ];
}
