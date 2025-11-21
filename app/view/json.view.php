<?php

    class JSONView {
        public function response($data, $status = 200) {
            header("Content-Type: application/json");
            $statusText = $this->_requestStatus($status);
            header("HTTP/1.1 $status $statusText");
            echo json_encode($data);
        }

        private function _requestStatus($code) {
            $status = array(
                200 => "Operación Exitosa",
                201 => "Recurso Creado",
                204 => "Sin Contenido",
                400 => "Solicitud Incorrecta",
                401 => "Acceso No Autorizado",
                404 => "Recurso No Encontrado",
                422 => 'Unprocessable Entity',
                500 => "Error en el Servidor"
            );
            if(!isset($status[$code])) {
                $code = 500;
            }
            return $status[$code];
        }
    }
