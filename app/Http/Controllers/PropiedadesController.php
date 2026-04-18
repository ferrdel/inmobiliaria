<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propiedad;
use Illuminate\Support\Facades\Auth;

class PropiedadesController extends Controller
{
    public function index()
    {
        $propiedades = Propiedad::with('user')->get();
        return view('dashboard', compact('propiedades'));
    }

    public function create()
    {
        // Solo debe haber UNA función create para mostrar el formulario
        return view('propiedades.create');
    }

    public function store(Request $request)
    {
        // 1. Validamos todos los campos de una vez
        $data = $request->validate([
            'nombre_titulo' => 'required|string|max:255',
            'tipo'          => 'required|string',
            'direccion'     => 'required|string',
            'precio'        => 'required|numeric',
            'descripcion'   => 'required|string',
            'estado'        => 'required|string',
            'superficie_m2' => 'required|numeric',
            'ambientes'     => 'required|integer',
            'imagenes.*'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Procesamos las imágenes antes de guardar
        $rutasImagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $archivo) {
                $ruta = $archivo->store('propiedades', 'public');
                $rutasImagenes[] = $ruta;
            }
        }

        // 3. Preparamos el array final de datos
        $data['user_id'] = auth()->id();
        $data['imagenes_path'] = $rutasImagenes; // Se guardará como JSON por el cast del modelo

        // 4. Creamos la propiedad una SOLA VEZ
        Propiedad::create($data);

        // 5. Redireccionamos (El return final)
        return redirect()->route('dashboard')->with('success', 'Propiedad creada con éxito.');
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        $this->authorize('update', $propiedad);

        if (auth()->user()->cannot('updatePrecio', Propiedad::class)) {
            $request->request->remove('precio'); 
        }

        $data = $request->validate([
            'nombre_titulo' => 'required|string',
            'precio'        => 'sometimes|required|numeric',
            // Agrega aquí los demás campos que permitas editar
        ]);

        $propiedad->update($data);
        return redirect()->route('dashboard')->with('success', 'Propiedad actualizada.');
    }

    public function destroy(Propiedad $propiedad)
    {
        $this->authorize('delete', $propiedad);
        $propiedad->delete();
        return redirect()->route('dashboard')->with('success', 'Propiedad eliminada.');
    }
}


