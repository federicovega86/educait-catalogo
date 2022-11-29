<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorias = Categoria::orderBy('catNombre')->get();
        return view('categorias', [ 'categorias' => $categorias ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categoriaCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function validarForm(Request $request){
        $request->validate
        (
            [ 
                'catNombre' => 'required|min:3|max:50|unique:categorias,catNombre'

            ],
            [
                'catNombre.required' => 'El campo Nombre de la Categoria es obligatorio',
                'catNombre.min' => 'El campo Nombre de la Categoria debe tener al menos 2 caracteres',
                'catNombre.max' => 'El campo Nombre de la Categoria debe tener como máximo 50 caracteres',
                'catNombre.unique' => 'El campo Nombre de la Categoria ya existe en la base de datos'
            ]
            
            );
    }


    public function store(Request $request)
    {
        //
        $this->validarForm($request);
        $catNombre = $request->catNombre;
        //insertar
        try{
            $categoria = new Categoria;
            $categoria->catNombre = $catNombre;
            $categoria->save();
            return redirect('/categorias')->with(
                [
                    'mensaje' => 'Categoria: '.$catNombre.' agregada correctamente',
                    'css' => 'success'
                ]
            );
        }catch(\Throwable $th){
            return redirect('categoria/create')
                ->with([
                    'mensaje', 'Categoria: '.$catNombre.' NO agregada',
                    'css' => 'danger'
                    ]);
        }
        return 'Paso la validacion'. $catNombre;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
         //
         $Categoria = Categoria::find($id);
         return view('categoriaEdit', [ 'Categoria' => $Categoria ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validarForm($request);
        //actualizamos
        $idCategoria = $request->idCategoria;
        $catNombre = $request->catNombre;
        try{
            $Categoria = Categoria::find($idCategoria);
            $Categoria->catNombre = $catNombre;
            $Categoria->save();
            return redirect('/categorias')->with(
                [
                    'mensaje' => 'Categoria: '.$catNombre.' modificada correctamente',
                    'css' => 'success'
                ]
            );
        }catch(\Throwable $th){
            return redirect('categoria/edit/'.$idCategoria)
                ->with([
                    'mensaje', 'Categoria: '.$catNombre.' NO modificada',
                    'css' => 'danger'
                    ]);
        }
    }

    public function confirm($id){
        
        $Categoria= Categoria::find($id);
        $Producto = Producto::firstWhere('idCategoria', $Categoria->idCategoria);
        if($Producto){
            return redirect('/categorias')->with(
                [
                    'mensaje' => 'Categoria: '.$Categoria->catNombre.' no se puede eliminar porque tiene productos relacionados',
                    'css' => 'warning'
                ]
            );
        }
        return view('categoriaDelete', [ 'Categoria' => $Categoria ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $catNombre = $request->catNombre;
        try{
            $idCategoria = $request->idCategoria;
            Categoria::destroy($idCategoria);
            return redirect('/categorias')->with(
                [
                    'mensaje' => 'Categoria: '.$request->catNombre.' eliminado correctamente',
                    'css' => 'sucess'
                ]
                );
        }catch(\Throwable $th){
            return redirect('/categorias')->with(
                [
                    'mensaje' => 'Categoria: '.$catNombre.' eliminada correctamente',
                    'css' => 'success'
                ]
            );
        }
    }
}
