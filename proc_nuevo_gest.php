<?php require_once('../Connections/bsr.php'); 

function validaValor($cadena)
{
	// Funcion utilizada para validar el dato a ingresar recibido por GET	
	if(preg_match('/^[a-zA-Z0-9._áéíóúñ¡!¿? -]{1,1000}$/i', $cadena)) return TRUE;
	else return FALSE;
}

$titulo=trim($_GET['nombre']); 

$campo = "gestor"; $valor = "'".$titulo."'";

if($titulo)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "INSERT INTO aquanima (".$campo.") VALUES (".$valor.") ") or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");
?>



