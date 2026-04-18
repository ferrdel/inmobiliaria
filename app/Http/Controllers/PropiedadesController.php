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
        // Validamos todos los campos de una vez
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

        // Procesamos las imágenes antes de guardar
        $rutasImagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $archivo) {
                $ruta = $archivo->store('propiedades', 'public');
                $rutasImagenes[] = $ruta;
            }
        }

        // Preparamos el array final de datos
        $data['user_id'] = auth()->id();
        $data['imagenes_path'] = $rutasImagenes; // Se guardará como JSON por el cast del modelo

        // Creamos la propiedad una SOLA VEZ
        Propiedad::create($data);

        // Redireccionamos (El return final)
        return redirect()->route('dashboard')->with('success', 'Propiedad creada con éxito.');
    }

        // Muestra el formulario con los datos cargados
    public function edit(Propiedad $propiedad)
    {
        // Solo el admin o el creador podrían editar (opcional, según tu lógica)
        return view('propiedades.edit', compact('propiedad'));
    }

    // Procesa la actualización
    public function update(Request $request, Propiedad $propiedad)
    {
        // REQUISITO DE PERFIL: Si no es admin, protegemos el precio
        if (auth()->user()->role !== 'admin') {
            // Eliminamos el precio del request para que no se actualice si un operario lo intenta
            $request->request->remove('precio');
        }

        // Validación
        $data = $request->validate([
            'nombre_titulo' => 'required|string|max:255',
            'tipo'          => 'required|string',
            'direccion'     => 'required|string',
            'precio'        => 'sometimes|required|numeric', // 'sometimes' porque puede no venir si es operario
            'descripcion'   => 'required|string',
            'estado'        => 'required|string',
            'superficie_m2' => 'required|numeric',
            'ambientes'     => 'required|integer',
        ]);

        // Actualizamos los datos
        $propiedad->update($data);

        return redirect()->route('dashboard')->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        //Buscamos la propiedad
        //$propiedad = Propiedad::findOrFail($propiedad);

        // 2. Verificamos que sea ADMIN (Requisito de seguridad)
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar propiedades.');
        }

        // 3. Eliminamos (Esto disparará automáticamente el evento 'deleted' de tu Auditoría)
        $propiedad->delete();

        // 4. Redirigimos al dashboard con un mensaje
        return redirect()->route('dashboard')->with('success', 'Propiedad eliminada correctamente.');
    }
}


