<?php 

$usuario = "root";
$servidor = "localhost";
$contra = "";
$basedatos = "sistema_ventas";

$conn = @mysqli_connect($servidor, $usuario, $contra, $basedatos);

if ($conn -> connect_error) {
	die("Coneccion Fallida: ". $conn->connect_error);
}




?>