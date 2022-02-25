<?php require_once('../Connections/bsr.php');
session_start();
$_SESSION['sol_est'] = '';
$_SESSION['sol_provee'] = '';
$_SESSION['sol_origen'] = '';
$_SESSION['sol_orden'] = 'desc';
$_SESSION['sol_nat'] = '';
$_SESSION['nro_sol'] = '';
$_SESSION['ano'] = date('Y');;
$_SESSION['req'] = '';
$_SESSION['curso'] = '';
$_SESSION['orden_s'] = 'estado';
$ano_actual = date('Y');
$ban = 0; $ban2 = 0;
$total = 0;
$dias = 0;

$query_sel= "select *, datediff(now(), ultima_fecha) as dif, datediff(now(), venc) venci from (select * from solicitudes left join (select id as idp, provee from proveedores) as tabla on solicitudes.proveedor_id = tabla.idp) as tabla4 left join (select idf, max(ult_fecha) as ultima_fecha from (select id idf, solicitada ult_fecha from solicitudes where solicitada = 0 Union select id idf, solicitada ult_fecha from solicitudes where solicitada <> 0 Union select id idf, cargada ult_fecha from solicitudes where cargada <> 0 Union select id idf, aprob_cmu ult_fecha from solicitudes where aprob_cmu <> 0 Union select id idf, aprob_pif ult_fecha from solicitudes where aprob_pif <> 0 Union select id idf, en_asig ult_fecha from solicitudes where en_asig <> 0 Union select id idf, en_negoc ult_fecha from solicitudes where en_negoc <> 0 Union select id idf, emitida ult_fecha from solicitudes where emitida <> 0 Union select id idf, informada ult_fecha from solicitudes where informada <> 0 ) tabla3 GROUP by idf) as tabla2 on tabla4.id = tabla2.idf where ano_pea = $ano_actual and estado < 99 order by estado desc, dif desc, nro_solicitud desc";
$sel= mysqli_query($bsr, $query_sel) or die(mysqli_error($bsr));
$row_sel = mysqli_fetch_assoc($sel);

$query_lista_estados = "select * from estados";
$lista_estados= mysqli_query($bsr, $query_lista_estados) or die(mysqli_error($bsr));
$row_lista_estados = mysqli_fetch_assoc($lista_estados);
do {
$estados[$row_lista_estados['id']] = $row_lista_estados['detalle'];
} while ($row_lista_estados = mysqli_fetch_assoc($lista_estados));

$query_fecha = "select max(actual) as a from solicitudes";
$fecha= mysqli_query($bsr, $query_fecha) or die(mysqli_error($bsr));
$row_fecha = mysqli_fetch_assoc($fecha);

$query_proveedor = "SELECT * from proveedores RIGHT join (select proveedor_id ids from solicitudes) tabla on proveedores.id = tabla.ids GROUP by ids order by nombre";
$proveedor= mysqli_query($bsr, $query_proveedor) or die(mysqli_error($bsr));
$row_proveedor = mysqli_fetch_assoc($proveedor);

$query_origen = "select origen from solicitudes group by origen order by origen asc";
$origen= mysqli_query($bsr, $query_origen) or die(mysqli_error($bsr));
$row_origen = mysqli_fetch_assoc($origen);

$query_nat = "select naturaleza from solicitudes group by naturaleza order by naturaleza asc";
$nat= mysqli_query($bsr, $query_nat) or die(mysqli_error($bsr));
$row_nat = mysqli_fetch_assoc($nat);

$query_estado = "select estados.id, detalle FROM estados right join solicitudes on estados.id = solicitudes.estado where ano_pea = $ano_actual GROUP by detalle order by estados.id";
$estado = mysqli_query($bsr, $query_estado) or die(mysqli_error($bsr));
$row_estado = mysqli_fetch_assoc($estado);

