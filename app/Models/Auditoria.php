<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id', 'propiedad_id', 'accion', 
        'valores_anteriores', 'valores_nuevos'
    ];

    // Para que Laravel maneje los JSON automáticamente como arrays de PHP
    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    // Relación para saber quién hizo el cambio: $auditoria->user->name
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}