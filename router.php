<?php
// Requiere las librerias
require_once 'libs/router.php'; // Requiere la clase Respuesta

// Requiere los controladores
require_once 'app/controller/producto.api.controller.php';  
require_once 'app/controller/auth.api.controller.php';


// Requiere los middlewares
require_once 'app/middlewares/jwt.auth.middleware.php';


$router = new Router();


$router -> addMiddleware(new JwtAuthMiddleware());

#                       endpoint                        verbo                   controller                        método
$router->addRoute('productos',           'GET',                'ProductoApiController',          'traerProductos');
$router->addRoute('productos/:id',           'GET',                'ProductoApiController',          'get');
$router->addRoute('productos/:id',           'PUT',                'ProductoApiController',          'updateProducto');
$router->addRoute('productos', 'POST', 'ProductoApiController', 'create');
$router->addRoute( 'usuarios/token',         'GET',    'AuthApiController',      'loginGetToken');

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);

