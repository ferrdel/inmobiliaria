<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propiedad;
use Illuminate\Support\Facades\Auth;
use App\Mail\MailPropiedadMailable;
use Illuminate\Support\Facades\Mail;

class PropiedadesController extends Controller
{
    public function index(Request $request)
    {
        // Capturamos lo que viene de los inputs
        $buscar = $request->input('buscar');
        $tipo = $request->input('tipo');
        $min_precio = $request->input('min_precio');
        $max_precio = $request->input('max_precio');

        // Iniciamos la consulta base
        $query = Propiedad::query();

        //  Filtramos por nombre si existe
        if ($buscar) {
            $query->where('nombre_titulo', 'LIKE', "%{$buscar}%");
        }

        //  Filtramos por tipo si se especifico
        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        //  Filtramos por rango de precio si cargo ambos valores
        if ($min_precio && $max_precio) {
            $query->whereBetween('precio', [$min_precio, $max_precio]);
        } elseif ($min_precio) {
            $query->where('precio', '>=', $min_precio);
        } elseif ($max_precio) {
            $query->where('precio', '<=', $max_precio);
        }

        // Obtenemos los resultados finales
        $propiedades = $query->with('user')->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('propiedades'));
    }

    public function create()
    {
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

        // Procesamos las imagenes antes de guardar
        $rutasImagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $archivo) {
                $ruta = $archivo->store('propiedades', 'public');
                $rutasImagenes[] = $ruta;
            }
        }

        // Preparamos el array final de datos
        $data['user_id'] = auth()->id();
        $data['imagenes_path'] = $rutasImagenes; 

        $propiedad = Propiedad::create($data);

        // Enviar el email de notificacion 
        try {
            Mail::to('fernandoramirezdelgado@gmail.com')->send(new \App\Mail\MailPropiedadMailable($propiedad));
        } catch (\Exception $e) {
            // Si el mail falla, logueamos el error
            dd("Error enviando mail: " . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Propiedad cargada y notificación enviada.');
    }

    // Muestra el formulario con los datos cargados
    public function edit(Propiedad $propiedad)
    {
        return view('propiedades.edit', compact('propiedad'));
    }

    // Procesa la actualizacion
    public function update(Request $request, Propiedad $propiedad)
    {
        // REQUISITO DE PERFIL: Si no es admin, protegemos el precio
        if (auth()->user()->role !== 'admin') {
            $request->request->remove('precio');
        }

        // Validacion
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

        // Verificamos que sea ADMIN
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar propiedades.');
        }

        // Eliminamos. Dispara el evento 'deleted' de Auditoria
        $propiedad->delete();

        //Redirigimos al dashboard con un mensaje
        return redirect()->route('dashboard')->with('success', 'Propiedad eliminada correctamente.');
    }
}


