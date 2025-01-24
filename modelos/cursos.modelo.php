<?php

    require_once "conexion.php";

    class ModeloCursos{

        static public function index($tabla1,$tabla2,$cantidad,$desde){

            if($cantidad!=null){
                /* 
                $stmt llama a la clase Conexion y con los : llama su funcion conectar junto al metodo PHP 
                prepare donde se le pasa la linea de sql y llamar a la tabla que necesitamos

                $stmt calls the Conexion class and with the : calls its conectar function together with the PHP
                prepare method where the sql line is passed and calls the table we need. 
                */
                $stmt = Conexion::conectar()->prepare("SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion,
                $tabla1.instructor, $tabla1.imagen, $tabla1.precio, $tabla1.id_creador, $tabla2.nombre,
                $tabla2.apellido FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id
                LIMIT $desde,$cantidad");
            }else{
                $stmt = Conexion::conectar()->prepare("SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion,
                $tabla1.instructor, $tabla1.imagen, $tabla1.precio, $tabla1.id_creador, $tabla2.nombre,
                $tabla2.apellido FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id");
            }
           

            // Para ejecutar stmt
            // To run stmt
            $stmt->execute();

            /*
                Retornamos stmt junto a la funcion fetchAll para devolver todos los datos de la base de datos.
                Dentro del fetchAll llamamos un parametro del PDO para que devuelva la informacion sin 
                duplicar, solo las propiedades de la tabla (FETCH_CLASS).

                We return stmt along with the fetchAll function to return all the data of the database.
                Inside the fetchAll we call a parameter of the PDO to return the information without duplicating 
                it, only the properties of the table (FETCH_CLASS).
            */
            return $stmt->fetchAll(PDO::FETCH_CLASS);

            // Cerramos la conexión
            // We closed the conection
            $stmt->close();

            $stmt=null;
        }

        static public function create($tabla, $datos){
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(titulo, descripcion, 
            instructor, imagen, precio, id_creador, created_at, updated_at) 
            VALUES (:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)");

            /*
                bindParam enlaza el :nombre con el array $datos y el PARAM_STR lo define como parametro de tipo
                String
            */
            $stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(":id_creador", $datos["id_creador"], PDO::PARAM_STR);
            $stmt->bindParam(":created_at", $datos["created_at"], PDO::PARAM_STR);
            $stmt->bindParam(":updated_at", $datos["updated_at"], PDO::PARAM_STR);

            if($stmt->execute()){
                return "ok";
            }else{
                print_r(Conexion::conectar()->errorInfo());
            }

            $stmt->close();
            $stmt=null;
        }

        static public function show($tabla1, $tabla2, $id){
            /* 
                $stmt llama a la clase Conexion y con los : llama su funcion conectar junto al metodo PHP 
                prepare donde se le pasa la linea de sql y llamar a la tabla que necesitamos

                $stmt calls the Conexion class and with the : calls its conectar function together with the PHP
                prepare method where the sql line is passed and calls the table we need. 
            */
            $stmt = Conexion::conectar()->prepare("SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion,
                $tabla1.instructor, $tabla1.imagen, $tabla1.precio, $tabla1.id_creador, $tabla2.nombre,
                $tabla2.apellido FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id WHERE $tabla1.id=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);


            // Para ejecutar stmt
            // To run stmt
            $stmt->execute();

            /*
                Retornamos stmt junto a la funcion fetchAll para devolver todos los datos de la base de datos.
                Dentro del fetchAll llamamos un parametro del PDO para que devuelva la informacion sin 
                duplicar, solo las propiedades de la tabla (FETCH_CLASS).

                We return stmt along with the fetchAll function to return all the data of the database.
                Inside the fetchAll we call a parameter of the PDO to return the information without duplicating 
                it, only the properties of the table (FETCH_CLASS).
            */
            return $stmt->fetchAll(PDO::FETCH_CLASS);

            // Cerramos la conexión
            // We closed the conection
            $stmt->close();

            $stmt=null;
        }

        static public function update($tabla, $datos){
            $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET titulo=:titulo,descripcion=:descripcion,
            instructor=:instructor,imagen=:imagen,precio=:precio,updated_at=:updated_at WHERE id=:id");

            $stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);
            $stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(":updated_at", $datos["updated_at"], PDO::PARAM_STR);  
            
            if($stmt->execute()){
                return "ok";
            }else{
                print_r(Conexion::conectar()->errorInfo());
            }

            $stmt->close();
            $stmt=null;
        }

        static public function delete($tabla, $id){
            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id=:id");

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

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
