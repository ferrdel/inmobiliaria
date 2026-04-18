<?php

namespace App\Policies;

use App\Models\Propiedades;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropiedadPolicy
{
    /**
     * Determina si el usuario puede ver la lista de propiedades.
     * Ambos perfiles pueden ver.
     */
    public function viewAny(User $user): bool
    {
        return true; 
    }

    /**
     * Determina si el usuario puede crear una nueva propiedad.
     * Según la imagen, ambos ADMINISTRADOR y OPERARIO pueden.
     */
    public function create(User $user): bool
    {
        return true; 
    }

    /**
     * Determina si el usuario puede editar UNA propiedad.
     * Ambos pueden, pero la lógica del CAMPO PRECIO la haremos en el controlador/request.
     */
    public function update(User $user, Propiedades $propiedad): bool
    {
        // Ambos pueden editar la información general
        return true;
    }

    /**
     * Determina si el usuario puede eliminar UNA propiedad.
     * Según la imagen, SOLO EL ADMINISTRADOR puede.
     */
    public function delete(User $user, Propiedades $propiedades): bool
    {
        // El Operario NO puede eliminar propiedades
        return $user->role === 'admin';
    }

    /**
     * Permiso especial: ¿Puede editar el precio?
     * Según la imagen, SOLO EL ADMINISTRADOR puede editar el precio.
     */
    public function updatePrecio(User $user): bool
    {
        return $user->role === 'admin';
    }
}