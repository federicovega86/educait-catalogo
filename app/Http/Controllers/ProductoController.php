<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // order by nombre
        $productos = Producto::with(['getMarca','getCategoria'])->paginate(10);
        return view('productos', [ 'productos' => $productos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // obtnemos las marcas y categorias para el select
        $marcas = Marca::orderBy('mkNombre')->get();
        $categorias = Categoria::orderBy('catNombre')->get();

        return view('productoCreate', [ 'marcas' => $marcas, 'categorias' => $categorias ]);
    }

    private function validarForm(Request $request, $idProducto = null){
        $request->validate
        (
            //reglas
            [ 
                'prdNombre' => 'required|min:2|max:50|unique:App\Models\Producto,prdNombre,'.$idProducto.',idProducto',
                'prdPrecio' => 'required|numeric|min:0',
                'idMarca' => 'required|numeric',
                'idCategoria' => 'required|numeric',
                'prdDescripcion' => 'required|min:10|max:150',
                'prdImagen' => 'image|max:2048'
            ],
            //mensajes
            [
                'prdNombre.required' => 'El campo "Nombre del producto" es obligatorio.',
                'prdNombre.min'=>'El campo "Nombre del producto" debe tener como mínimo 2 caractéres.',
                'prdNombre.max'=>'El campo "Nombre" debe tener 50 caractéres como máximo.',
                'prdNombre.unique'=>'El campo "Nombre del producto" ya existe en la base de datos.',
                'prdPrecio.required'=>'Complete el campo Precio.',
                'prdPrecio.numeric'=>'Complete el campo Precio con un número.',
                'prdPrecio.min'=>'Complete el campo Precio con un número mayor a 0.',
                'idMarca.required'=>'Seleccione una marca.',
                'idCategoria.required'=>'Seleccione una categoría.',
                'prdDescripcion.required'=>'Complete el campo "Descripción".',
                'prdDescripcion.min'=>'Complete el campo Descripción con al menos 10 caractéres',
                'prdDescripcion.max'=>'Complete el campo Descripción con 150 caractéres como máxino.',
                'prdImagen.image'=>'El archivo debe ser una imagen.',
                'prdImagen.max'=>'El archivo debe pesar menos de 2MB.'
            ]
            
        );
    }

    private function subirImagen(Request $request){
        
        $prdImagen = 'noDisponible.png';
        // si envia imagen actual, la guardamos
        if( $request->has('prdImagenActual') ){
            $prdImagen = $request->prdImagenActual;
        }
        
        if( $request->file('prdImagen') ){
            // renombramos archivo.
            $archivo = $request->file('prdImagen');
            $tiempo = time();
            $extension = $archivo->getClientOriginalExtension();
            $prdImagen = $tiempo.'.'.$extension;
            // movemos archivo a carpeta publica
            $archivo->move( public_path('imagenes/productos/'), $prdImagen );
        }

        return $prdImagen;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $prdNombre = $request->prdNombre;
        //validación
        $this->validarForm($request);
        //subirImagen()
        $prdImagen = $this->subirImagen($request);
        /*magia para almacenar en tabla productos*/
        try {
            //instanciamos
            $Producto = new Producto;
            //asignamos atributos
            $Producto->prdNombre = $prdNombre;
            $Producto->prdPrecio = $request->prdPrecio;
            $Producto->idMarca = $request->idMarca;
            $Producto->idCategoria = $request->idCategoria;
            $Producto->prdDescripcion = $request->prdDescripcion;
            $Producto->prdImagen = $prdImagen;
            $Producto->prdActivo = '1'; // hardcoding
            $Producto->save();
            
            return redirect('/productos')
                ->with([
                    'mensaje'=>'Producto: '.$prdNombre.' agregado correctamente',
                    'css'=>'success'
                ]);
        }
        catch ( \Throwable $th ){
            return redirect('/productos')
                ->with([
                    'mensaje'=>'No se pudo agregar el producto: '.$prdNombre,
                    'css'=>'danger'
                ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $producto = Producto::with(['getMarca','getCategoria'])->find($id);
        $marcas = Marca::orderBy('mkNombre')->get();
        $categorias = Categoria::orderBy('catNombre')->get();

        return view('productoEdit', [ 
                                    'producto' => $producto, 
                                    'marcas' => $marcas, 
                                    'categorias' => $categorias 
                                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validacion
        $this->validarForm($request, $request->idProducto);
        //subirImagen() si enviaron archivo
        $prdImagen = $this->subirImagen($request);
        
        /*magia para actualizar en tabla productos*/
        // try {
            // buscamos el producto
            $Producto = Producto::find($request->idProducto);
            //asignamos atributos
            $Producto->prdNombre = $request->prdNombre;
            $Producto->prdPrecio = $request->prdPrecio;
            $Producto->idMarca = $request->idMarca;
            $Producto->idCategoria = $request->idCategoria;
            $Producto->prdDescripcion = $request->prdDescripcion;
            $Producto->prdImagen = $prdImagen;
            // $Producto->prdActivo = '1'; // hardcoding
            $Producto->save();
            
            return redirect('/productos')
                ->with([
                    'mensaje'=>'Producto: '.$request->prdNombre.' modificado correctamente',
                    'css'=>'success'
                ]);
        // }
        // catch ( \Throwable $th ){
        //     return redirect('/productos')
        //         ->with([
        //             'mensaje'=>'No se pudo modificar el producto: '.$request->prdNombre,
        //             'css'=>'danger'
        //         ]);
        // }


    }


    public function confirm($id){
        $producto = Producto::find($id);
        return view('productoDelete', [ 'producto' => $producto ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //

    }
}
