<?php 

$usuario = "root";
$servidor = "localhost";
$contra = "";
$basedatos = "final_sistema";

$conn = @mysqli_connect($servidor, $usuario, $contra, $basedatos);

if ($conn -> connect_error) {
	die("Coneccion Fallida: ". $conn->connect_error);
}




?>