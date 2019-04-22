<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Ruta para los middlewares
//Los que no se han autentificado a que ruta van a tener acceso
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');//name es para el alias
});
//La vas a matar perroooooooooooo
//Los usuarios que estan autenticado
Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('dashboard','Dashboard\DashboardController');

    Route::get('/main', function () {
        //return view('layouts/principal');
        return view('contenido/contenido');
    })->name('main');//alias

    //La ruta para el almacenero y tiene permiso para administrar categoria, articulos y proveedores
    Route::group(['middleware'=>['Almacenero']],function(){
        //Categoria
        Route::get('categoria','Categoria\CategoriaController@index');
        Route::post('categoria/registrar','Categoria\CategoriaController@store');
        Route::put('categoria/actualizar','Categoria\CategoriaController@update');
        Route::put('categoria/desactivar','Categoria\CategoriaController@desactivar');
        Route::put('categoria/activar','Categoria\CategoriaController@activar');
        Route::get('categoria/selectCategoria','Categoria\CategoriaController@selectCategoria');

        //Artículo
        Route::get('articulo','Articulo\ArticuloController@index');
        Route::post('articulo/registrar','Articulo\ArticuloController@store');
        Route::put('articulo/actualizar','Articulo\ArticuloController@update');
        Route::put('articulo/desactivar','Articulo\ArticuloController@desactivar');
        Route::put('articulo/activar','Articulo\ArticuloController@activar');
        Route::get('articulo/buscarArticulo','Articulo\ArticuloController@buscarArticulo');
        Route::get('articulo/listarArticulo','Articulo\ArticuloController@listarArticulo');
        Route::get('articulo/buscarArticuloVenta','Articulo\ArticuloController@buscarArticuloVenta');
        Route::get('articulo/listarArticuloVenta','Articulo\ArticuloController@listarArticuloVenta');
        Route::get('articulo/listarPdf','Articulo\ArticuloController@listarPdf')->name('articulos_pdf');

        //Proveedor
        Route::get('proveedor','Proveedor\ProveedorController@index');
        Route::post('proveedor/registrar','Proveedor\ProveedorController@store');
        Route::put('proveedor/actualizar','Proveedor\ProveedorController@update');
        Route::get('proveedor/selectProveedor','Proveedor\ProveedorController@selectProveedor');

        //Ingreso
        Route::get('ingreso','Ingreso\IngresoController@index');
        Route::post('ingreso/registrar','Ingreso\IngresoController@store');
        Route::put('ingreso/desactivar','Ingreso\IngresoController@desactivar');
        Route::get('ingreso/obtenerCabecera','Ingreso\IngresoController@obtenerCabecera');
        Route::get('ingreso/obtenerDetalles','Ingreso\IngresoController@obtenerDetalles');
        


    });

    //La ruta para el vendedor y tiene acceso para revisar los clientes
    Route::group(['middleware'=>['Vendedor']],function(){

            
        //Cliente
        Route::get('cliente','Cliente\ClienteController@index');
        Route::post('cliente/registrar','Cliente\ClienteController@store');
        Route::put('cliente/actualizar','Cliente\ClienteController@update');

        //Venta
        Route::get('/venta', 'Venta\VentaController@index');
        Route::post('/venta/registrar', 'Venta\VentaController@store');
        Route::put('/venta/desactivar', 'Venta\VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'Venta\VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'Venta\VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'Venta\VentaController@pdf')->name('venta_pdf');

    });    

      //La ruta para el administrador y tiene acceso para casi todo
    Route::group(['middleware'=>['Administrador']],function(){

        //Categoria
        Route::get('categoria','Categoria\CategoriaController@index');
        Route::post('categoria/registrar','Categoria\CategoriaController@store');
        Route::put('categoria/actualizar','Categoria\CategoriaController@update');
        Route::put('categoria/desactivar','Categoria\CategoriaController@desactivar');
        Route::put('categoria/activar','Categoria\CategoriaController@activar');
        Route::get('categoria/selectCategoria','Categoria\CategoriaController@selectCategoria');
    
         //Artículo
         Route::get('articulo','Articulo\ArticuloController@index');
         Route::post('articulo/registrar','Articulo\ArticuloController@store');
         Route::put('articulo/actualizar','Articulo\ArticuloController@update');
         Route::put('articulo/desactivar','Articulo\ArticuloController@desactivar');
         Route::put('articulo/activar','Articulo\ArticuloController@activar');
         Route::get('articulo/buscarArticulo','Articulo\ArticuloController@buscarArticulo');
         Route::get('articulo/listarArticulo','Articulo\ArticuloController@listarArticulo');
         Route::get('articulo/buscarArticuloVenta','Articulo\ArticuloController@buscarArticuloVenta');
         Route::get('articulo/listarArticuloVenta','Articulo\ArticuloController@listarArticuloVenta');
         Route::get('articulo/listarPdf','Articulo\ArticuloController@listarPdf')->name('articulos_pdf');
    
        //Proveedor
        Route::get('proveedor','Proveedor\ProveedorController@index');
        Route::post('proveedor/registrar','Proveedor\ProveedorController@store');
        Route::put('proveedor/actualizar','Proveedor\ProveedorController@update');
        Route::get('proveedor/selectProveedor','Proveedor\ProveedorController@selectProveedor');


        //Roles
        Route::get('rol','Role\RoleController@index');
        Route::get('rol/selectRol','Role\RoleController@selectRol');
        
        //Users
        Route::get('user','User\UserController@index');
        Route::post('user/registrar','User\UserController@store');
        Route::put('user/actualizar','User\UserController@update');
        Route::put('user/desactivar','User\UserController@desactivar');
        Route::put('user/activar','User\UserController@activar');
    
            
        //Cliente
        Route::get('cliente','Cliente\ClienteController@index');
        Route::post('cliente/registrar','Cliente\ClienteController@store');
        Route::put('cliente/actualizar','Cliente\ClienteController@update');
        Route::get('cliente/selectCliente','Cliente\ClienteController@selectCliente');

         //Ingreso
         Route::get('ingreso','Ingreso\IngresoController@index');
         Route::post('ingreso/registrar','Ingreso\IngresoController@store');
        Route::put('ingreso/desactivar','Ingreso\IngresoController@desactivar');
        Route::get('ingreso/obtenerCabecera','Ingreso\IngresoController@obtenerCabecera');
        Route::get('ingreso/obtenerDetalles','Ingreso\IngresoController@obtenerDetalles');
        
        //Venta
        Route::get('/venta', 'Venta\VentaController@index');
        Route::post('/venta/registrar', 'Venta\VentaController@store');
        Route::put('/venta/desactivar', 'Venta\VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'Venta\VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'Venta\VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'Venta\VentaController@pdf')->name('venta_pdf');//Ese parámetro id lo estoy recibiendo en el controlador

    });    
  
    
    //Login
   /* Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');//name es para el alias*/
});

