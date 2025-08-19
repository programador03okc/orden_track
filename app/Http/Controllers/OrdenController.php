<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\OrdenView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        // Buscar la orden usando el campo 'id' en la base de datos esquema / vista
        $orden = DB::table('orden_track.ordenes_view')
                    ->where('id', $idOrden)
                    ->first();

        if (!$orden) { //validacion de orden existente
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        // Determinar carpeta según tipo o prefijo del nro_orden
        if (strpos(strtolower($orden->nro_orden), 'directa') === 0 || $orden->tipo === 'directa') {
            $tipoCarpeta = 'directas';
        } elseif (strpos(strtolower($orden->nro_orden), 'ocam') === 0 || $orden->tipo === 'am') {
            $tipoCarpeta = 'propias';
        } else {
            return response()->json(['error' => 'Tipo de orden no reconocido'], 400);
        }

        // Ruta base absoluta apuntando a la carpeta mgc fuera de Laravel
        $basePath = 'C:/xampp/htdocs/mgc/storage/app/mgcp/ordenes-compra/guias/';

        // Construir la ruta completa a la carpeta con idOrden
        $carpeta = $basePath . $tipoCarpeta . '/' . $idOrden;

        if (!is_dir($carpeta)) {
            return response()->json(['error' => 'Carpeta no existe: ' . $carpeta], 404);
        }

        $files = glob($carpeta . '/*');

        if (empty($files)) {
            return response()->json(['error' => 'No hay archivos en la carpeta: ' . $carpeta], 404);
        }

        // Ordenar archivos por fecha de modificación descendente (más reciente primero)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $archivo = $files[0];

        if (file_exists($archivo)) {
            return response()->file($archivo);
        } else {
            return response()->json(['error' => 'Archivo no encontrado: ' . $archivo], 404);
        }
    }
    public function verGuia($idOrden)
    {
        // Buscar la orden usando el campo 'id' en la base de datos esquema / vista
        $orden = DB::table('orden_track.ordenes_view')
                    ->where('id', $idOrden)
                    ->first();

        if (!$orden) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        // Determinar carpeta según tipo o prefijo del nro_orden
        if (strpos(strtolower($orden->nro_orden), 'directa') === 0 || $orden->tipo === 'directa') {
            $tipoCarpeta = 'directas';
        } elseif (strpos(strtolower($orden->nro_orden), 'ocam') === 0 || $orden->tipo === 'am') {
            $tipoCarpeta = 'propias';
        } else {
            return response()->json(['error' => 'Tipo de orden no reconocido'], 400);
        }

        // Ruta base absoluta apuntando a la carpeta mgc fuera de Laravel
        $basePath = 'C:/xampp/htdocs/mgc/storage/app/mgcp/ordenes-compra/guias/';

        // Construir la ruta completa a la carpeta con idOrden
        $carpeta = $basePath . $tipoCarpeta . '/' . $idOrden;

        if (!is_dir($carpeta)) {
            return response()->json(['error' => 'Carpeta no existe: ' . $carpeta], 404);
        }

        $files = glob($carpeta . '/*');

        if (empty($files)) {
            return response()->json(['error' => 'No hay archivos en la carpeta: ' . $carpeta], 404);
        }

        // Ordenar archivos por fecha de modificación descendente
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $archivo = $files[0];

        if (file_exists($archivo)) {
            return response()->file($archivo, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($archivo) . '"'
            ]);
        } else {
            return response()->json(['error' => 'Archivo no encontrado: ' . $archivo], 404);
        }
    }



    
}
