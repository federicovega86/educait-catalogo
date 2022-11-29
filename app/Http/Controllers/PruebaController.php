<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PruebaController extends Controller
{
    //index
    public function listarRecurso(){
        return "Listar Recurso";
    }

    //show()
    public function mostrarRecurso($id){
        return "Mostrar Recurso: $id";
    }

    //create() && store()
    public function crearRecurso(){
        return "Crear Recurso";
    }
    
    //edit() && update()
    public function editarRecurso($id){
        return "Editar Recurso: $id";
    }

    //destroy()
    public function eliminarRecurso($id){
        return "Eliminar Recurso: $id";
    }

}
