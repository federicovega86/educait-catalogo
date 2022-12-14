<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'idProducto';

    ## metodos de relacion
    public function getMarca()
    {
        return $this->belongsTo(Marca::class, 'idMarca', 'idMarca');
    }

    public function getCategoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    ## metodo order by.
    public function orderByNombre($query)
    {
        return $query->orderBy('prdNombre');
    }
}
