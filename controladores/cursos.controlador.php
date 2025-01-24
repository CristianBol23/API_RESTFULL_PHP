<?php

    class ControladorCursos{
        
        public function index($pagina){

            // Validar credenciales del cliente
            // Validate client credentials 

            $clientes = ModeloClientes::index("clientes");

            /*
                Estas son 2 variables globales de PHP, para el user y el password

                These are 2 global PHP variables, for the user and the password
            */
            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

                /*
                    Recorremos la tabla definida anteriormente por medio del foreach

                    We traverse the table defined above using the foreach
                */ 
                foreach($clientes as $key=>$value){
                    /*
                        Verificamos el usuario y la contraseña por las 2 variables globales con la tabla además
                        de usar el método base64_encode para codificar más los datos

                        We verify the user and the password by the 2 global variables with the table in addition
                        to using the base64_encode method to further encode the data
                    */
                    if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                       base64_encode($value["id_cliente"].":".$value["llave_secreta"])){
                        if($pagina!=null){
                            $cantidad=10;
                            $desde=(($pagina-1)*$cantidad);

                            $cursos = ModeloCursos::index("cursos","clientes",$cantidad,$desde);
                        }else{
                            $cursos = ModeloCursos::index("cursos","clientes",null,null);

                            
                        }

                        $json=array(
                            "detalle"=>$cursos
                        );
                
                        echo json_encode($json,true);  
                        return;
                    }
                }
            } 
        }

        public function create($datos){

            // Validar credenciales del cliente
            // Validate client credentials 
            $clientes = ModeloClientes::index("clientes");

            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key=>$valueCliente){
                    if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){
                        // Validar datos
                        // Data validate
                        foreach($datos as $key=>$valueDatos){
                            /* 
                                Por medio del if valida si existen los datos y que se permitan simbolos y 
                                letras por medio del preg_match.

                                Using if, validates whether the data exists and that simbols and 
                                letter are allowed using preg_match.
                            */
                            if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){

                                $json = array(
    
                                    "status"=>404,
                                    "detalle"=>"Error en el campo ".$key
    
                                );
    
                                echo json_encode($json, true);
    
                                return;
                            }
                        }

                        // Validar que el titulo o la descripcion no esten repetidos 
                        // Validate that the title or the description is not repeated
                        $cursos = ModeloCursos::index("cursos","clientes",null,null);

                        foreach($cursos as $key=>$value){
                            if($value->titulo==$datos["titulo"]){
                                $json = array(
    
                                    "status"=>404,
                                    "detalle"=>"El titulo ya existe en la base de datos"
    
                                );
    
                                echo json_encode($json, true);
    
                                return;
                            }

                            if($value->descripcion==$datos["descripcion"]){
                                $json = array(
    
                                    "status"=>404,
                                    "detalle"=>"La descripción ya existe en la base de datos"
    
                                );
    
                                echo json_encode($json, true);
    
                                return;
                            }
                        }

                        // Llevar datos al modelo
                        // Bringing data into the model
                        $datos=array(
                            "titulo"=>$datos["titulo"],
                            "descripcion"=>$datos["descripcion"],
                            "instructor"=>$datos["instructor"],
                            "imagen"=>$datos["imagen"],
                            "precio"=>$datos["precio"],
                            "id_creador"=>$valueCliente["id"],
                            "create_at"=>date('Y-m-d h:i:s'),
                            "update_at"=>date('Y-m-d h:i:s')
                        );

                        $create = ModeloCursos::create("cursos", $datos);

                        // Respuesta del ModeloCurso
                        // ModelCurso response
                        if($create=="ok"){
                            $json=array(
                                "status"=>200,
                                "detalle"=>"Registro exitoso, curso registrado"
                            );
                    
                            echo json_encode($json,true);  
                            return;
                        }
                    }
                }
            } 
        }

        // Mostrar cursos desde el id
        // Show course from the id
        public function show($id){
            // Validar credenciales del cliente
            // Validate client credentials 
            $clientes = ModeloClientes::index("clientes");

            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key=>$valueCliente){
                    if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){
                    
                        // Mostrar los cursos por medio del id
                        // Display courses by id
                        $curso = ModeloCursos::show("cursos","clientes",$id);

                        // Verificar que el curso exista
                        // Verify that the course exists
                        if(!empty($curso)){
                            $json=array(
                                "status"=>200,
                                "detalle"=>$curso
                            );
                    
                            echo json_encode($json,true);  
                            return;
                        }else{
                            $json=array(
                                "status"=>200,
                                "total_registros"=>0,
                                "detalle"=>"No hay ningun curso registrado"
                            );
                    
                            echo json_encode($json,true);  
                            return;
                        }
                    }
                }
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
                        // Validar datos
                        // Data validation
    					foreach ($datos as $key => $valueDatos) {
						    if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){
							    $json = array(
								    "status"=>404,
								    "detalle"=>"Error en el campo ".$key
							    );
							    echo json_encode($json, true);
					    		return;
					    	}
					    }

                        // Validar id del creador
                        // Validate creator id
                        $curso = ModeloCursos::show("cursos","clientes",$id);

                        foreach($curso as $key=>$valueCurso){
                            if($valueCurso->id_creador == $valueCliente["id"]){
                                // Llevar datos al modelo
                                // Bringing data into the model
                                $datos = array(
                                    "id"=>$id,
                                    "titulo"=>$datos["titulo"],
                                    "descripcion"=>$datos["descripcion"],
                                    "instructor"=>$datos["instructor"],
                                    "imagen"=>$datos["imagen"],
                                    "precio"=>$datos["precio"],
                                    "updated_at"=>date('Y-m-d h:i:s')
                                );

                                $update = ModeloCursos::update("cursos", $datos);

                                if($update=="ok"){
                                    $json=array(
                                        "status"=>200,
                                        "detalle"=>"Actualización exitosa, curso actualizado"
                                    );
                            
                                    echo json_encode($json,true);  
                                    return;
                                }else{
                                    $json=array(
                                        "status"=>404,
                                        "detalle"=>"No esta autorizado para actualizar este curso"
                                    );
                            
                                    echo json_encode($json,true);  
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Eliminar curso
        // Couse delete
        public function delete($id){
            // Validar credenciales del cliente
            // Validate client credentials 
            $clientes = ModeloClientes::index("clientes");

            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key=>$valueCliente){
                    //if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){
                    if( "Basic ".base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"]) ){
                        // Validar id del creador
                        // Validate creator id
                        $curso = ModeloCursos::show("cursos","clientes",$id);

                        foreach($curso as $key=>$valueCurso){
                            if($valueCurso->id_creador == $valueCliente["id"]){
                                // Llevar datos al modelo
                                // Bringing data into the model
                                $delete = ModeloCursos::delete("cursos",$id);

                                if($delete=="ok"){
                                    $json=array(
                                        "status"=>200,
                                        "detalle"=>"El curso se borro exitosamente"
                                    );
                            
                                    echo json_encode($json,true);  
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

?>