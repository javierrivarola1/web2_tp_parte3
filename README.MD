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

