<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)//
    {   
        $anio=date('Y');//obtener el año y lo almaceno en anio
        $ingresos = DB::table('ingresos as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),//Obtener el mes de la fecha_hora del ingreso
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))//Mostrar la suma de todos los campos totales
        ->whereYear('i.fecha_hora',$anio)//Solo se muestre los meses y totales asignado a cada mes del año actual, ejemplo si estoy en mayo me va mostrar de enero hasta mayo
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))//Para agrupar por el mes y por el año para poder utilizar la función SUM
        ->get();

        $ventas=DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
        DB::raw('YEAR(v.fecha_hora) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();

        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];
    }
}
