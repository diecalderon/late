<?php require_once('../Connections/bsr.php'); 
$campos = "";
$valores = "";

function vacio($valor) {
	if ($valor == '') {$valor = 'null';}
	return $valor;
}
$id = trim($_GET['id2']);
$lista = array(
"monto_sol" => vacio(str_replace(',','.',str_replace('.','',$_GET['mon_sol3']))), 
"titulo" => '"'.str_replace("$?4", "&", $_GET['tit3']).'"',
"origen" => '"'.trim($_GET['origen3']).'"',
"contrato_id" => vacio(trim($_GET['contrato3'])),
"proyecto" => vacio(trim($_GET['proy3'])),
"ano_pea" => trim($_GET['ano_pea3']),
"subgcia_id" => vacio(trim($_GET['subg3'])),
"naturaleza" => vacio(trim($_GET['natu3'])),
"cege" => vacio(trim($_GET['cege3'])),
"gestor_adm_id" => vacio(trim($_GET['gestor_adm3'])),
"proveedor_id" => $_GET['provee3'],
"tipo" => $_GET['tipo3'],
);
foreach ($lista as $k => $v) {
$campos .= $k.', ';
$valores .= $v.', ';
}
$campos = substr($campos, 0, -2);
$valores = substr($valores, 0, -2);

	// Si los campos son validos, se procede a actualizar los valores en la DB
	// Actualizo el campo recibido por GET con la informacion que tambien hemos recibido
	mysqli_query($bsr, "INSERT INTO solicitudes (".$campos.") VALUES (".$valores.") ") or die(mysqli_error($bsr));
	
	

include("cons_solicitudes.php");
?>