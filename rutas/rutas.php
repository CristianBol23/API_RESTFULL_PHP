<?php

    /* 
        Esto $_SERVER['REQUEST_URI'], nos sirve para obtener la ruta en la cual se encuentra el usuario
        
        La direccion URL donde se encuentra el usuario la guardamos dentro de $arrayRutas, donde 
        se guarda como un array con indice que se separa por cada /. Es decir:
            [0] =>
            [1] => PROYECTOSWEB
            [2] => api-rest
            [3] => cursos

        This $_SERVER['REQUEST_URI'], helps us to obtain the route in which the user is located.

        The URL where the user is located is stored in $arrayRutas, where
        it is save as an array with an index separated by a /. That is:
            [0] =>
            [1] => PROYECTOSWEB
            [2] => api-rest
            [3] => cursos
    */
    $arrayRutas = explode("/",$_SERVER['REQUEST_URI']);
   // echo "<pre>"; print_r($arrayRutas); echo "<pre>";

   /*  
        La variable pagina viene de la URL y en este if se pregunta si existe y si es númerica

        The variable page comes from the URL and in this if it asks if it exists and if it is numeric
   */
    if(isset($_GET["pagina"]) && is_numeric($_GET["pagina"])){
        $cursos=new ControladorCursos();
        $cursos->index($_GET["pagina"]);
         
    }else{
    /*
        Dentro del if, vamos a verificar que el usuario si este especificando que necesita, si el usuario
        solo coloca PROYECTOSWEB/api-rest/, salta el array detalle no encontrado, pero si especifica, como 
        por ejemplo, PROYECTOSWEB/api-rest/cursos, no se mostrará este array.

        El array_filter se usa para eliminar todos los indices vacios, en este caso el indice 0 como vimos 
        anteriormente.

        Dentro del else if verifica primero si esta en el indice 3, seguido a esto se verifica (mediante otro if)
        que busca el usuario, en este caso, cursos o registro.

        Inside of if, we will verify that the user is specifying what he needs, if the user only puts 
        PROYECTOSWEB/api-rest/, the "detail no found" array is skipped, but if he specifies, of example, 
        PROYECTOSWEB/api-rest/cursos, this array will not be displayed.

        The array_filter is used to remove all empty indexes, in this case index 0 as we saw previously.

        Inside of else if first check if it is at the index 3, then it verifies(using another if)
        what the user looking for, in this case, courses or register.
    */

        // Aqui no se hacen peticiones
        // No request are made here
        if(count(array_filter($arrayRutas))==2){
            $json=array(
                "detalle"=>"no encontrado"
            );

            echo json_encode($json,true);  
            return; 
        // Aqui se hacen peticiones pero dentro se verifica que peticion es
        // Request are made here but inside it is verify what request it is
        }else if(count(array_filter($arrayRutas))==3){
            // Aqui se hacen peticiones desde cursos
            // Request are made here from courses
            if(array_filter($arrayRutas)[3]=="cursos"){
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
                // Capturar los datos
                    $datos = array(
                        "titulo"=>$_POST["titulo"],
                        "descripcion"=>$_POST["descripcion"],
                        "instructor"=>$_POST["instructor"],
                        "imagen"=>$_POST["imagen"],
                        "precio"=>$_POST["precio"]
                    ); 
                    $cursos = new ControladorCursos;
                    $cursos->create($datos);
                }else if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET"){
                    $cursos = new ControladorCursos;
                    $cursos->index(null);
                }
            // Aqui se hacen peticiones desde registro
            // Request are madre here from register
            }else if(array_filter($arrayRutas)[3]=="registro"){
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
                    // Recibir los datos enviado por el usuario desde la peticion POST 
                    // para guardar en la base de datos
                    // Receive the data sent by the user from the POST request to save in the database
                    $datos = array("nombre"=>$_POST["nombre"],
                        "apellido"=>$_POST["apellido"],
                        "email"=>$_POST["email"],
                    );
                    $clientes = new ControladorClientes;
                    $clientes->create($datos);
                }
            } 
        // Aquí se reciben las peticiones que manden un id desde la URL
        // Requests that send an id from the URL are received here
        }else if(is_numeric(array_filter($arrayRutas)[4])){
            // Aquí se reciben las peticiones para los cursos
            // Requests for the courses are received here
            if(array_filter($arrayRutas)[3]=="cursos"){
                // Petición GET
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET"){
                    $curso = new ControladorCursos;
                    $curso->show(array_filter($arrayRutas)[4]);
                }
                // Petición PUT
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT"){
    
                    // Capturar los datos
                    // Capture the data
                    $datos = array();
    
                    /*
                        Esta es una normativa para la petición PUT, por medio de php://input capturamos los datos 
                        enviados desde el formulario por el usuario con el método file_get_contents, esto necesita un
                        array donde almacenar esa data, por tanto, se le envia el array $datos. Y se necesita el 
                        parse_str para parsear el string que genera el file_get_contents.
                    */
                    parse_str(file_get_contents('php://input'), $datos);
    
                    $editarCurso = new ControladorCursos();
                    $editarCurso->update(array_filter($arrayRutas)[4], $datos);
                }
                // Petición DELETE
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE"){
                    $borrarCurso = new ControladorCursos;
                    $borrarCurso->delete(array_filter($arrayRutas)[4]);
                }
            // Aqui se reciben las peticiones para los clientes
            // Requests from the clientes are received here
            }else if(array_filter($arrayRutas)[3] == "registro"){
                // Petición PUT
                // PUT request
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT"){
                    // Capturar los datos
                    // Capture the data
                    $datos = array();

                    parse_str(file_get_contents('php://input'), $datos);

                    $editarCliente = new ControladorClientes;
                    $editarCliente->update(array_filter($arrayRutas)[4], $datos);
                }

                // Petición DELETE
                // DELETE request
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE"){
                    $borrarCliente = new ControladorClientes;
                    $borrarCliente->delete(array_filter($arrayRutas)[4]);
                }
            }
        }  
    }
?>