<?php require_once('../Connections/bsr.php'); 
function vacio($valor) {
	if ($valor == '') {$valor = 'null';}
	return $valor;
}
$id = trim($_GET['id2']);
$lista = array(
"monto_sol" => vacio(str_replace(',','.',str_replace('.','',$_GET['mon_sol']))), 
"monto_emit" => vacio(str_replace(',','.',str_replace('.','',$_GET['mon_emi']))), 
"pea" => vacio(str_replace(',','.',str_replace('.','',$_GET['asig']))), 
"nota_pedido" => vacio(trim($_GET['nota'])),
"nro_solicitud" => vacio(trim($_GET['nro'])), 
"origen" => '"'.trim($_GET['origen']).'"',
"contrato_id" => vacio(trim($_GET['contrato'])),
"proyecto" => vacio(trim($_GET['id_proy'])),
"ano_pea" => trim($_GET['ano_pea']),
"comentarios" => '"'.vacio(trim($_GET['coment'])).'"',
"naturaleza" => vacio(trim($_GET['natu'])),
"cege" => vacio(trim($_GET['cege'])),
"gestor_adm_id" => vacio(trim($_GET['gestor_adm'])),
"tipo" => vacio(trim($_GET['tipo'])),
"proveedor_id" => vacio($_GET['provee4']),
);
foreach ($lista as $k => $v) {
$campos .= $k.' = '.$v.', ';
}
$campos = substr($campos, 0, -2);

 if($id)
{
	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "UPDATE solicitudes SET ".$campos." WHERE id = ".$id) or die(mysqli_error($bsr));
	
}
include("cons_solicitudes.php");

?>



