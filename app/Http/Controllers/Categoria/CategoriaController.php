<?php

namespace App\Http\Controllers\Categoria;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Categoria\Categoria;

class CategoriaController extends Controller
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
         $categorias = Categoria::orderBy('id','desc')->paginate(2);     
        }else{
            $categorias = Categoria::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(2);
            //% se significa que el texto puede estar al inicio, en el medio o final, del campo criterio
        }

      //  $categorias = Categoria::all();
      //Query Builder Results
      //$categorias = DB::table('categorias')->paginate(2);
      
     

      //return $categorias;

      //return con paginación
        
      return [
          //Instancias de la paginación
          'pagination' =>[
            'total'        => $categorias->total(),
            'current_page' => $categorias->currentPage(),
            'per_page'     => $categorias->perPage(),
            'last_page'    => $categorias->lastPage(),
            'from'         => $categorias->firstItem(),
            'to'           => $categorias->lastItem(),
          ],
          'categorias' => $categorias
        ];

    }

    public function selectCategoria(Request $request){
       
         if(!$request->ajax())
         return redirect('/');
     
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre','asc')->get();  

        return ['categorias'=> $categorias];    

    }
    
    public function store(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //   
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    
    public function update(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //    
        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }
    
    public function desactivar(Request $request)
    {   
         //Para seguridad
         if(!$request->ajax())
         return redirect('/');
            //   
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {   
         //Para seguridad
         if(!$request->ajax())
         return redirect('/');
        //   
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }
   
}