$fec = date_format(date_create($row_fecha['a']), "d/m/Y h:m");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link href="../imagenes/bsr.ico" rel="shortcut icon" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Solicitudes</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
<link href="../css/bsr.css" rel="stylesheet" type="text/css" />
<link href="../css/menu.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/menu.js"></script>
<script src="../js/bsr.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<div id="inicio">
<?php include('../menu.php');?>
<div id="bloque">
<form id="form1">
	<div class="div" style="width: 11%">
		<p>Solicitud / Pedido</p></br>
	<input type="text" id="sol" onkeyup="solicitud2('sol', 'nro_sol',  'cons_solicitudes.php?')" style="text-align:center"/>
	</div>
	<div class="div" style="width: 7%">
		<p>Año</p></br>
	<select id="ano" onchange="solicitud(this.id, 'ano', 'cons_solicitudes.php?')">
        <option value="">Todos</option>
<?php   for ($i = 2017; $i<= $ano_actual + 1; $i++) { ?>
        <option value=<?php echo '"'.$i.'" '; if ($i == $ano_actual) { echo 'selected = "selected"' ;};?>><?php echo $i;?></option>
        <?php } ?>
        </select>
	</div>
	<div class="div" style="width: 17%">
		<p>Proveedor</p></br>
	<select id="prov" onchange="solicitud(this.id, 'provee2', 'cons_solicitudes.php?')"><option value="">Todos</option>
        <?php do { ?>
        <option value="<?php echo $row_proveedor['id'];?>"><?php echo $row_proveedor['nombre'];?></option>
        <?php } while ($row_proveedor = mysqli_fetch_assoc($proveedor));?>
        </select>
	</div>
	<div class="div" style="width: 10%">
		<p>Estado</p></br>
	<select id="esta" onchange="solicitud(this.id, 'estado', 'cons_solicitudes.php?')"><option value="">Todos</option>
        <?php do { ?>
        <option value="<?php echo $row_estado['id'];?>"><?php echo $row_estado['detalle'];?></option>
        <?php } while ($row_estado = mysqli_fetch_assoc($estado));?>
        </select>
	</div>
	<div class="div" style="width: 7%">
		<p>Origen</p></br>
	<select id="orig" width="80" onchange="solicitud(this.id, 'orig', 'cons_solicitudes.php?')"><option value="">Todos</option>
        <?php do { ?>
        <option value="<?php echo $row_origen['origen'];?>"><?php echo $row_origen['origen'];?></option>
        <?php } while ($row_origen = mysqli_fetch_assoc($origen));?>
        </select>
	</div>
<div class="div" style="width: 7%">
		<p>Naturaleza</p></br>
	<select id="nat" width="80" onchange="solicitud(this.id, 'nat', 'cons_solicitudes.php?')"><option value="">Todas</option>
        <?php do { ?>
        <option value="<?php echo $row_nat['naturaleza'];?>"><?php echo $row_nat['naturaleza'];?></option>
        <?php } while ($row_nat = mysqli_fetch_assoc($nat));?>
        </select>
	</div>
<div class="div" style="width: 10%">
	<p>&nbsp</p></br>
		<input type="button" class="boton rojo" style="zoom:1.2" value="Nueva Solicitud"  onclick="estado('1', 'nueva_sol.php?')"/>
	</div>
<div class="div" style="width: 10%">
	<p>&nbsp</p></br>
	<input type="button" class="boton rojo" value="Nuevo Proveedor" onclick="estado('1', 'nuevo_provee.php?')" style="zoom:1.2"/>
	</div>
</form>

</div>
<div id="bloque2" class="tabla">
<table border="0" cellspacing="0" align="center" width="100%">
<tr style="position: fixed; width: 96%; margin-top: -30px;">
<td class="encabezado" width="8%"><a class="blanco" id="nro_solicitud" onclick="solicitud3(this.id, 'orden_s' , 'cons_solicitudes.php?')">Solicitud </a></td>
<td class="encabezado" width="26%" height="30"><a class="blanco" id="titu" onclick="solicitud3('titulo', 'orden_s' , 'cons_solicitudes.php?')">Título</a></td>
<td class="encabezado" width="20%"><a class="blanco" id="provee" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">Proveedor</a></td>
	<td class="encabezado" width="6%"><a class="blanco" id="naturaleza" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">Naturaleza</a></td>
