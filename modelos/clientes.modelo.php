<?php

    require_once "conexion.php";


    class ModeloClientes{

        // Mostrar todos los registros
        static public function index($tabla){
            /* 
                $stmt llama a la clase Conexion y con los : llama su funcion conectar junto al metodo PHP 
                prepare donde se le pasa la linea de sql y llamar a la tabla que necesitamos

                $stmt calls the Conexion class and with the : calls its conectar function together with the PHP
                prepare method where the sql line is passed and calls the table we need. 
            */
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

            // Para ejecutar stmt
            // To run stmt
            $stmt->execute();

            /*
                Retornamos stmt junto a la funcion fetchAll para devolver todos los datos de la base de datos.

                We return stmt along with the fetchAll function to return all the data of the database.
            */
            return $stmt->fetchAll();

            // Cerramos la conexión
            // We closed the conection
            $stmt->close();

            $stmt=null;
        }

        // Agregar a la base de datos
        // Add to database
        static public function create($tabla,$datos){
            $stmt = Conexion::conectar()->prepare("
                INSERT INTO clientes(nombre, apellido,email, id_cliente, llave_secreta, 
                created_at, updated_at) VALUES (:nombre, :apellido,:email, :id_cliente, :llave_secreta, 
                :created_at, :updated_at)");

            /*
                bindParam enlaza el :nombre con el array $datos y el PARAM_STR lo define como parametro de tipo
                String
            */
            $stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt -> bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
            $stmt -> bindParam(":email", $datos["email"], PDO::PARAM_STR);
            $stmt -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
            $stmt -> bindParam(":llave_secreta", $datos["llave_secreta"], PDO::PARAM_STR);
            $stmt -> bindParam(":created_at", $datos["created_at"], PDO::PARAM_STR);
            $stmt -> bindParam(":updated_at", $datos["updated_at"], PDO::PARAM_STR);

            if($stmt->execute()){
                return "ok";
            }else{
                print_r(Conexion::conectar()->errorInfo());
            }

            $stmt->close();
            $stmt=null;
        }
    }

?>