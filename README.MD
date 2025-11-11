# WEB-II-Trabajo-Práctico - Ecommerce Perfumes - PARTE 2.
Proyecto creado para la cátedra de Web2, Facultad UNICEN, carrera TUDAI.

## 📋 Numero de grupo: 
48

## 📋 Nombres de los integrantes del grupo: 
<ul>
<li><strong> Javier Ignacio Rivarola - nachovisa1@gmail.com </strong></li>
<li><strong> Micaela Jazmín Breijo - micabreijo@gmail.com </strong></li>
</ul>

## 🧠 Tematica:
Se trata de una API REST pública que brinda integración con otros sistemas a través de varios servicios, utilizando una base de datos compartida con el trabajo anterior. Esta API se construyó para ser RESTfull y cumple con varios requisitos funcionales y no funcionales.

## ✅ Requerimientos Funcionales
Las entidades principales de la base de datos son productos y marcas . Para cada una se construyó una tabla con sus respectivos atributos.
<ul>
<li> La API ofrece servicios para listar, agregar y modificar datos de una base de datos.</li>
<li> Los servicios permiten ordenar, filtrar y paginar los resultados.</li>
<li> Se asegura que se manejen los códigos de estado HTTP adecuados (200, 201, 400, 404, 500).</li>
<li> La autenticación de token está implementada en los servicios para modificaciones de datos (PUT).</li>
</ul>

## ✅ Acceso Administrador

<ul>
<li Autenticación : Los administradores deben tener un jwt para acceder a las funcionalidades de administración de datos.</li>
<li>Administración de Marcas : Los administradores pueden agregar y editar Marcas específicas. Cada Marca debe estar asociada a un producto.</li>
</ul>

## ✅Diagrama de Datos

<img width="952" height="607" alt="Captura de pantalla 2025-09-23 194921" src="https://github.com/user-attachments/assets/a92a6dfe-b1f6-4c09-af7d-914411362a8e" />

## ✅Instalación y configurar

Importar la base de datos : Importar el archivo db_perfumeria en PHPMyAdmin para cargar la estructura y datos iniciales.

## ✅Administrador

<ul>
<li>Usuario: webadmin</li>
<li>Contrasenia: admin</li>
</ul>

## ✅Ruta

http://localhost/BACKUP/

# PRUEBAS DE ENDPOINTS.

## ✅1 OBTENER TODOS LOS PRODUCTOS - Sin query params.

GET http://localhost/BACKUP/api/productos

## ✅2 OBTENER PRODUCTOS ORDENADOS

## A / Nombre Ascendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=nombre&modo=ASC

## B / Precio Descendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=precio&modo=DESC

## C / Descripción Ascendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=descripcion&modo=ASC

## D / Marca Ascendente.

GET http://localhost/BACKUP/BACKUP/api/productos?ordenarPor=marca&modo=DESC

TENER EN CUENTA EN ESTE ENDPOINT QUE LA MARCA ES NÚMERICA EN LA BASE DE DATOS.

## E / Sexo Ascendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=sexo&modo=ASC

## F / Stock Descendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=stock&modo=DESC

## G / Stock Ascendente.

GET http://localhost/BACKUP/api/productos?ordenarPor=precio&modo=ASC

## ✅3 OBTENER PRODUCTOS FILTRADOS

Filtrar por Stock.

GET http://localhost/BACKUP/api/productos?filtrarPor=stock&filtro_valor=1

GET http://localhost/BACKUP/BACKUP/api/productos?filtrarPor=stock&filtro_valor=0

## ✅4 OBTENER PRODUCTOS PAGINADOS

Primera página con 5 productos.

GET http://localhost/BACKUP/api/productos?cantidad=5&numeroDePagina=1

Segunda página con 10 productos.

GET http://localhost/BACKUP/api/productos?cantidad=10&numeroDePagina=2

## ✅5 OBTENER PRODUCTOS PAGINADOS

Errores

Ordenamiento inexistente
GET http://localhost/BACKUP/api/productos?ordenarPor=color&modo=ASC

Ordenamiento inexistente
GET http://localhost/BACKUP/api/productos?ordenarPor=nombre&modo=RANDOM

Caracteristica inválida
GET http://localhost/BACKUP/api/productos?filtrarPor=color&filtro_valor=rojo

Fuera de rango
GET http://localhost/BACKUP/api/productos?cantidad=99999&numeroDePagina=1

Producto erroneo
GET http://localhost/BACKUP/api/productos/8


ENDPOINT DE PRUEBA

5. Endpoints con Errores (Para Probar Validaciones)\ GET http://localhost/BACKUP/api/productos?ordenarPor=color&modo=ASC
Modo de ordenamiento inválido:\ GET http://localhost/BACKUP/api/productos?ordenarPor=nombre&modo=RANDOM
Filtrar por característica no disponible:\ GET http://localhost/BACKUP/api/productos?filtrarPor=color&filtro_valor=rojo
Cantidad fuera de rango:\ GET http://localhost/BACKUP/api/productos?cantidad=99999&numeroDePagina=1
Traer un solo producto:\ http://localhost/BACKUP/api/productos/8
________________________________________
Update: Pruebas de Validación (Errores esperados)\ Error: Falta el campo nombre\ PUT http://localhost/BACKUP/api/productos/8 BODY raw:
{
    "descripcion": "Solo descripción",
    "marca": 1,
    "sexo": "M",
    "stock": 1,
    "precio": 10000,
    "presentacion": 100
}
Get token:\ Información a poner en Authorization basic:\ user: admin\ password: webadmin\ PUT http://localhost/BACKUP/api/usuarios/token
________________________________________
Update: Correcto\ PUT http://localhost/BACKUP/api/productos/8\ Información a poner en Authorization bearer token:\ El token obtenido al hacer Get token
BODY raw:
{
    "nombre": "212 VIP modificado",
    "descripcion": "212 VIP de Carolina Herrera es una fragancia oriental vainilla",
    "marca": 1,
    "sexo": "F",
    "stock": 1,
    "precio": 35000,
    "presentacion": 80
}






