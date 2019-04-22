<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Persona\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {   
        //Para seguridad, solo se tiene acceso a este método mediante ajax
        //Solo va recibir peticiones mediante ajax
       if(!$request->ajax())
            return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            //Eloquen Results
         $personas = Persona::orderBy('id','desc')->paginate(2);     
        }else{
            $personas = Persona::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(2);
            //% se significa que el texto puede estar al inicio, en el medio o final, del campo criterio
        }

      //  $personas = Categoria::all();
      //Query Builder Results
      //$personas = DB::table('personas')->paginate(2);
      
     

      //return $personas;

      //return con paginación
        
      return [
          //Instancias de la paginación
          'pagination' =>[
            'total'        => $personas->total(),
            'current_page' => $personas->currentPage(),
            'per_page'     => $personas->perPage(),
            'last_page'    => $personas->lastPage(),
            'from'         => $personas->firstItem(),
            'to'           => $personas->lastItem(),
          ],
          'personas' => $personas
        ];

    }

    public function store(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //   
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

    public function update(Request $request)
    {   
        //Para seguridad
        if(!$request->ajax())
            return redirect('/');
        //    
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

    //Para maestro - detalle

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre','num_documento')
        ->orderBy('nombre', 'asc')->get();
 
        return ['clientes' => $clientes];
    }

}
