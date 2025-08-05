<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenView extends Model
{
    protected $table = 'orden_track.ordenes_view'; 
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
