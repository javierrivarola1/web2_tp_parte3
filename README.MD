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
  
La relación entre estas tablas es de 1 a N , lo que significa que una marca puede tener más de un perfume, pero un perfume sólo puede pertenecer a una marca específica.

## ✅ Acceso Administrador

<ul>
<li Autenticación : Los administradores deben tener un jwt para acceder a las funcionalidades de administración de datos.</li>
<li>Administración de MArcas : Los administradores pueden agregar y editar Marcas específicas. Cada Marca debe estar asociada a un producto.</li>
</ul>

## ✅ Requisitos tecnicos

<ul>
<li>El sistema está basado en el patrón MVC para separar la lógica del negocio, las vistas y el acceso a los datos.</li>
<li>Las vistas se generan utilizando plantillas PHTML.</li>
<li>El sitio utiliza URL semánticas.</li>
</ul>

## ✅ Manual de uso. Carga de productos y marcas
Una vez logueado, el administrador podra agregar tanto marcas como productos nuevos, siendo en el caso de los productos el rango minimo para la presentacion de 20 ml, por lo cual el entero a colocar debera ser un numero mayor o igual a 20.



<img width="952" height="607" alt="Captura de pantalla 2025-09-23 194921" src="https://github.com/user-attachments/assets/a92a6dfe-b1f6-4c09-af7d-914411362a8e" />



ENDPOINT DE PRUEBA
1. Traer Todos los Productos (Sin Query Params)\ GET http://localhost/BACKUP/BACKUP/api/productos
2. Traer Productos Ordenados\ Ordenar por nombre ascendente:\ GET http://localhost/BACKUP/api/productos?ordenarPor=nombre&modo=ASC
Ordenar por precio descendente:\ GET http://localhost/BACKUP/api/productos?ordenarPor=precio&modo=DESC
Otras opciones de ordenamiento:\ GET http://localhost/BACKUP/api/productos?ordenarPor=descripcion&modo=ASC\ GET http://localhost/BACKUP/BACKUP/api/productos?ordenarPor=marca&modo=DESC → (Para este último tener en cuenta que la marca es numérica en la BD)\ GET http://localhost/BACKUP/api/productos?ordenarPor=sexo&modo=ASC\ GET http://localhost/BACKUP/api/productos?ordenarPor=stock&modo=DESC\ GET http://localhost/BACKUP/api/productos?ordenarPor=presentacion&modo=ASC\ GET http://localhost/BACKUP/api/productos?ordenarPor=precio&modo=ASC
3. Traer Productos Filtrados\ Filtrar por stock:\ GET http://localhost/BACKUP/api/productos?filtrarPor=stock&filtro_valor=1\ GET http://localhost/BACKUP/BACKUP/api/productos?filtrarPor=stock&filtro_valor=0
4. Traer Productos Paginados\ Primera página con 5 productos:\ GET http://localhost/BACKUP/api/productos?cantidad=5&numeroDePagina=1
Segunda página con 10 productos:\ GET http://localhost/BACKUP/api/productos?cantidad=10&numeroDePagina=2
5. Endpoints con Errores (Para Probar Validaciones)\ GET http://localhost/BACKUP/api/productos?ordenarPor=color&modo=ASC
Modo de ordenamiento inválido:\ GET http://localhost/BACKUP/api/productos?ordenarPor=nombre&modo=RANDOM
Filtrar por característica no disponible:\ GET http://localhost/BACKUP/api/productos?filtrarPor=color&filtro_valor=rojo
Cantidad fuera de rango:\ GET http://localhost/BACKUP/api/productos?cantidad=99999&numeroDePagina=1
Traer un solo producto:\ http://localhost/BACKUP/api/productos/8
________________________________________
Update: Pruebas de Validación (Errores esperados)\ Error: Falta el campo nombre\ PUT http://localhost/BACKUP/api/productos/8\ BODY raw:
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




