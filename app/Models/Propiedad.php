<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Importamos el modelo User para la relación
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Auditoria;

class Propiedad extends Model
{
    use HasFactory;

    protected $table = 'propiedades';

    protected $fillable = [
        'nombre_titulo',
        'tipo',
        'direccion',
        'precio',
        'descripcion',
        'estado',
        'imagenes_path',
        'superficie_m2',
        'ambientes',
        'user_id',
    ];

    /**
     * Obtener el usuario (responsable) que cargó esta propiedad.
     * Esto nos permite hacer: $propiedad->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'imagenes_path' => 'array',
    ];

    protected static function booted()
    {
        // Cuando se CREA una propiedad
        static::created(function ($propiedad) {
            Auditoria::create([
                'user_id' => auth()->id(), // El usuario logueado
                'propiedad_id' => $propiedad->id,
                'accion' => 'crear',
                'valores_nuevos' => $propiedad->toArray(), // Guardamos todo el registro nuevo
            ]);
        });

        //  Cuando se EDITAR una propiedad (el más complejo)
        static::updating(function ($propiedad) {
            // Obtenemos qué campos cambiaron realmente
            $cambios = $propiedad->getDirty();
            
            if (!empty($cambios)) {
                $valoresAnteriores = [];
                $valoresNuevos = [];

                foreach ($cambios as $campo => $valorNuevo) {
                    // Ignoramos campos técnicos
                    if(in_array($campo, ['updated_at', 'created_at'])) continue;

                    // getOriginal() nos da el valor antes del cambio
                    $valoresAnteriores[$campo] = $propiedad->getOriginal($campo);
                    $valoresNuevos[$campo] = $valorNuevo;
                }

                if(!empty($valoresNuevos)) {
                    Auditoria::create([
                        'user_id' => auth()->id(),
                        'propiedad_id' => $propiedad->id,
                        'accion' => 'editar',
                        'valores_anteriores' => $valoresAnteriores,
                        'valores_nuevos' => $valoresNuevos,
                    ]);
                }
            }
        });

        // Cuando se ELIMINA una propiedad
        static::deleted(function ($propiedad) {
            Auditoria::create([
                'user_id' => auth()->id(),
                'propiedad_id' => $propiedad->id,
                'accion' => 'eliminar',
                'valores_anteriores' => $propiedad->toArray(), // Guardamos qué eliminamos
            ]);
        });
    }
}