<?php 
require_once('../Connections/bsr.php');
session_start();
$valor = 'where ';
$dir = -1;
$total = 0; $dias = 0; $ban = 0; $ban2 = 0;
$total_p = 0;

if (isset($_GET['ano'])) {$_SESSION['sol_ano'] = $_GET['ano']; }
if (isset($_GET['provee2'])) {$_SESSION['sol_provee'] = $_GET['provee2']; }
if (isset($_GET['estado'])) {$_SESSION['sol_est'] = $_GET['estado']; } 
if (isset($_GET['orig'])) { if ($_GET['orig'] != "") {$_SESSION['sol_origen'] = "'".$_GET['orig']."'"; } else {$_SESSION['sol_origen'] = "";}}
if (isset($_GET['nat'])) {$_SESSION['sol_nat'] = $_GET['nat']; }
if (isset($_GET['nro_sol'])) {$_SESSION['nro_sol'] = $_GET['nro_sol'];}

if (isset($_GET['prog'])) {
	switch ($_GET['prog']) {
		case "SP":
			$_SESSION['sol_prog'] = ""; 
			$_SESSION['sin_prog'] = 1;
			break;
		case "CP":
			$_SESSION['sol_prog'] = ""; 
			$_SESSION['sin_prog'] = 2;
			break;
		default:
			$_SESSION['sol_prog'] = $_GET['prog']; 
			$_SESSION['sin_prog'] = 0;
	} 
}
if (isset($_GET['orden_s']) and $_GET['orden_s'] <> '') {
	
	if ($_GET['orden_s'] == $_SESSION['orden_s']) {
		if ($_SESSION['sol_orden'] == 'desc') {
			$_SESSION['sol_orden'] = 'asc';
		} else {
			$_SESSION['sol_orden'] = 'desc';
		}
	} else {
		$_SESSION['orden_s'] = $_GET['orden_s'];
		$_SESSION['sol_orden'] = 'asc';
	}
	
}

if ($_SESSION['sol_est'] == 99) {
	$borrada = '';
} else {
	$borrada = ' and estado < 99';
}

if ($_SESSION['orden_s'] == 'dif') {
	$borrada = ' and estado < 9 and estado > 1';
}

$lista_sol = array(
	"estado" => $_SESSION['sol_est'],
	"proveedor_id" => $_SESSION['sol_provee'],
	"origen" => $_SESSION['sol_origen'],
	"ano_pea" => $_SESSION['sol_ano'],
	"naturaleza" => $_SESSION['sol_nat'],
	"proyecto" => $_SESSION['sol_prog'],
);

foreach ($lista_sol as $k => $v) {
	if ($v <> "") {
		$valor .= $k.' = '.$v.' and ';
}}

if ($_SESSION['sin_prog'] == 1) {$valor .= "proyecto is null and ";}
if ($_SESSION['sin_prog'] == 2) {$valor .= "proyecto is not null and ";}

if ($_SESSION['nro_sol'] == "") {
	if ($valor != "where ") {
		$valor = substr($valor, 0, -4);
	} else {
		$valor = "";
	}
} else {
	$valor .= '(nro_solicitud like "%'.$_SESSION['nro_sol'].'%" or titulo like "%'.$_SESSION['nro_sol'].'%" or nota_pedido like "%'.$_SESSION['nro_sol'].'%")';
}

$query_sol2= "select *, datediff(now(), ultima_fecha) as dif, datediff(now(), venc) venci from (select * from solicitudes left join (select id as idp, provee from proveedores) as tabla on solicitudes.proveedor_id = tabla.idp $valor $borrada) as tabla4 left join (select idf, max(ult_fecha) as ultima_fecha from (select id idf, solicitada ult_fecha from solicitudes where solicitada = 0 Union select id idf, solicitada ult_fecha from solicitudes where solicitada <> 0 Union select id idf, cargada ult_fecha from solicitudes where cargada <> 0 Union select id idf, aprob_cmu ult_fecha from solicitudes where aprob_cmu <> 0 Union select id idf, aprob_pif ult_fecha from solicitudes where aprob_pif <> 0 Union select id idf, en_asig ult_fecha from solicitudes where en_asig <> 0 Union select id idf, en_negoc ult_fecha from solicitudes where en_negoc <> 0 Union select id idf, emitida ult_fecha from solicitudes where emitida <> 0 Union select id idf, informada ult_fecha from solicitudes where informada <> 0 ) tabla3 GROUP by idf) as tabla2 on tabla4.id = tabla2.idf order by ".$_SESSION['orden_s']." ".$_SESSION['sol_orden'].", nro_solicitud desc";
$sol2= mysqli_query($bsr, $query_sol2) or die(mysqli_error($bsr));
$row_sol2 = mysqli_fetch_assoc($sol2);

