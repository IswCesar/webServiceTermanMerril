<?php
//no bloquee el contenido
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_SERVER['HTTP_ORIGIN']))
{
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials:true');
    header('Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');
    header('Access-Control-Max-Age:86400'); //cache por un dia 
}
//ESTABLECE FORMATO DE ENTRADA PARA APPLICATION/JSON
if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') != 0)
{
    throw new Exception("EL METODO DEBERIA SER POST");
}

//Establece que el formato de entrada será application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}


//RECIBE EL RAW
$content = trim(file_get_contents("php://input"));

//transdorma el raw json a php
$decoded = json_decode($content,true); //guarda la peticion

$message = array(); //guardar las respuestas

require 'Config/Conexion.php';
require 'API/apiTest.php';

$miApi = new Consultas();

//tratar la peticion
switch ($decoded['action']) {
    case "loggin":
            if(!isset($decoded['usuario']) || !isset($decoded['clave']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                if(!empty($data = $miApi->loggin($decoded)))
                {
                    $message = $data;
                }
                else
                {
                    $message = "DATOS INCORRECTOS, ERROR AL INICIAR SESION.";
                }
            }
        break;
    case "cUsuario":
            if(!isset($decoded['email']) || !isset($decoded['password']) || !isset($decoded['nombres']) || !isset($decoded['apellidos'])|| !isset($decoded['cargo']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                
                if($data = $miApi->insertarUsuario($decoded)) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
                {
                    $message["message"] = "USUARIO REGISTRO CON EXITO.";
                }
                else
                {
                    $message["message"] = "ERROR EN LA ALTA DE USUARIO.";
                }
            }
        break;
    case "rUsuario":
            if(is_array($data = $miApi->consultarUsuarios($decoded))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
            {
                $message = $data;
            }
            else
            {
                $message["message"] = "ERROR EN LA ACCION CONSULTAR USUARIOS.";
            }
        break;
        
    case "cSerie1":
            if(is_array($data = $miApi->consultarSerie1($decoded))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
            {
                $message = $data;
            }
            else
            {
                $message["message"] = "ERROR EN LA ACCION CONSULTAR USUARIOS.";
            }
        break;
    case "cPreguntasSerie1":
            if(is_array($data = $miApi->consultarPreguntasS1($decoded))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
            {
                $message = $data;
            }
            else
            {
                $message["message"] = "ERROR EN LA ACCION CONSULTAR USUARIOS.";
            }
        break;
    
    default:
            $message["message"] = "ACCION NO VALIDA";
        break;
}

header('Content-Type:application/json;charset=utf-8');
print json_encode($message, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);