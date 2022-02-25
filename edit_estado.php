<?php require_once('../Connections/bsr.php');
$caja = ''; $caja_id = '';
$id = "";
if ($_GET['id']) {
$id = $_GET['id'];}

$query_sol = "select * from solicitudes left join (select id ide, detalle est_actual, boton_1, boton_2 from estados) tabla on solicitudes.estado = tabla.ide where id = $id";
$sol = mysqli_query($bsr, $query_sol) or die(mysqli_error($bsr));
$row_sol = mysqli_fetch_assoc($sol);
$totalRows_sol = mysqli_num_rows($sol);

$cajas = array(
		  'Solicitada' => array('caja' => 'Nro. Solicitud', 'caja_id' => 'nro_sol2', 'ult_fec2' => $row_sol['solicitada']),
		  'Cargada' => array('ult_fec2' => $row_sol['cargada']),
		  'Aprobada CMU' => array('ult_fec2' => $row_sol['aprob_cmu']),
		  'Aprobada PIF/PIC' => array('caja' => 'Gestor', 'caja_id' => 'gestor2', 'ult_fec2' => $row_sol['aprob_pif']),
		  'En Asignación' => array('ult_fec2' => $row_sol['en_asig']),
		  'En Negociación' => array('caja' => 'Nota de Pedido', 'caja_id' => 'nota2', 'ult_fec2' => $row_sol['en_negoc']),
		  'Emitida' => array('ult_fec2' => $row_sol['emitida']),
		  'Informada' => array('ult_fec2' => $row_sol['informada'])
		  );
$caja = $cajas[$row_sol['est_actual']]['caja'];
$caja_id = $cajas[$row_sol['est_actual']]['caja_id'];
$dif = round((strtotime(date('Y-m-d H:i:s')) - strtotime($cajas[$row_sol['est_actual']]['ult_fec2']))/86400,0);
$ult_fec2 = date('d/m/Y',strtotime($cajas[$row_sol['est_actual']]['ult_fec2']));
$dif_tot = round((strtotime(date('Y-m-d H:i:s')) - strtotime($row_sol['solicitada']))/86400,0);

?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<form id="form_est">
<table cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#E1E1E1" style="padding:30px;" class="redondo">
	<tr>
		<td colspan="3">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="font-size:16px" align="center" width="97%" valign="middle"><?php echo $row_sol['titulo'];?>
						
					</td>
					<td width="3%" align="right" valign="top">
						<input type="button" id="cerrar" value="X" class="boton rojo" onclick="javascript:document.getElementById('estado').style.display ='none', $('#inicio').removeClass('blur')" />
					</td>
				</tr>
			</table>
						
		</td></tr>
<tr>
<td  width="130" >Estado</td>
<td ><input type="text" id="est_det" name="est_det" value="<?php echo $row_sol['est_actual'];?>" readonly="readonly"/></td>
</tr>
<tr>
<td  >Fecha Estado</td>
<td ><input type="text" id="fec_est" name="fec_est" value="<?php if ($row_sol['est_actual'] != 'No Solicitada') {echo $ult_fec2;}?>"  readonly="readonly"/></td>
<td><input type="text" id="dias" value="<?php if ($row_sol['est_actual'] != 'No Solicitada' && $estado2 != 'Informada') {echo $dif;}?>"  readonly="readonly"/></td>
</tr>
<tr>
<td >Fecha Solicitud</td>
<td ><input type="text" id="fec_sol" name="fec_sol" value="<?php if ($row_sol['est_actual'] != 'No Solicitada') {echo date('d/m/Y',strtotime($row_sol['solicitada']));}?>"  readonly="readonly"/></td>

<td><input type="text" id="dias" value="<?php if ($row_sol['est_actual'] != 'No Solicitada') {echo $dif_tot;}?>"  readonly="readonly"/></td>
</tr>
<?php if (isset($caja) && $caja != 'Gestor' && $caja != '') { ?>
<tr>
<td ><?php echo $caja;?></td>
<td ><input type="text" id="<?php echo $caja_id;?>" name="<?php echo $caja_id;?>" value="<?php if ($caja_id == 'nota2') {echo $row_sol['nota_pedido'];} else if ($caja_id == 'nro_sol2') {echo $row_sol['nro_solicitud'];}?>" /></td>
</tr>
<?php if ($caja == 'Nota de Pedido') { ?>
<tr>
<td >Monto Pedido $</td>
<td ><input type="text" id="monto_pedido" name="monto_pedido" value="<?php echo $row_sol['monto_emit'];?>" /></td>
</tr>
<?php }}  ?>
<tr>
<td><input type="hidden" id="id2" name="id2" value="<?php echo $row_sol['id'];?>" /><input type="hidden" id="est" name="est" value="<?php echo $row_sol['estado'];?>"/>
	<?php if ($row_sol['est_actual'] != 'No Solicitada') { ?>
	<input type="button" id="volver" value="<?php echo $row_sol['boton_2'];?>" class="boton redondo rojo" onclick="ingresar('proc_volver_est.php?', 'form_est'), $('#inicio').removeClass('blur')"/></td>
<?php }
	if ($row_sol['est_actual'] != 'Informada') { ?>
<td  colspan="2" align="right"><input type="button" id="ingresar2" value="<?php echo $row_sol['boton_1'];?>" class="boton redondo rojo" onclick="ingresar('proc_edit_est.php?', 'form_est'), $('#inicio').removeClass('blur')"/></td>
	<?php }?>
</tr>
</table>
</form>