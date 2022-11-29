<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $marcas = Marca::paginate(3);
        return view('marcas', [ 'marcas' => $marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('marcaCreate');

    }

    private function validarForm(Request $request){
        $request->validate
        (
            [ 
                'mkNombre' => 'required|min:2|max:50|unique:marcas,mkNombre'

            ],
            [
                'mkNombre.required' => 'El campo Nombre de la Marca es obligatorio',
                'mkNombre.min' => 'El campo Nombre de la Marca debe tener al menos 2 caracteres',
                'mkNombre.max' => 'El campo Nombre de la Marca debe tener como máximo 50 caracteres',
                'mkNombre.unique' => 'El campo Nombre de la Marca ya existe en la base de datos'
            ]
            
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validacion
        $this->validarForm($request);
        $mkNombre = $request->mkNombre;
        //insertar
        try{
            $marca = new Marca;
            $marca->mkNombre = $mkNombre;
            $marca->save();
            return redirect('/marcas')->with(
                [
                    'mensaje' => 'Marca: '.$mkNombre.' agregada correctamente',
                    'css' => 'success'
                ]
            );
        }catch(\Throwable $th){
            return redirect('marca/create')
                ->with([
                    'mensaje', 'Marca: '.$mkNombre.' NO agregada',
                    'css' => 'danger'
                    ]);
        }
        return 'Paso la validacion'. $mkNombre;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $Marca = Marca::find($id);
        return view('marcaEdit', [ 'Marca' => $Marca ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validamos
        $this->validarForm($request);
        //actualizamos
        $idMarca = $request->idMarca;
        $mkNombre = $request->mkNombre;
        try{
            $Marca = Marca::find($idMarca);
            $Marca->mkNombre = $mkNombre;
            $Marca->save();
            return redirect('/marcas')->with(
                [
                    'mensaje' => 'Marca: '.$mkNombre.' modificada correctamente',
                    'css' => 'success'
                ]
            );
        }catch(\Throwable $th){
            return redirect('marca/edit/'.$idMarca)
                ->with([
                    'mensaje', 'Marca: '.$mkNombre.' NO modificada',
                    'css' => 'danger'
                    ]);
        }

        
    }

    public function confirm($id){
        
        $Marca= Marca::find($id);
        $Producto = Producto::firstWhere('idMarca', $Marca->idMarca);

        if($Producto){
            return redirect('/marcas')->with(
                [
                    'mensaje' => 'Marca: '.$Marca->mkNombre.' no se puede eliminar porque tiene productos relacionados',
                    'css' => 'warning'
                ]
            );
        }
        return view('marcaDelete', [ 'Marca' => $Marca ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $mkNombre = $request->mkNombre;
        try{
            $idMarca = $request->idMarca;
            Marca::destroy($idMarca);
            return redirect('/marcas')->with(
                [
                    'mensaje' => 'Marca: '.$request->mkNombre.' eliminado correctamente',
                    'css' => 'sucess'
                ]
                );
        }catch(\Throwable $th){
            return redirect('/marcas')->with(
                [
                    'mensaje' => 'Marca: '.$mkNombre.' eliminada correctamente',
                    'css' => 'success'
                ]
            );
        }
        
    }
}
