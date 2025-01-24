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
                "create_at"=>date('Y-m-d h:i:s'),
                "update_at"=>date('Y-m-d h:i:s')
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

    }

?>