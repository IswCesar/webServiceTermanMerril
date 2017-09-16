<?php
error_reporting(E_ALL ^ E_NOTICE);
//require '../Config/Conexion.php';

class Consultas {
    
    private $conexion;
    private $results;
    private $mysqli;
    
    public function __construct() {
       $this -> conexion = new Conexion();
       $this -> mysqli = $this->conexion->mysqli;
    }
    
    public function loggin($values)
    {
        $query = "SELECT * from usuarios where email = '".$values['usuario']."' and password = '".$values['clave']."'";
        //print $query;
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this -> results;
    }
    
    public function insertarUsuario($values)
    {
        
        switch($values[4])
        {
            case "ADMINISTRADOR": $tipo = 1; 
            case "POSTULANTE": $tipo = 2;
        }
        
        $query = "insert into usuarios (`email`, `password`, `nombres`, `apellidos`, `cargo`, `tipoUsuario`) values('$values[email]','$values[password]','$values[nombres]','$values[apellidos]','$values[cargo]','$tipo')";
        //print $query;
        
        if($res = $this -> mysqli -> query($query))
        {
            return true;
        }
        return false;
    }
    
    public function consultarUsuarios(){
        $query = "SELECT usuarios.nombres,usuarios.email , usuarios.password , usuarios.nombres , usuarios.apellidos,usuarios.cargo, postulante.id_postulante as Clave from postulante inner JOIN usuarios on postulante.email = usuarios.email";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this->results;
    }    
    
    public function consultarSerie1(){
        $query = "select * from series where noSerie = 1";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this->results;
    }  
    
    public function consultarPreguntasS1(){
        $query = "SELECT * FROM `preguntas` inner join contiene on preguntas.id_pregunta = contiene.id_pregunta and contiene.noSerie = 1";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this->results;
    }
    
    
}

/*
$miConsulta = new Consultas();

$values[email] = "dan@gmail.com";
$values[password] = "12345678";
$values[nombres] = "DANIELA MARIEL";
$values[apellidos] = "LOPEZ CHAVEZ";
$values[cargo] = "ADMINISTRADOR";



$miConsulta ->insertarUsuario($values);*/