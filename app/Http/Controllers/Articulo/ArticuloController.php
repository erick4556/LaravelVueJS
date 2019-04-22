<?php

namespace App\Http\Controllers\Articulo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Articulo\Articulo;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {   
        //Para seguridad, solo se tiene acceso a este método mediante ajax
       if(!$request->ajax())
            return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            //Eloquen Results
         $articulos = Articulo::join('categorias','articulos.idcategoria', '=','categorias.id')
         ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta',
         'articulos.stock','articulos.descripcion','articulos.condicion')
         ->orderBy('articulos.id','desc')->paginate(2);     
        }else{
            $articulos = Articulo::join('categorias','articulos.idcategoria', '=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta',
            'articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio,'like','%'.$buscar.'%')->orderBy('articulos.id','desc')->paginate(2);//$criterio puede ser nombre o descripcion    
        
            
        }


        
      return [
          //Instancias de la paginación
          'pagination' =>[
            'total'        => $articulos->total(),
            'current_page' => $articulos->currentPage(),
            'per_page'     => $articulos->perPage(),
            'last_page'    => $articulos->lastPage(),
            'from'         => $articulos->firstItem(),
            'to'           => $articulos->lastItem(),
          ],
          'articulos' => $articulos
        ];

    }

    public function store(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //   
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();
    }

     public function update(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //    
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();
    }
    
    public function desactivar(Request $request)
    {   
         //Para seguridad
         if(!$request->ajax())
         return redirect('/');
            //   
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {   
         //Para seguridad
         if(!$request->ajax())
         return redirect('/');
        //   
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }

    //Para maestro detalle
    public function buscarArticulo(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)
        ->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();
 
        return ['articulos' => $articulos];
    } 
    
    
    public function buscarArticuloVenta(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)
        ->select('id','nombre','stock','precio_venta')
        ->where('stock','>','0')
        ->orderBy('nombre', 'asc')
        ->take(1)->get();
 
        return ['articulos' => $articulos];
    }

    //Para maestro detalle
    public function listarArticulo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
         
 
        return ['articulos' => $articulos];
    }

    public function listarArticuloVenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
         
 
        return ['articulos' => $articulos];
    }

    public function listarPdf(){
        $articulos = Articulo::join('categorias','articulos.idcategoria', '=','categorias.id')
        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta',
        'articulos.stock','articulos.descripcion','articulos.condicion')
        ->orderBy('articulos.nombre','desc')->get();   

        $cont= Articulo::count(); //para almacenar la cantidad de registros que tengo en la tabla articulo

        $pdf = \PDF::loadView('pdf.articulospdf',['articulos'=>$articulos,'cont'=>$cont]);//voy a cargar el reporte en la vista articulospdf que esta en la carpeta pdf, y le envio un parámetro articulos
                                                                                           // que es lo que contiene la variable articulos y otro parámetro cont que contiene la variable cont.  
        return $pdf->download('articulos.pdf');//para descargar el reporte con el nombre articulos.pdf
    }

}