/*
Route::get('/main', function () {
    //return view('layouts/principal');
    return view('contenido/contenido');
})->name('main');//alias

//Categoria
Route::get('categoria','Categoria\CategoriaController@index');
Route::post('categoria/registrar','Categoria\CategoriaController@store');
Route::put('categoria/actualizar','Categoria\CategoriaController@update');
Route::put('categoria/desactivar','Categoria\CategoriaController@desactivar');
Route::put('categoria/activar','Categoria\CategoriaController@activar');
Route::get('categoria/selectCategoria','Categoria\CategoriaController@selectCategoria');

//Artículo
Route::get('articulo','Articulo\ArticuloController@index');
Route::post('articulo/registrar','Articulo\ArticuloController@store');
Route::put('articulo/actualizar','Articulo\ArticuloController@update');
Route::put('articulo/desactivar','Articulo\ArticuloController@desactivar');
Route::put('articulo/activar','Articulo\ArticuloController@activar');

//Cliente
Route::get('cliente','Cliente\ClienteController@index');
Route::post('cliente/registrar','Cliente\ClienteController@store');
Route::put('cliente/actualizar','Cliente\ClienteController@update');

//Proveedor
Route::get('proveedor','Proveedor\ProveedorController@index');
Route::post('proveedor/registrar','Proveedor\ProveedorController@store');
Route::put('proveedor/actualizar','Proveedor\ProveedorController@update');

//Roles
Route::get('rol','Role\RoleController@index');
Route::get('rol/selectRol','Role\RoleController@selectRol');

//Users
Route::get('user','User\UserController@index');
Route::post('user/registrar','User\UserController@store');
Route::put('user/actualizar','User\UserController@update');
Route::put('user/desactivar','User\UserController@desactivar');
Route::put('user/activar','User\UserController@activar');


//Login
Route::get('/','Auth\LoginController@showLoginForm');
Route::post('/login','Auth\LoginController@login')->name('login');//name es para el alias*/

//Auth::routes(); Ya no voy a utilizar las rutas por defecto

//Route::get('/home', 'HomeController@index')->name('home');
