<?php

namespace App\Http\Controllers\Ingreso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Ingreso\Ingreso;
use App\Models\DetalIngreso\DetalleIngreso;
use App\User;
use App\Notifications\NotifyAdmin;

class IngresoController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(2);
        }
         
        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        try{
            DB::beginTransaction();
 
            $mytime= Carbon::now('America/Panama'); //Para indicar en el campo fecha_hora mi zona horaria
 
            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id; //Envio el id del usuario que se ha autenticado
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';
            $ingreso->save();
 
            $detalles = $request->data;//Array de detalles

            //Recorro todos los elementos
            //Registro todo los detalles que pertenecen al ingreso
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo']; //A la propiedad idarticulo lo que recibo en el indice idarticulo y asi sucesivamente
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];          
                $detalle->save();
            }          
            
            //Obtener la cantidad de ingresos y cantidad de ventas
         /*   $fechaActual = date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at',$fechaActual)->count();//Consulta a la tabla ventas, obtendo todas las ventas donde la fecha de creación es igual a la fecha actual, la venta ha sido creado
                                                                                            //en este momento. count() es para obtener la cantidad de ventas
            $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count();
            
            $arregloDatos = [
                    'ventas'=>[ //objeto del arreglo
                        'numero' => $numVentas,
                        'msj' => 'Ventas'
                    ],
                    'ingresos'=>[ //objeto del arreglo
                        'numero' => $numIngresos,
                        'msj' => 'Ingresos'
                    ]
                ];

                //Notificar del nuevo ingreso
                $allUsers = User::all(); //Almaceno todos los usuarios

                //Recorrer todos los usuarios perro
                foreach ($allUsers as $notificar) {
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));//Se recibe el id a los usuarios a los que se la mandar la notificación, se notifica y ese método espera
                                                                                //una instancia de la notificación, es la notificacion que programe antes y le mando el arregloDatos
                }*/

                $fechaActual= date('Y-m-d');
                $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count(); 
                $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count(); 
    
                $arregloDatos = [ 
                'ventas' => [ 
                            'numero' => $numVentas, 
                            'msj' => 'Ventas' 
                        ], 
                'ingresos' => [ 
                            'numero' => $numIngresos, 
                            'msj' => 'Ingresos' 
                        ] 
                ];                
                $allUsers = User::all();
    
                foreach ($allUsers as $notificar) { 
                    //Notificar
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos)); 
                }         

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

     public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }

    //Obtener los datos de la cabecera del ingreso
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $ingreso = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','personas.nombre','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();
         
        return ['ingreso' => $ingreso];
    }

    //Obtener detalle
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $detalles = DetalleIngreso::join('articulos','detalle_ingresos.idarticulo','=','articulos.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
        ->where('detalle_ingresos.idingreso','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();
         
        return ['detalles' => $detalles];
    }

}
