<?php
require_once './app/model/producto_model.php';
require_once './app/model/marca_model.php';
require_once './app/view/json.view.php';


require_once './libs/jwt.php';

class ProductoApiController
{

    private $marcaModel;
    private $model;
    private $view;

    private $opcionesColumnasDisponibles = ['nombre', 'descripcion', 'marca', 'sexo', 'stock', 'precio', 'presentacion']; //columnas por las que se puede ordenar
    private $opcionModoDisponible = ['ASC', 'DESC']; //modos de ordenamiento disponibles
    private $opcionesDeFiltroDisponibles = ['stock'];
    private $opcionesSexo = ['M', 'F', 'U'];
     private $opcionesStock = [0, 1];


    function __construct()
    {

        $this->marcaModel = new MarcaModel();
        $this->model = new ProductoModel();
        $this->view = new JSONView();
    }

    public function traerProductos($req, $res)
    {

        $errores = [];
        $ordenarPor = '';
        $modo = '';

        //Traer ordenado por algun campo de manera ascendente o descendente
        if (isset($req->query->ordenarPor) && isset($req->query->modo) && !empty($req->query->ordenarPor) && !empty($req->query->modo)) { //si estan seteados los parametros

            //validar que la opcion "ordenarPor" pasada por el usuario sea correcta
            if (!$this->validOption(($req->query->ordenarPor),  $this->opcionesColumnasDisponibles)) { //si no es opc valida
                return $this->view->response('No se puede ordenar por esa caracteristica(inexistente)', 404);
            } else {//si es opc valida
                $ordenarPor = $req->query->ordenarPor; //capturamos el campo por el que vamos a ordenar
                
                if (!$this->validOption(($req->query->modo), $this->opcionModoDisponible)) {//validar que la opcion "modo" pasada por el usuario sea correcta
                    $errores[] = 'No existe ese modo de orden: solo ascendente y descendente'; //si no es modo(ascendente o desendente) valido
                
                } else {//si el ordenarPor y el modo son correctos entra 
                     
                    $modo = $req->query->modo;//capturamos el modo 

                    //traer todos los productos de la base de datos
                    $productos = $this->model->getTodosOrdenados($ordenarPor, $modo);
                }
            }

            if (count($errores) > 0) {//imprimimos si hay errorres
                $erroresString = implode(', ', $errores);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $erroresString;
                return $this->view->response([$error_msj, 400]);
            }
        
        //Traer filtrado por un campo y un valor pasado por el usuario.
        } else if (isset($req->query->filtrarPor) && !empty($req->query->filtrarPor)){

            if (!$this->validOption(($req->query->filtrarPor), $this->opcionesDeFiltroDisponibles)) { //si no es opc de campo valida
                $errores[] = 'No se puede filtrar por esa caracteristica(inexistente)';
            } else {
                $filtrarPor = $req->query->filtrarPor;
                $filtro_valor = $req->query->filtro_valor;
                
                $productos = $this->model->traerTodosFiltrados($filtrarPor, $filtro_valor);//traemos los productos de la DB con lso filtros
                if (!$productos) {
                    return $this->view->response(['Error' => 'No existen productos con esa caracteristica'], 400); //400 bad request 
                }

                
                //guardo la información en un arreglo
                $response = [
                    'productos' => $productos
                ];
                // mando los productos y marcas a la vista en forma de arreglo
                return $this->view->response($response, 200);

            }

            if (count($errores) > 0) {
                $erroresString = implode(', ', $errores);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $erroresString;
                return $this->view->response([$error_msj, 400]);
            }
        } else if (isset($req->query->cantidad) && isset($req->query->numeroDePagina) && !empty($req->query->cantidad) && !empty($req->query->numeroDePagina)) { //si estan seteados los parametros de paginacion

            $totalProductos = $this->model->countProductos();
            if (!$totalProductos) {
                return $this->view->response('Error: No hay productos que traer', 500);
            }
            if ($req->query->cantidad > $totalProductos || $req->query->cantidad < 0) {
                return $this->view->response('Error: fuera de rango cantidad a traer', 400);
            }

            $cantidad = $req->query->cantidad;
            $numeroDePagina = $req->query->numeroDePagina;

            $productos = $this->model->traerPaginados($cantidad, $numeroDePagina);

            if (!$productos) {
                return $this->view->response('Error: No hay productos que traer', 500);
            } else {
                return $this->view->response($productos, 200);
            }
        } else {
            $productos = $this->model->getProductos();
        }

        //verifico si trajo los productos de la db
        if (!$productos) {
            $errores[] = 'Ocurrio un problema al obtener los datos';
        } else {
            //hacemos un foreach para buscar cada marca de los productos en la db y asignarselos
            // hago esto porque el prod tiene el id de la marca ,no el nombre.Al nombre lo busco en la tabla nombre
            foreach ($productos as $producto) {
                $marca = $this->marcaModel->getMarcaById($producto->marca); //buscamos el marca en la base de datos
                if (!$marca) { //en caso de que no se encontrara la marca avisar
                    $marca = 'no registrado, probablemente fue eliminado';
                }
                $producto->marca = $marca->nombre; //le asignamos el nombre de la marca al producto
            }
        }

        if (count($errores) > 0) {
            return $this->view->response(['error' => 'Ocurrio un problema al obtener los datos: ' . implode(', ', $errores)], 500);
        }

        //guardo la información en un arreglo
        $response = [
            'productos' => $productos
        ];
        // mando los productos y marcas a la vista en forma de arreglo
        return $this->view->response($response, 200);
    }