<td class="encabezado" width="10%"><a class="blanco" id="monto_sol" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">$ Solicitud</a></td>
	<td class="encabezado" width="10%"><a class="blanco" id="monto_emit" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">$ Pedido</a></td>
<td class="encabezado" width="10%"><a class="blanco" id="ocu" onclick="solicitud3('estado', 'orden_s', 'cons_solicitudes.php?')">Estado <img src="../imagenes/desc.png" height="12" alt="down" /></a></td>
<td class="encabezado" width="4%"><a class="blanco" id="dif" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">Días</a></td>
<td class="encabezado" width="6%"><a class="blanco" id="venci" onclick="solicitud3(this.id, 'orden_s', 'cons_solicitudes.php?')">Alerta</a></td>
</tr>
<?php do { 

$ult_fec = date('d-m-Y',strtotime($row_sel['ultima_fecha']));
$alerta = 'Verde';
$dif_dias = $row_sel['venci'];
if ($dif_dias >= (-60) && $row_sel['estado'] != 9) {$alerta = 'Amarillo';
if ($dif_dias >= (-30)) {$alerta = 'Rojo';
}}
if ($row_sel['estado'] == 9 || $row_sel['estado'] == 1) {$no_importa = 'No importa';} else {
?>

<tr>
<td style="padding-right:10px" width="8%" align="center"><?php echo $row_sel['nro_solicitud'];?></td>
<td style="padding-left:5px" width="26%" height="30"><a href="javascript:editar(<?php echo $row_sel['id'];?>, 'edit_sol.php?')"><?php echo $row_sel['titulo'];?></a></td>
<td  width="20%"><a href="javascript:editar(<?php echo $row_sel['idp'];?>, 'edit_provee.php?')"><?php echo $row_sel['provee'];?></a></td>
<td style="padding-right:10px" width="6%" align="center"><?php echo $row_sel['naturaleza'];?></td>
<td style="padding-right:5px" width="10%" align="right">$ <?php echo number_format($row_sel['monto_sol'],0,',','.');?></td>
<td style="padding-right:5px" width="10%" align="right">$ <?php echo number_format($row_sel['monto_emit'],0,',','.');?></td>
<td  align="center" width="10%"><a href="javascript:estado(<?php echo $row_sel['id'];?>, 'edit_estado.php?')"><?php echo $estados[$row_sel['estado']];?></a></td>
<td width="4%"  align="center"><?php if (1 < $row_sel['estado'] && $row_sel['estado'] < 9) {echo $row_sel['dif'];}?></td>
<td width="6%"  align="center"><img src="../imagenes/<?php echo $alerta;?>.png" id="alerta"/></td>
</tr>

<?php 
$total = $total + $row_sel['monto_sol'];
$total_p = $total_p + $row_sel['monto_emit'];
$ban2 = $ban2 + 1;
if (1 < $row_sel['estado'] && $row_sel['estado'] < 9) {$dias = $dias + $row_sel['dif']; $ban = $ban + 1;}}
} while ($row_sel = mysqli_fetch_assoc($sel));?>
<tr>
<td class="encabezado">Totales</td>
<td class="encabezado"></td>
<td class="encabezado"></td>
<td class="encabezado"></td>
<td class="encabezado">$ <?php echo number_format($total, 0, ',', '.');?></td>
<td class="encabezado">$ <?php echo number_format($total_p, 0, ',', '.');?></td>
<td class="encabezado"><?php echo $ban." / ".$ban2;?></td>
<td class="encabezado"><?php if ($ban > 0) {echo number_format($dias/$ban, 0);}?></td>
<td class="encabezado"></td>
</tr>
</table>
</div>
</div>
<div id="edicion">
</div>
<div id="estado">
</div>
</br>
</br>
</body>
</html>
				