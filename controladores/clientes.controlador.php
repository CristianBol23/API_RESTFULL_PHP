<?php

    class ControladorClientes{
        
        public function create($datos){

            //echo "<pre>"; print_r($datos); echo "<pre>";

            // Validación de datos
            // Data validation

            /*
                Validar nombre
                Preguntamos si existe el campo nombre y usamos preg_match con el patrón de permitir solo letras
                
                Name validation
                We asked if the name field exists and we use preg_match with the pattern to allow only letters
            */
            if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["nombre"])){
                $json=array(
                    "status"=>404,
                    "detalle"=>"Error en el campo de nombre, solo ingrese letras"
                );
        
                echo json_encode($json,true);  
                return;
            }

            // Validar apellido
            // Last name validation 
            if(isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["apellido"])){
                $json=array(
                    "status"=>404,
                    "detalle"=>"Error en el campo de apellido, solo ingrese letras"
                );
        
                echo json_encode($json,true);  
                return;
            }

            // Validar email
            // Email validation 
            if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["email"])){
                $json=array(
                    "status"=>404,
                    "detalle"=>"Error en el campo de email, ingrese un email correcto"
                );
        
                echo json_encode($json,true);  
                return;
            }

            // Validar email repetido
            // Validate duplicate email
            $clientes = ModeloClientes::index("clientes");
            
            foreach($clientes as $key=>$value){
                if($value["email"] == $datos["email"]){
                    $json=array(
                        "status"=>404,
                        "detalle"=>"Error el email está repetido"
                    );
            
                    echo json_encode($json,true);  
                    return;
                }
            }

            // Generación credenciales del cliente
            // Generación credentials client

            /*
                La función crypt pide un hash, que es una cadena de caracteres, con lo cual genera esa llave
                o id que necesitamos. En este caso reemplazamos el simbolo $ por una letra.

                The crypt function asks for a hash, which is a string of characters, with which it generates
                the key or id that we need. In this case we replace the $ simbol with a letter.
            */
            $id_cliente = str_replace("$","c",crypt($datos["nombre"].$datos["apellido"].$datos["email"] , '$2a$07$afartwetsdAD52356FEDGsfhsd$'));

            $llave_secreta = str_replace("$","a",crypt($datos["email"].$datos["apellido"].$datos["nombre"] , '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
            
            /*
                Traemos los datos que vienen desde rutas (nombre, apellido y email) para agregarlos junto
                al resto, con el id_cliente y llave_secreta generados anteriormente, además de la fecha de la 
                creación y actualización.

                We bring the data that comes from routes (nombre, apellido and email) to add them together with
                the rest, with the id_cliente and llave_secreta generated previously, in addition to the date 
                of creation and update.
            */
            $datos=array(
                "nombre"=>$datos["nombre"],
                "apellido"=>$datos["apellido"],
                "email"=>$datos["email"],
                "id_cliente"=>$id_cliente,
                "llave_secreta"=>$llave_secreta,
                "created_at"=>date('Y-m-d h:i:s'),
                "updated_at"=>date('Y-m-d h:i:s')
            );

            /*
                Traemos la función create del ModeloClientes pasandole el nombre de la tabla (contenido en
                $clientes) y los datos para agregar.

                We bring the create funcion of the ModeloClientes passing it the name of the table 
                (contained in $clientes) and the data to add.
            */
            $create = ModeloClientes::create($clientes,$datos);

            if($create=="ok"){
                $json=array(
                    "status"=>404,
                    "detalle"=>"se genero sus credenciales",
                    "credenciales"=>$id_cliente,
                    "llave_secreta"=>$llave_secreta
                );
        
                echo json_encode($json,true);  
                return;
            }
        }

        public function update($id, $datos){
            // Validar credenciales del cliente
            // Validate client credentials 
            $clientes = ModeloClientes::index("clientes");

            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key=>$valueCliente){
                    //if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){
                    if( "Basic ".base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"]) ){
                        // Verificar que el cliente solo pueda actualizar su propio usuario
                        // Verify that the cliente can only update its own user
                        if($valueCliente["id"] != $id){
                            $json = array(
                                "status" => 403,
                                "detalle" => "No tienes permiso para editar este usuario"
                            );

                            echo json_encode($json, true);
                            return;
                        }

                        // Validar datos
                        // Data validation
    					foreach ($datos as $key => $valueDatos) {
                            if ($key === "nombre" || $key === "apellido") {
                                // Validación para nombre y apellido (sin caracteres especiales)
                                if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', $valueDatos)) {
                                    $json = array(
                                        "status" => 404,
                                        "detalle" => "Error en el campo $key: solo se permiten letras y espacios."
                                    );
                                    echo json_encode($json, true);
                                    return;
                                }
                            } elseif ($key === "email") {
                                // Validación para email
                                if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $valueDatos)) {
                                    $json = array(
                                        "status" => 404,
                                        "detalle" => "Error en el campo email: formato no válido."
                                    );
                                    echo json_encode($json, true);
                                    return;
                                }
                            }
                        }

                        // Llevar datos al modelo
                        // Bringing data into the model
                        $datos = array(
                            "id"=>$id,
                            "nombre"=>$datos["nombre"],
                            "apellido"=>$datos["apellido"],
                            "email"=>$datos["email"],
                            "updated_at"=>date('Y-m-d h:i:s')
                        );

                        $update = ModeloClientes::update("clientes", $datos);

                        if($update=="ok"){
                            $json=array(
                                "status"=>200,
                                "detalle"=>"Actualización exitosa, cliente actualizado"
                            );
                    
                            echo json_encode($json,true);  
                            return;
                        }else{
                            $json=array(
                                "status"=>404,
                                "detalle"=>"Error al actualizar el cliente"
                            );
                    
                            echo json_encode($json,true);  
                            return;
                        }
                    }
                }
            }
        }

        // Eliminar cliente
        // Client delete
        public function delete($id){
            // Obtener todos los clientes y cursos
            // Get all clients and courses
            $clientes = ModeloClientes::index("clientes");
            $cursos = ModeloCursos::index("cursos","clientes",null,null);

            // Validar credenciales del cliente
            // Validate client credentials 
            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key=>$valueCliente){
                    //if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){
                    if( "Basic ".base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"]) ){
                        // Verificar que el cliente solo pueda eliminar su propio usuario
                        // Verify that the cliente can only delete its own user
                        if($valueCliente["id"] != $id){
                            $json = array(
                                "status" => 403,
                                "detalle" => "No tienes permiso para editar este usuario"
                            );

                            echo json_encode($json, true);
                            return;
                        }

                        foreach($cursos as $key=>$valueCursos){
                            if($id==$valueCursos->id_creador){
                                $json = array(
                                    "status" => 403,
                                    "detalle" => "El cliente tiene cursos asociados, no se puede eliminar"
                                );
    
                                echo json_encode($json, true);
                                return;
                            }
                        }

                        // Llevar datos al modelo
                        // Bringing data into the model
                        $delete = ModeloClientes::delete("clientes",$id);

                        if($delete=="ok"){
                            $json=array(
                                "status"=>200,
                                "detalle"=>"El cliente se borro exitosamente"
                            );
                            
                            echo json_encode($json,true);  
                            return;
                        }
                    }
                }
            }
        }
    }

?>