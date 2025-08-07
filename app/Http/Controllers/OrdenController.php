<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenView;

class OrdenController extends Controller
{
    public function index(Request $request)
    {
        $codigo = $request->query('codigo');
        $ordenes = [];

        if ($codigo) {
            $ordenes = OrdenView::where('nro_orden', 'ILIKE', $codigo)->get();
        }

        return view('home', compact('ordenes'));
    }

    public function descargarGuia($idOrden)
    {
        //buscar por $idOrden de la guia y obtener el campo "ruta_archivo" de la tabla  "oc_directas_guias"

        $ruta_archivo ="/storage/mgcp/ordenes-compra/guias/1232/T001-4323.pdf";
        $rutaLimpia = ltrim($ruta_archivo, '/storage/'); // al obtner la ruta del campo, considera storage al inicio pero al usar storage_path ya lo incluye , entonces aqui elimina la parte de "storage"
        $rutaArchivo = storage_path('app' . '/'.$rutaLimpia);
        $rutaArchivo = str_replace('orden_track', 'mgc', storage_path('app/' . $rutaLimpia)); // storage_path considera la ruta del proyecto pero como esta el archiv en otro directorio deberia remplazar orden_track por mgc

        if (file_exists($rutaArchivo)) {
            return response()->download($rutaArchivo);
        }else{
            return 'Archivo no encontrado en: ' . $rutaArchivo;
        }

    }
}
