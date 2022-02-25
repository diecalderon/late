<?php require_once('../Connections/bsr.php');
$novigente = "";
$id = "";
if ($_GET['id']) {
$id = $_GET['id'];}
$ano_actual = date('Y');

$query_sol = "select * from (select * from solicitudes left join (select id as idp, provee from proveedores) as tabla on solicitudes.proveedor_id = tabla.idp) as tabla6 left join (select id idt, cod, detalle from tipologias) as tabla7 on tabla6.tipo = tabla7.idt where id=".$id;
$sol = mysqli_query($bsr, $query_sol) or die(mysqli_error($bsr));
$row_sol = mysqli_fetch_assoc($sol);
$totalRows_sol = mysqli_num_rows($sol);

$vector = array("comentarios", "cars", "plan_compras", "ccc");

for ($i = 0; $i < count($vector); $i++) {
if ($row_sol[$vector[$i]] == 'null') {
	$$vector[$i] = '';
} else {
	$$vector[$i] = $row_sol[$vector[$i]];
}
}

$query_gest_adm_sol = "select * from (select id ids, gestor_adm_id from solicitudes) tabla left join gestor_adm on tabla.gestor_adm_id = gestor_adm.id where ids= $id";
$gest_adm_sol = mysqli_query($bsr, $query_gest_adm_sol) or die(mysqli_error($bsr));
$row_gest_adm_sol = mysqli_fetch_assoc($gest_adm_sol);

$query_tipo = "select * from tipologias";
$tipo = mysqli_query($bsr, $query_tipo) or die(mysqli_error($bsr));
$row_tipo = mysqli_fetch_assoc($tipo);

$query_gest_adm = "select * from gestor_adm where vigencia = 1";
$gest_adm = mysqli_query($bsr, $query_gest_adm) or die(mysqli_error($bsr));
$row_gest_adm = mysqli_fetch_assoc($gest_adm);

if (is_null($row_sol['proyecto'])) {
	$query_prog = "";
} else {
$query_prog = "select * from programas where id = ".$row_sol['proyecto'];
$prog= mysqli_query($bsr, $query_prog) or die(mysqli_error($bsr));
$row_prog = mysqli_fetch_assoc($prog);

if ($row_prog['vigencia'] == 0) {$novigente = "union (select * from programas where id = ".$row_prog['id'].")";
								} else {
	$novigente = "";
}}

$query_programa = "select * from programas where estado = 1 and ano = ".date('Y')." $novigente order by id_programa asc";
$programa= mysqli_query($bsr, $query_programa) or die(mysqli_error($bsr));
$row_programa = mysqli_fetch_assoc($programa);

$query_provee = "select * from proveedores order by provee asc";
$provee= mysqli_query($bsr, $query_provee) or die(mysqli_error($bsr));
$row_provee = mysqli_fetch_assoc($provee);

$query_contrato = "select * from contratos where provee_id = ".$row_sol['idp']." order by detalle asc";
$contrato = mysqli_query($bsr, $query_contrato) or die(mysqli_error($bsr));
$row_contrato = mysqli_fetch_assoc($contrato);

$query_origen = "select origen from solicitudes where ano_pea = $ano_actual group by origen order by origen asc";
$origen = mysqli_query($bsr, $query_origen) or die(mysqli_error($bsr));
$row_origen = mysqli_fetch_assoc($origen);

?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script>
$('document').ready(function() {
	
	$('#provee4').on('change', function() {
		var seleccion = $('#provee4 option:selected');
		var valor = seleccion.val();
		var ano = $('#ano_pea').val();
		$.ajaxSetup({ cache: false });
		$.ajax({
	            type: "GET",
	            url: 'select_contrato.php?id=' + valor + '&ano=' + ano,
	       		success: function(a) {
                                              $('#contrato').html(a);
				}
			});
	})
})
</script>
<form id="form_sol">
<table cellpadding="0" cellspacing="0" border="0" align="center"  bgcolor="#E1E1E1" style="padding:30px 60px 30px 30px">
	<tr>
		<td colspan="4">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="97%" align="center" style="font-size: 16px; padding-bottom: 10px" valign="middle"><?php echo $row_sol['titulo']; ?></td>
				<td width="3%" align="right" valign="top" style="margin-right: 10px">
					<input type="button" id="cerrar" value=" X " class="boton redondo rojo" onclick="javascript:document.getElementById('edicion').style.display ='none', $('#inicio').removeClass('blur')" />
				</td>
			</tr>
		</table>
		</td></tr>
