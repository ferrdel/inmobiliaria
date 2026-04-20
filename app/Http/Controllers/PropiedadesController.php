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
        // Capturamos lo que el usuario escribió en los inputs
        $buscar = $request->input('buscar');
        $tipo = $request->input('tipo');
        $min_precio = $request->input('min_precio');
        $max_precio = $request->input('max_precio');

        // Iniciamos la consulta base
        $query = Propiedad::query();

        //  Filtramos por nombre/título si existe búsqueda
        if ($buscar) {
            $query->where('nombre_titulo', 'LIKE', "%{$buscar}%");
        }

        //  Filtramos por tipo si se seleccionó uno
        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        //  Filtramos por rango de precio si se especificaron ambos valores
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
        //Propiedad::create($data);

        // Redireccionamos (El return final)
        //return redirect()->route('dashboard')->with('success', 'Propiedad creada con éxito.');

        $propiedad = Propiedad::create($data);

        // NUEVO: Enviar el email
        // Aquí pon tu correo personal donde quieres recibir la prueba
        try {
            Mail::to('fernandoramirezdelgado@gmail.com')->send(new \App\Mail\MailPropiedadMailable($propiedad));
        } catch (\Exception $e) {
            // Si el mail falla, que la app no se rompa, solo logueamos el error
            dd("Error enviando mail: " . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Propiedad cargada y notificación enviada.');
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


