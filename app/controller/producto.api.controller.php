<?php

require_once './app/model/producto_model.php';
require_once './app/model/marca_model.php';
require_once './app/view/json.view.php';


require_once './libs/jwt.php';

class ProductoApiController {

    private $marcaModel;
    private $model;
    private $view;

    private $opcionesColumnasDisponibles = ['nombre', 'descripcion', 'marca', 'sexo' ,'stock', 'precio', 'presentacion']; //columnas por las que se puede ordenar
    private $opcionModoDisponible = ['ASC', 'DESC']; //modos de ordenamiento disponibles
    private $opcionesDeFiltroDisponibles = ['id', 'stock'];


    function __construct() {

        $this->marcaModel = new MarcaModel();
        $this->model = new ProductoModel();
        $this->view = new JSONView();
    }

    public function getAll($req, $res)  {

        $errores = [];
        $ordenarPor = '';
        $modo = '';

        if (isset($req->query->ordenarPor) && isset($req->query->modo) && !empty($req->query->ordenarPor) && !empty($req->query->modo)) { //si estan seteados los parametros
        //traer todos los productos de la base de datos

            if(!$this->validOption(($req->query->ordenarPor),  $this->opcionesColumnasDisponibles)) { //si no es opc valida
                return $this->view->response('No se puede ordenar por esa caracteristica(inexistente)', 404);
            } else {
                $ordenarPor = $req->query->ordenarPor; 
                
                if (!$this->validOption(($req->query->mode), $this->opcionModoDisponible) ){
                    $errores[] = 'No existe esa modo de orden: solo ascendete y descendente'; //si no es modo(ascendente o desendente) valido
                } else {
                    $modo = $req->query->modo;
                    $productos = $this->model->getTodosOrdenados($ordenarPor, $modo);
                }
            }
 
            if (count($errores) > 0) {
                $erroresString = implode(', ', $errores);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $erroresString;
                return $this->view->response([$error_msj, 400]);
            } 

        }else if (isset($req->query->filtrarPor) && !empty($req->query->filtrarPor)) { 
                
            if (!$this->validOption(($req->query->filtrarPor), $this->opcionesDeFiltroDisponibles)) { //si no es opc valida
                    $errores[] = 'No se puede filtrar por esa caracteristica(inexistente)';  
            } else {
                $filtrarPor = $req->query->filtrarPor;
                $filtro_valor = $req->query->filtro_valor;

                $productos = $this->model->traerTodosFiltrados($filtrarPor, $filtro_valor); 
                if (!$productos) {
                    return $this->view->response(['Error' => 'No existen productos con esa caracteristica'], 400); //400 bad request 
               }
            }

            if (count($errores) > 0) {
                $erroresString = implode(', ', $errores);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $erroresString;
                return $this->view->response([$error_msj, 400]);
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
                foreach ($productos as $producto) {
                    $marca = $this->marcaModel->getMarcaById($producto->id); //buscamos el owner en la base de datos
                    if (!$marca) { //en caso de que no se encontrara el dueño avisar
                        $marca = 'no registrado, probablemente fue eliminado';
                    }
                    $producto->marca = $marca->nombre; //le asignamos el nombre del duenio a el produto
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
        return $this->view->response($response,200);
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