    public function get($req, $res)
    {
        // obtengo el id de la tarea desde la ruta
        $id = $req->params->id;

        // obtengo un producto de la DB
        $producto = $this->model->get($id);

        if ($producto) { //si trae producto 
            //capturo el id_marca de esa producto
            $idMarca = $producto->marca;
            //busco la marca por medio del id_marca
            $marca = $this->marcaModel->getMarcaById($idMarca);
            $producto->marca = $marca->nombre;

            $response = [
                'producto' => $producto,
            ];

            // mando la producto y la marca a la vista 
            return $this->view->response($response);
        } else {
            return $this->view->response('Error: No se encontró la producto', 404);
        }
    }

    public function updateProducto($req, $res)
    {

        if(!$res->user){
            return $this->view->response("No tiene permisos para modificar productos", 401);
        }

        $errores = [];

        $id = $req->params->id;


        // obtengo un producto de la DB 
        $producto = $this->model->get($id);
        // chequear si existe lo que se quiere modificar  
        if (!$producto) {
            return $this->view->response("No Existe el producto con el id: $id", 404);
        }

        // tomar datos del form ingresados por el usuario y validarlos , funcion importante del contoller 

        // VALIDACIONES nombre
        // Verificar si el campo existe, no es null, ni vacío
        if (!isset($req->body->nombre) || is_null($req->body->nombre) || trim($req->body->nombre) === '') {
            $errores[] = "El campo nombre es requerido";
        }
        
        // *********    VALIDACIONES descripcion     *********//
        // Verificar si el campo existe, no es null, ni vacío
        if (!isset($req->body->descripcion) || is_null(intval($req->body->descripcion)) || trim(intval(($req->body->descripcion)) === '')) {
            $errores[] = "El campo descripcion es requerido";
        }

        //*********     VALIDACIONES MARCA   *********//
        // que la MArca sea requerida
        if (!isset($req->body->marca) || is_null($req->body->marca) || trim($req->body->marca) === '') {
            $errores[] = "marca es requerido";
        }
        // existe la marca en la bd ? 
        $idMarca = $producto->marca;
        if (!$this->marcaModel->getMarcaById($idMarca)) {
            $errores[] = "La marca no existe en la base de datos ";
        }

        //*********     VALIDACIONES sexo     *********//
        //verificar si ingresa una opcion no valida en el select del input status 
        if (!$this->validOption($req->body->sexo, $this->opcionesSexo)) {
            $errores[] = "No seleccionó una opción válida para el campo sexo";
        }
      
        //*********     VALIDACIONES stock     *********//
       if (!$this->validOption($req->body->stock, $this->opcionesStock)) {
            $errores[] = "No seleccionó una opción válida para el campo stock";
        }

        //*********     VALIDACIONES precio     *********//
        if (!isset($req->body->precio) || is_null($req->body->precio) || trim($req->body->precio) === '') {
            $errores[] = "precio es requerido";
        }

         //*********     VALIDACIONES presentacion     *********//
        if (!isset($req->body->presentacion) || is_null($req->body->presentacion) || trim($req->body->presentacion) === '') {
            $errores[] = "presentacion es requerido";
        }

        // si hay errores 
        if (count($errores) > 0) {
            $erroresString = implode(", ", $errores); //convierto el areglo de errores a string
            return $this->view->response($erroresString,400);
        } // si los datos del usuario pasaron todas las validaciones 
        else {
            // si no hay errores 

            $nombre = $req->body->nombre; //name del formulario 
            $descripcion = $req->body->descripcion; 
            $marca = intval($req->body->marca); //quedarse sólo con la parte entera 
            $sexo = $req->body->sexo;
            $stock = intval($req->body->stock);
            $precio = intval($req->body->precio);
            $presentacion = intval($req->body->presentacion);
          

            $filasModificadas = $this->model->modificar($id, $nombre, $descripcion, $marca, $sexo, $stock, $precio, $presentacion);

                 // no se modifico nungun campo 
            if (!$filasModificadas) {
                $this->view->response('Error: No se pudo modificar', 500);
            }

            // obtengo la producto modificada y la devuelvo en la respuesta
            $productoModificado = $this->model->get($id);
            $this->view->response($productoModificado, 200);
        }
    }


    public function validOption($campo, $opcionesCampos)
    {
        $valido = false;
        for ($i = 0; $i < count($opcionesCampos); $i++) {
            if ($opcionesCampos[$i] == $campo) {
                $valido = true;
            }
        }
        return $valido;
    }
}

