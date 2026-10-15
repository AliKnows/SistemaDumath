<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorVisitante extends Model
{
    use HasFactory;

    protected $table = 'JugadorVisitante';

    protected $fillable = [
        'nombre',
        'edad',
        'rol',
        'contacto',
        'opinion',
    ];
}
