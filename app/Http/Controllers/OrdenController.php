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
}
