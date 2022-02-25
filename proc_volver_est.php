<?php require_once('../Connections/bsr.php'); 

$id=trim($_GET['id2']);

$est = 0;

if ($_GET['est'] == 99) {
	$query_vest = "select * from solicitudes where id = $id ";
	$vest = mysqli_query($bsr, $query_vest) or die(mysqli_error($bsr));
	$row_vest = mysqli_fetch_assoc($vest);
	
	$estado_volver = array('2019-01-01 00:00:00', $row_vest['solicitada'], $row_vest['cargada'], $row_vest['aprob_cmu'], $row_vest['aprob_pif'], $row_vest['en_asig'], $row_vest['en_negoc'], $row_vest['emitida'], $row_vest['informada']);
		
	for ($k = 0; $k < count($estado_volver); $k++) {
		
		if (substr($estado_volver[$k],0,1) < substr($estado_volver[$k-1],0,1)) {
			
			$est = $k;
			break;
		}
	}
	
	$campo = "estado = ".$est;
	
} else {

	$est = $_GET['est'] - 1;

	$estado = array('no solicitada', 'solicitada', 'cargada', 'aprob_cmu', 'aprob_pif', 'en_asig', 'en_negoc', 'emitida', 'informada');

	$campo = $estado[$est]." = 0";
	$campo .= ", estado = ".$est;
}


if($id)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "UPDATE solicitudes SET $campo WHERE id = $id") or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");
?>



