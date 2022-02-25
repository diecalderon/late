<?php require_once('../Connections/bsr.php'); 

$estado=trim($_GET['est_det']); $id=trim($_GET['id2']);
if (isset($_GET['nro_sol2']) && $_GET['nro_sol2']>0) {$nro_sol = $_GET['nro_sol2'];}
if (isset($_GET['nota2']) && $_GET['nota2']>0) {$nota = $_GET['nota2'];}
if (isset($_GET['monto_pedido']) && $_GET['monto_pedido']>0) {$monto = str_replace('.','',$_GET['monto_pedido']);}
$est = $_GET['est'] + 1;

switch ($estado) {
	case 'No Solicitada':
	$campo = 'solicitada';
	break;
	case 'Solicitada':
	$campo = 'cargada';
	break;
	case 'Cargada':
	$campo = 'aprob_cmu';
	break;
	case 'Aprobada CMU':
	$campo = 'aprob_pif';
	break;
	case 'Aprobada PIF/PIC':
	$campo = 'en_asig';
	break;
	case 'En Asignación':
	$campo = 'en_negoc';
	break;
	case 'En Negociación':
	$campo = 'emitida';
	break;
	case 'Retenida':
	$campo = 'informada';
	break;
}
$campo .= " = '".date('Y-m-d H:m:s')."'";
$campo .= ", estado = ".$est;
if ($nro_sol) { $campo .= ", nro_solicitud = ".$nro_sol;}
if ($nota) { $campo .= ", nota_pedido = ".$nota;}
if ($monto) { $campo .= ", monto_emit = ".str_replace(',','.',$monto);}

if($id)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "UPDATE solicitudes SET ".$campo." WHERE id = ".$id) or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");
?>



