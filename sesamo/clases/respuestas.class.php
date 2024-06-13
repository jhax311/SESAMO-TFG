<?php
//clase para gestionar las respuestas de php
//daran dos respuestas, el codigo de estado y la respuesta
class respuestas{

    //creamos un atributo respuesta

    public $response = [
        'status'=>"ok",
        'result'=> array()
    ];
    
    //solicitudes por metodos no permitidos
    public function error_405(){
        //cambiamos el ok por error, decimos que el metodo no esta permitido
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"405",
            "error_msg"=>"Metodo no permitido"
        );
        //devolvemos la respuesta
     //   return $this->response;
        //podemos devolverla en json
        return json_encode($this->response);

    }
    //para avisar de que la peticion es correcta
    //usaremos un parametro opcional
    public function error_200($string="Datos incorrectos"){
        //cambiamos el ok por error, decimos que el metodo no esta permitido
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"200",
            "error_msg"=>"$string"
        );
        //devolvemos la respuesta
     //   return $this->response;
        //podemos devolverla en json
        return $this->response;

    }
    //error badrequest, no se entiod la solicitud
    public function error_400($error="Datos enviados incorrectos u incompletos"){
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"400",
            "error_msg"=>"$error"
        );
        //devolvemos la respuesta
        return $this->response;
        //podemos devolverla en json
        //return json_encode($this->response);

    }
    //error interno
    public function error_500($error="Error interno en el servidor"){
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"500",
            "error_msg"=>"$error"
        );
        //devolvemos la respuesta
        return $this->response;
        //podemos devolverla en json
        //return json_encode($this->response);

    }
    //error de autorizacion por falta de token
        public function error_401($error="No autorizado"){
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"401",
            "error_msg"=>"$error"
        );
        //devolvemos la respuesta
        return $this->response;
        //podemos devolverla en json
        //return json_encode($this->response);

    }
    //error no se encuntran datso
    public function success_204($error="No se encontraron datos para la solicitud"){
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"204",
            "error_msg"=>"$error"
        );
        //devolvemos la respuesta
        return $this->response;
        //podemos devolverla en json
        //return json_encode($this->response);

    }
    public function error_404($error="No se encuentra la busqueda"){
        $this->response["status"]="error";
        $this->response["result"]=array(
            //mandamos mensaje de error con su mensaje
            "error_id"=>"404",
            "error_msg"=>"$error"
        );
        //devolvemos la respuesta
        return $this->response;
        //podemos devolverla en json
        //return json_encode($this->response);

    }

}

?>