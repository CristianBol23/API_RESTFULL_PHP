<?php

    /* 
        Conexión a la base de datos, se hace por medio de un metodo de PHP llamado PDO, donde pide el host
        (en este caso localhost), el nombre de la base de datos (en este caso api-rest) y el usuario y la 
        contraseña (en este caso root y no tiene contraseña).
        Luego se le manda el método exec, definiendo el formato utf-8.
        Todo esto dentro de una clase llamada Conexion la cual tiene como funcion conectar que se hara uso más
        adelante, retornando todos los datos mencionados anteriormente.

        Connection to the database, is done through PHP method called PDO, where it asks for the host (in this
        case localhost), the name of the database (in this case api-rest) and the user and password (in this
        case root and it has no password).
        Then the exec method is sent, defining the utf-8 format.
        All this within a class called Conexion which has the function of connect that will be used 
        later, returning all the data mentioned above.

    */
    class Conexion{

        static public function conectar(){
            $link = new PDO("mysql:host=localhost;dbname=api-rest;","root","");

            $link->exec("set names utf8");

            return $link;
        }

    }

?>