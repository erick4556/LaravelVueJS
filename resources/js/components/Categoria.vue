<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td>
                                        <button type="button" @click="abrirModal('categoria','actualizar',categoria)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        
                                       <template v-if="categoria.condicion"><!--Si la categoria esta activa-->
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)">
                                          <i class="icon-trash"></i>
                                        </button>
                                       </template>

                                       <template v-else><!--Si la categoria no esta activa-->
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)">
                                          <i class="icon-check"></i>
                                        </button>
                                       </template> 

                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                        <div v-if="categoria.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                         <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>                                                                       
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']"> <!--Me indica que estoy en esa página actual, isActived = propiedad computada, si es la página que se esta mostrando se va agregar la directiva active-->
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a> <!--paso la página que deseo cambiar-->
                                </li>
                            
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingresde descripción">
                                    </div>
                                </div>

                                <div v-show="errorCategoria" class="form-group row div-error"> <!--el error so se va mostrar si errorCategoria tiene el valor 1-->
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>    
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCategoria()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
    

        </main>
</template>

<script>
    export default {
        data(){
            return {
                //Variables que voy a utilizar
                categoria_id:0, //Aqui se va almacenar cual es el id de la categoria que quiero actualizarr
                nombre:'',
                descripcion:'',
                arrayCategoria:[], //Para almacenar la lista de todas las categorías
                modal : 0, //Para indicar si voy a mostrar o ocultar la ventana modal
                tituloModal : '', //Para ver si el título que voy a mostrar es registrar o actualizar
                tipoAccion : 0,
                errorCategoria: 0,
                errorMostrarMsjCategoria : [], //En primer lugar el array no va tener ningun valor
                pagination : {
                    //Todo en base a registros total de registros, registro por páginas, etc
                   'total' : 0,
                   'current_page' : 0,
                   'per_page' : 0,
                   'last_page' : 0,
                   'from' : 0, //Desde la página
                   'to' : 0,//hasta la página     
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function(){
                if (!this.pagination.to) { //Si el último elemento de la página es diferente que to, regresa un array vacío
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from<1){ //Si la página actual es 0 o es menos
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){ //Si to es mayor o igual a la ultima página, lo es lógico por que no puede mayor que la última página
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            listarCategoria(page,buscar,criterio){
                let me = this;
                var url = 'categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) { //Se hace referencia a la ruta categoria
                    // handle success
                    //console.log(response);
                    var respuesta = response.data;//Se guarda el contenido del objeto response
                    me.arrayCategoria = respuesta.categorias.data;
                    me.pagination =  respuesta.pagination; 
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this; //objeto
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envía la petición para vizualizar la data de esa página
                me.listarCategoria(page,buscar,criterio);
            },
            registrarCategoria(){
                
                if(this.validarCategoria()){ //Si este método devuelva 1
                    return;
                }

                let me = this; //Para hacer referencia a este mismo archivo

                axios.post('categoria/registrar',{
                    'nombre':this.nombre,
                    'descripcion' :this.descripcion
                }).then(function (response) {
                    //console.log(response);
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre'); //Le mando que muestre la primera página, el filtro que es vacío, y el nombre de la columna
                }) 
                .catch(function (error) {
                    console.log(error);
                });

            },
            actualizarCategoria(){
                 if(this.validarCategoria()){ //Si este método devuelva 1
                    return;
                }

                let me = this; //Para hacer referencia a este mismo archivo

                axios.put('categoria/actualizar',{
                    'nombre':this.nombre,
                    'descripcion' :this.descripcion,
                    'id': this.categoria_id
                }).then(function (response) {
                    //console.log(response);
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }) 
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarCategoria(id){
                 swal({
                title: 'Esta seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    //Si el usuario da click en aceptar
                    let me = this;

                    axios.put('/categoria/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
             activarCategoria(id){
                 swal({
                title: 'Esta seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    //Si el usuario da click en aceptar
                    let me = this;

                    axios.put('/categoria/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            validarCategoria(){
                //Inicialización de las variables
                this.errorCategoria = 0;
                this.errorMostrarMsjCategoria = [];   
                
                if(!this.nombre)
                    this.errorMostrarMsjCategoria.push("El nombre de la cetegoría no puede estar vacío");
                 
                 //errorCategoria solo va pasar a 1 cuando almenos tenga registrado un error en el errorMostrarMsjCategoria
                 if(this.errorMostrarMsjCategoria.length)
                    this.errorCategoria = 1;

                return this.errorCategoria;    
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion = ''; 
            },
            //En el data no se le envia un objeto cuando se va registrar pero si se va mandar uno cuando se va actualizar
            abrirModal(modelo, accion, data=[]){ //La acción va tener dos valores registrar o actualizar, data va ser el objeto que esta en la fila categoria
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case "registrar":
                            {
                                  this.modal = 1; //Aqui digo que se debe mostrar la modal
                                  this.tituloModal = 'Regisrar Categoría';
                                  this.nombre = '';
                                  this.descripcion = '';
                                  this.tipoAccion = 1;
                                  break;  
                            }
                            case "actualizar":
                            {
                                this.modal = 1;
                                this.tituloModal = "Actualizar Categoría";
                                this.tipoAccion = 2;
                                this.categoria_id = data['id'];
                                this.nombre = data['nombre']; //se accede a la propiedad nombre del objeto
                                this.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }    
                }
            }
        },
        mounted() {
            //console.log('Component mounted.')
            this.listarCategoria(1,this.buscar,this.criterio);
        }
    }
</script>

<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }

    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error{
        display: flex;
        justify-content: center;
    
    }

    .text-error{
        color: red !important;
        font-weight: bold;
    }

</style>

