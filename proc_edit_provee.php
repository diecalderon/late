<?php require_once('../Connections/bsr.php'); 

function validaValor($cadena)
{
	// Funcion utilizada para validar el dato a ingresar recibido por GET	
	if(preg_match('/^[a-zA-Z0-9._áéíóúñ¡!¿? -]{1,1000}$/i', $cadena)) return TRUE;
	else return FALSE;
}

$nombre = trim($_GET['alias']);
$cuit = trim($_GET['cuit']); 
$id = trim($_GET['id2']);

$campos = "nombre = '$nombre'";


if ($cuit !== "") {$campos .= ", cuit = '$cuit'";};

if($id)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "UPDATE proveedores SET ".$campos." WHERE id = ".$id) or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");
?>



