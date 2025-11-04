<?php
    class JWTAuthMiddleware {// Define una clase para el middleware de autenticación JWT.
        public function run($req, $res) {// Método que se ejecuta para validar el token JWT en cada solicitud.
            $auth_header = $_SERVER['HTTP_AUTHORIZATION']; // "Bearer un.token.firma" Obtiene el encabezado de autorización, que contiene "Bearer un.token.firma".
            $auth_header = explode(' ', $auth_header); //  Divide el encabezado en ["Bearer", "un.token.firma"].
            if(count($auth_header) != 2) {// Si no tiene exactamente dos elementos (por ejemplo, "Bearer" y el token), finaliza.
                return;
            }
            if($auth_header[0] != 'Bearer') {// Si el primer elemento no es "Bearer", finaliza.
                return;
            }
            $jwt = $auth_header[1];// Guarda el token JWT de la segunda posición del array.
        
            // VALIDA el token Q LLEGA EN LA SOLICITUD,  Valida el JWT y asigna el usuario autenticado a `$res->user`.
            $res->user = validateJWT($jwt);
        }
    }

//     en PHP es una variable de servidor que almacena el encabezado de autorización HTTP, que se utiliza comúnmente para la autenticación y autorización de usuarios. Este encabezado suele contener un token de autenticación (como un token JWT) o credenciales en un formato de autenticación básica, como "Basic <credenciales en Base64>".

// Esta variable puede ser útil en aplicaciones web donde necesitas validar el acceso de un usuario basado en un token o un sistema de autenticación. Sin embargo, en algunos entornos, como servidores compartidos o ciertos servicios de hosting, $_SERVER['HTTP_AUTHORIZATION'] podría no estar disponible directamente por razones de configuración de Apache o Nginx. En esos casos, puede ser necesario buscar el encabezado de autorización a través de otros medios, como:

// Utilizar apache_request_headers(): En Apache, 
// Una vez obtenido, el valor de HTTP_AUTHORIZATION puede utilizarse para validar y autenticar las solicitudes entrantes en tu aplicación.






