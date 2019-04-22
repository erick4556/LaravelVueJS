<?php

namespace App\Http\Controllers\Proveedor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Persona\Persona;
use App\Models\Proveedor\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $personas = Proveedor::join('personas','proveedors.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedors.contacto','proveedors.telefono_contacto')
            ->orderBy('personas.id', 'desc')->paginate(2);
        }
        else{
            $personas = Proveedor::join('personas','proveedors.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedors.contacto','proveedors.telefono_contacto')            
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(2);
        }
         
 
        return [
            'pagination' => [
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
        if (!$request->ajax()) return redirect('/');
         
        try{ //Capturador de excepciones
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();
            
            //Para registrar el proveedor, se va registrar tanto en la tabla persona como en la tabla proveedor a la vez
            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->id = $persona->id; //Obtener del objeto persona que se ha guardado anteriormente el id que se ha registrado para que ese id sea la llave foránea de la tabla proveedores
            $proveedor->save();
 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
         
         
    } 
    
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
 
            //Buscar primero el proveedor a modificar
            $proveedor = Proveedor::findOrFail($request->id);
 
            $persona = Persona::findOrFail($proveedor->id); //Se hace una búsqueda de ese id de proveedor, se obtiene la persona cuyo id es el mismo que esta en el objeto proveedor
 
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();
 
             
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();
 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }

    //Esta parte es para maestro-detalle
    public function selectProveedor(Request $request){

       if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
    
        //Para almacenar la lista de todos los proveedores que coincidan con el texto almacenado en la variable filtro
        $proveedores = Proveedor::join('personas','proveedors.id','=','personas.id')
        ->where('personas.nombre', 'like', '%'. $filtro . '%') //% y al final % indica que el texto que estamos buscando que esta en la variable filtro puede estar al incio, mitad o final del campo nombre
        ->orWhere('personas.num_documento', 'like', '%'. $filtro . '%')
        ->select('personas.id','personas.nombre','personas.num_documento')
        ->orderBy('personas.nombre', 'asc')->get();
 
        return ['proveedores' => $proveedores];
        

    }

}
