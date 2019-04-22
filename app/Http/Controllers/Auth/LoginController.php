<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 


class LoginController extends Controller
{
   public function showLoginForm(){
       return view('auth.login');
   }
  
   //Validar petición de inicio de sesión de usuario
   public function login(Request $request){
        $this->validateLogin($request);  

        if (Auth::attempt(['usuario' => $request->usuario,'password' => $request->password,'condicion'=>1])){ //Que la propiedad usuario sea igual a la propiedad usuario del objeto request y igual para el password y de la condición sea igual a 1
            return redirect()->route('main');
        }

        return back()
        ->withErrors(['usuario' => trans('auth.failedesp')])
        ->withInput(request(['usuario'])); //regresar el usuario que esta bien en el input
       //regresa atrás, withError se le agrega al back()
       //withErrors espera un parametro, necesita el identificador de la plantilla blade donde voy a enseñar el identificador = usuario y el error que voy a mostrar auth.failed que esta en lang/en/auth pero uso
       //trans para traducir el error dependiendo el idioma que tenga

    }

    protected function validateLogin(Request $request){
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]); //El arreglo son los valores que queremos validar
    }

    public function logout(Request $request){
        Auth::logout(); //Se hace referecia a la clase Auth y a su método logout
        $request->session()->invalidate();
        return redirect('/');
    }

}
