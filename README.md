API RESTful para Gestión de Cursos y Clientes.

Este proyecto es una API RESTful desarrollada en PHP que permite la creación, actualización, eliminación y gestión de cursos relacionados con clientes. La API utiliza el patrón de diseño MVC (Modelo-Vista-Controlador), está respaldada por una base de datos y cuenta con documentación básica para facilitar su uso.

Características principales
Gestión de clientes:
  Crear nuevos clientes.

Gestión de cursos:
  Crear nuevos cursos asociados a un cliente.
  Consultar cursos disponibles.
  Actualizar información de un curso.
  Eliminar cursos.
  
Utiliza el patrón MVC para mantener el código limpio y modular.
Base de datos SQL para almacenar la información.
Documentación básica para facilitar la integración con otros sistemas.

Rutas para los cursos de la API
    GET    /cursos    Obtiene la lista de cursos.
    GET    /cursos/{id}    Obtiene detalles de un curso por ID.
    GET    /cursos/pagina/{número}    Obtiene la lista de cursos por paginación
    POST    /cursos    Crea un nuevo curso.
    PUT    /cursos/{id}    Actualiza un curso por ID.
    DELETE    /cursos/{id}    Elimina un curso por ID.
