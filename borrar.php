<?php require_once('../Connections/bsr.php'); 

function validaValor($cadena)
{
	// Funcion utilizada para validar el dato a ingresar recibido por GET	
	if(preg_match('/^[a-zA-Z0-9._áéíóúñ¡!¿? -]{1,1000}$/i', $cadena)) return TRUE;
	else return FALSE;
}

$id=trim($_GET['id']); $tabla=trim($_GET['tabla']);$cons=trim($_GET['cons']); 

if($id)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "update ".$tabla." set estado = 99 where id=".$id) or die(mysqli_error($bsr));
	
}
?>


<?php include($cons.".php");?>