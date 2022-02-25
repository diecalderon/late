<?php require_once('../Connections/bsr.php');

$query_sel= "select * from solicitudes left join (select id as idp, provee from proveedores) as tabla on solicitudes.proveedor_id = tabla.idp order by provee, titulo";
$sel= mysqli_query($bsr, $query_sel) or die(mysqli_error($bsr));
$row_sel = mysqli_fetch_assoc($sel);

$query_fecha = "select max(actual) as a from solicitudes";
$fecha= mysqli_query($bsr, $query_fecha) or die(mysqli_error($bsr));
$row_fecha = mysqli_fetch_assoc($fecha);

$query_proveedor = "select * from proveedores order by nombre asc";
$proveedor= mysqli_query($bsr, $query_proveedor) or die(mysqli_error($bsr));
$row_proveedor = mysqli_fetch_assoc($proveedor);

$fec = $row_fecha['a'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Nueva Solicitud</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
<link href="../css/bsr.css" rel="stylesheet" type="text/css" />
<link href="../css/menu.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/menu.js"></script>
<script src="../js/bsr.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<?php include('../menu.php');?>
<div id="actual" style="height:30px; position:absolute; top:50px; left:770px">
Fecha de Última actualización: <?php echo $fec;?>
</div>
<div id="bloque">
</br>
<form id="form1">
<table width="100%" border="0" align="left" cellpadding="0" bgcolor="#E1E1E1" cellspacing="0">
	<tr><td style="padding:10px 10px 0px 10px; "><input type="button" class="boton redondo rojo" style="zoom:1.3" value="Nueva Solicitud" onclick=""/></td></tr>
        <tr><td style="padding:5px 10px 0px 10px;" height="20"></td></tr>
        <tr><td style="padding:5px 10px 0px 10px; "><input type="button" class="boton redondo rojo" value="Nuevo Gestor" onchange="solicitud2('sol')" style="zoom:1.3"/></td></tr>
    	<tr><td style="padding:5px 10px 0px 10px;" height="20"></td></tr>
        <tr><td style="padding:5px 10px 10px 10px; "><input type="button" class="boton redondo rojo" value="Nuevo Proveedor" onchange="solicitud2('sol')" style="zoom:1.3"/>
        </td>
        </tr>
        <tr><td style="padding:5px 10px 0px 10px;" height="20"></td></tr>
    	</table>
</form>
</div>
<div id="bloque2">
<table border="0" cellspacing="0" align="center" width="100%">
<tr>
<td class="encabezado" width="300" height="30">Título</td>
<td class="encabezado" width="320">Proveedor</td>
<td class="encabezado" width="100">Monto Solicitud</td>
<td class="encabezado" width="180">Estado</td>
<td class="encabezado" width="130">Actualización</td>
<td class="encabezado" width="70">Cant. días</td>
</tr>
<?php do { 
$estado = 'No Solicitada'; $ult_fec = '';
if ($row_sel['solicitada']>0) {$estado = 'Solicitada'; $ult_fec = $row_sel['solicitada'];}
if ($row_sel['cargada']>0) {$estado = 'Cargada'; $ult_fec = $row_sel['cargada'];}
if ($row_sel['aprob_cmu']>0) {$estado = 'Aprobada CMU'; $ult_fec = $row_sel['aprob_cmu'];}
if ($row_sel['aprob_pif']>0) {$estado = 'Aprobada PIF/PIC'; $ult_fec = $row_sel['aprob_pif'];}
if ($row_sel['en_asig']>0) {$estado = 'En Asignación'; $ult_fec = $row_sel['en_asig'];}
if ($row_sel['en_negoc']>0) {$estado = 'En Negociación'; $ult_fec = $row_sel['en_negoc'];}
if ($row_sel['emitida']>0) {$estado = 'Emitida'; $ult_fec = $row_sel['emitida'];}
if ($row_sel['informada']>0) {$estado = 'Informada'; $ult_fec = $row_sel['informada'];}
$dif = round((strtotime(date('Y-m-d H:i:s')) - strtotime($ult_fec))/86400,0);
$ult_fec = date('d-m-Y',strtotime($ult_fec));
?>

<tr>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px; padding-left:10px" width="300" height="30"><a href="javascript:editar(<?php echo $row_sel['id'];?>, 'edit_sol.php?')"><?php echo $row_sel['titulo'];?></a></td>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px" width="320"><?php echo $row_sel['provee'];?></td>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px; padding-right:5px" width="100" align="right">$ <?php echo number_format($row_sel['monto_sol'],0,',','.');?></td>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px" width="180" align="center"><a href="javascript:estado(<?php echo $row_sel['id'];?>, 'edit_estado.php?')"><?php echo $estado;?></a></td>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px; padding-right:5px" width="130" align="center"><?php if ($estado != 'No Solicitada') {echo $ult_fec;}?></td>
<td style="background-color:#E1E1E1; border: solid #FFF; border-width: 1px 0px 0px 0px" width="70" align="center"><?php if ($estado != 'No Solicitada' && $estado != 'Informada') {echo $dif;}?></td>
</tr>

<?php } while ($row_sel = mysqli_fetch_assoc($sel));?>
</table>
</div>
<div id="edicion">
</div>
<div id="estado">
</div>
</br>
</br>
</body>
</html>
				