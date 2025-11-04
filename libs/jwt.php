<?php
    // funciones de crear y validar token 
    
    function createJWT($payload) {
        // Header: tiene informacion sobre los metodos de encriptacion 
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Payload: suele contener informacion del usuario 
        $payload = json_encode($payload);

        // Base64Url: "encripta" el header y el payload recibido en base64
        $header = base64_encode($header);
        $header = str_replace(['+', '/', '='], ['-', '_', ''], $header);
        
        $payload = base64_encode($payload);
        $payload = str_replace(['+', '/', '='], ['-', '_', ''], $payload);

        // Firma : se genera en base a la informacion del header y payload (en base64) y la clave secreta
        $signature = hash_hmac('sha256', $header . "." . $payload, 'esteEsMiSecreto', true);
        $signature = base64_encode($signature);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

        // JWT
        $jwt = $header . "." . $payload . "." . $signature;
        return $jwt;//es un arreglo(string) 
    }

    function validateJWT($jwt) {//RECIBE EL TOKEN DE LA SOLICITUD
        $jwt = explode('.', $jwt); //lo separa en un [ header,payload ,signature ]
        if(count($jwt) != 3) {//valida el largo 
            return null;
        }
        $header = $jwt[0];
        $payload = $jwt[1];
        $signature = $jwt[2];

            // Genera una firma válida usando el mismo algoritmo y clave secreta.
        $valid_signature = hash_hmac('sha256', $header . "." . $payload, 'esteEsMiSecreto', true);
        $valid_signature = base64_encode($valid_signature); // Codifica la firma en Base64.
        $valid_signature = str_replace(['+', '/', '='], ['-', '_', ''], $valid_signature); // Ajusta el formato Base64URL.

        if($signature != $valid_signature) {// Si la firma no coincide, retorna null.
            return null;
        }

        $payload = base64_decode($payload);// Decodifica el payload de Base64.
        $payload = json_decode($payload);// Convierte el payload a un objeto JSON.


        if($payload->exp < time()) {// Si el token ha expirado, retorna null.
            return null;
        }

        return $payload; // Retorna el contenido del payload si es válido y no ha expirado.
    }

    // En resumen, esta función verifica que el token tenga tres partes, valida la firma usando un secreto y asegura que no haya expirado. Si todo es correcto, devuelve el contenido del payload.