<tr>
<td>Proveedor</td>
<td ><select id="provee4" name="provee4" >
<?php do { ?>
<option value="<?php echo $row_provee['id'];?>" <?php if ($row_sol['proveedor_id'] == $row_provee['id']) {echo "selected";}?>><?php echo $row_provee['provee'];?></option>

<?php } while ($row_provee= mysqli_fetch_assoc($provee));?>
</select>	
</td>
<?php if ($row_sol['origen'] == 'Inversión') { ?>
<td>Proyecto Asoc.</td>
<td ><select id="id_proy" name="id_proy" >
	<option value="">Sin Programa Asociado</option>
<?php do { ?>
<option value="<?php echo $row_programa['id'];?>" <?php if ($row_sol['proyecto'] == $row_programa['id']) {echo "selected";}?>><?php echo $row_programa['id_programa']." - ".$row_programa['titulo'];?></option>

<?php } while ($row_programa = mysqli_fetch_assoc($programa));?>
</select></td>
<?php } else {?>
<td >PEA            $</td>
<td ><input type="text" id="asig" name="asig" value="<?php echo number_format($row_sol['pea'],0,',','.');?>"  /></td>
<?php } ?>
</tr>
<tr>
<td >Contrato</td>
<td ><select id="contrato" name="contrato" >
<option value="" ></option>
<?php do { ?>
<option value="<?php echo $row_contrato['id'];?>" <?php if ($row_sol['contrato_id'] == $row_contrato['id']) {echo "selected";}?>><?php echo $row_contrato['detalle'];?></option>

<?php } while ($row_contrato = mysqli_fetch_assoc($contrato));?>
</select>
</td>
<td >Tipología</td>
<td ><select id="tipo" name="tipo" >
<?php do { ?>
<option value="<?php echo $row_tipo['id'];?>" <?php if ($row_sol['idt'] == $row_tipo['id']) {echo "selected";}?>><?php echo $row_tipo['cod']." - ".$row_tipo['detalle'];?></option>

<?php } while ($row_tipo = mysqli_fetch_assoc($tipo));?>
</select>
</td>
</tr>
<tr>
<td>Linea de Gasto</td>
<td ><select id="origen" name="origen" >
<?php do { ?>
<option value="<?php echo $row_origen['origen'];?>" <?php if ($row_sol['origen'] == $row_origen['origen']) {echo "selected";}?>><?php echo $row_origen['origen'];?></option>

<?php } while ($row_origen = mysqli_fetch_assoc($origen));?>
</select>
</td>
<td >Nro. Solicitud</td>
<td><input type="text" id="nro" name="nro" value="<?php echo $row_sol['nro_solicitud'];?>" /></td>
</tr>
<tr>
<td >Naturaleza</td>
<td ><input type="text" id="natu" name="natu" value="<?php echo $row_sol['naturaleza'];?>" /></td>
<td >Monto Solicitud   $</td>
<td ><input type="text" id="mon_sol" name="mon_sol" value="<?php echo number_format($row_sol['monto_sol'],0,',','.');?>" /></td>
</tr>
<tr>
<td>CEGE</td>
<td ><input type="text" id="cege" name="cege" value="<?php echo $row_sol['cege'];?>" /></td>
<td>Nota de Pedido</td>
<td ><input type="text" id="nota" name="nota" value="<?php echo $row_sol['nota_pedido'];?>"/></td>
</tr>
<tr>
<td>Año PEA         </td>
<td ><input type="text" id="ano_pea" name="ano_pea" value="<?php echo $row_sol['ano_pea'];?>" /></td>
<td >Monto Pedido  $</td>
<td ><input type="text" id="mon_emi" name="mon_emi" value="<?php echo number_format($row_sol['monto_emit'],0,',','.');?>" /></td>
</tr>
<tr>
<td>Gestor ADM</td>
<td ><select id="gestor_adm" name="gestor_adm" >
<?php do { ?>
<option value="<?php echo $row_gest_adm['id'];?>" <?php if ($row_gest_adm_sol['id'] == $row_gest_adm['id']) {echo "selected";}?>><?php echo $row_gest_adm['apellido'].', '.$row_gest_adm['nombre'];?></option>

<?php } while ($row_gest_adm = mysqli_fetch_assoc($gest_adm));?>
</select></td>
<td >Comentarios         </td>
<td rowspan="2"><textarea id="coment" name="coment" rows="<?php echo round((strlen($comentarios) / 38) + 0.49,0) ;?>"><?php echo $comentarios;?></textarea></td>
</tr>


<tr>
<td></td>
<td align="right">
	<input type="hidden" id="id2" name="id2" value="<?php echo $row_sol['id'];?>" />
	<input type="button" id="eliminar" class="boton redondo rojo" value="Eliminar" onclick="borrar('<?php echo $row_sol['id'];?>', 'solicitudes', 'cons_solicitudes', '#bloque2'), document.getElementById('edicion').style.display ='none', $('#inicio').removeClass('blur')" />

	<input type="button" id="ingresar2" name="ingresar2" value="Ingresar" class="boton redondo rojo" onclick="ingresar('proc_edit_sol.php?', 'form_sol'), $('#inicio').removeClass('blur')"/>
</td>
	<td></td>
</tr>
</table>
</form>