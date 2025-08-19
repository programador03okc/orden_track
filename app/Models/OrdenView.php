<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenView extends Model
{
    protected $table = 'orden_track.ordenes_view'; // Vista con esquema

    public $timestamps = false; // Porque es una vista

    // Si quieres que Eloquent no espere llave primaria, sino que sea "id"
    protected $primaryKey = 'id';

    // Opcional: puedes definir los campos fillable o guarded si quieres
}