<?php require_once('../Connections/bsr.php'); 

function validaValor($cadena)
{ $dato = str_replace("$?4", "&", $cadena);
return $dato;

}

$titulo=validaValor($_GET['nomb']); $alias=validaValor($_GET['alias']); $cuit = trim($_GET['cuit']); 

$campo = "provee"; $valor = "'".$titulo."'";
if ($alias) { $campo .= ", nombre"; $valor .= ", '".$alias."'";}
if ($cuit) { $campo .= ", cuit"; $valor .= ", '".$cuit."'";}

if($titulo)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "INSERT INTO proveedores (".$campo.") VALUES (".$valor.") ") or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");
?>