$query_lista_estados2 = "select * from estados";
$lista_estados2= mysqli_query($bsr, $query_lista_estados2) or die(mysqli_error($bsr));
$row_lista_estados2 = mysqli_fetch_assoc($lista_estados2);
do {
$estados[$row_lista_estados2['id']] = $row_lista_estados2['detalle'];
} while ($row_lista_estados2 = mysqli_fetch_assoc($lista_estados2));

$query_fecha2 = "select max(actual) as a from solicitudes ".$valor;
$fecha2= mysqli_query($bsr, $query_fecha2) or die(mysqli_error($bsr));
$row_fecha2 = mysqli_fetch_assoc($fecha2);

$fec2 = $row_fecha2['a'];

if (mysqli_num_rows($sol2)>0) {
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<table border="0" cellspacing="0" align="center" width="100%">
	
<?php do { 

$ult_fec = date('d-m-Y',strtotime($row_sol2['ultima_fecha']));
$alerta = 'Verde';
$dif_dias = $row_sol2['venci'];
if ($dif_dias >= (-60) && $estado != 'Informada') {$alerta = 'Amarillo';
if ($dif_dias >= (-30)) {$alerta = 'Rojo';
}}
?>

<tr>
<td style=" padding-right:10px" width="7%" align="center"><?php echo $row_sol2['nro_solicitud'];?></td>
<td style=" padding-left:5px" width="24%" height="30"><a href="javascript:editar(<?php echo $row_sol2['id'];?>, 'edit_sol.php?')"><?php echo $row_sol2['titulo'];?></a></td>
<td width="16%"><a href="javascript:editar(<?php echo $row_sol2['proveedor_id'];?>, 'edit_provee.php?')"><?php echo $row_sol2['provee'];?></a></td>
<td style="padding-right:10px" width="6%"  align="center"><?php echo $row_sol2['naturaleza'];?></td>
<td style="padding-right:5px" width="10%"  align="right">$ <?php echo number_format($row_sol2['monto_sol'],0,',','.');?></td>
<td style="padding-right:5px" width="10%"  align="right">$ <?php echo number_format($row_sol2['monto_emit'],0,',','.');?></td>
	<td style=" padding-left:5px" width="8%" align="center"><?php echo $row_sol2['nota_pedido'];?></td>
<td align="center" width="9%" ><a href="javascript:estado(<?php echo $row_sol2['id'];?>, 'edit_estado.php?')"><?php echo $estados[$row_sol2['estado']];?></a></td>
<td width="4%" align="center"><?php if ($row_sol2['estado'] != 9 && $row_sol2['estado'] != 1) {echo $row_sol2['dif'];}?></td>
<td width="6%"  align="center"><img src="../imagenes/<?php echo $alerta;?>.png" id="alerta"/></td>
</tr>

<?php 
$total = $total + $row_sol2['monto_sol'];
$total_p = $total_p + $row_sol2['monto_emit'];
$ban2 = $ban2 + 1;
if ($row_sol2['estado'] != 1 && $row_sol2['estado'] != 9) {$dias = $dias + $row_sol2['dif']; $ban = $ban + 1;}
} while ($row_sol2 = mysqli_fetch_assoc($sol2));?>
<tr>
<th class="encabezado">Totales</th>
<th class="encabezado"></th>
<th class="encabezado"></th>
<th class="encabezado"></th>
<th class="encabezado">$ <?php echo number_format($total, 0, ',', '.');?></th>
<th class="encabezado">$ <?php echo number_format($total_p, 0, ',', '.');?></th>
<th class="encabezado"><?php echo $ban." / ".$ban2;?></th>
<th class="encabezado"><?php if ($ban > 0) {echo number_format($dias/$ban, 0);}?></th>
<th class="encabezado"></th>
<th class="encabezado"></th>
</tr>
</table>
</br>
</br>

<?php } else { ?>
<div style="width:100%; height:600px; vertical-align:middle; text-align:center">
</br>
</br>
</br>
No se encontraron solicitudes que se ajusten a sus parámetros de búsqueda. 
</div>
<?php } ?>