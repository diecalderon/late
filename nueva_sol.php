<?php require_once('../Connections/bsr.php');

$query_provee = "select * from proveedores order by provee asc";
$provee= mysqli_query($bsr, $query_provee) or die(mysqli_error($bsr));
$row_provee = mysqli_fetch_assoc($provee);

$query_origen = "select origen from solicitudes group by origen order by origen asc ";
$origen= mysqli_query($bsr, $query_origen) or die(mysqli_error($bsr));
$row_origen = mysqli_fetch_assoc($origen);

$query_subgcia = "select * from subgcias";
$subgcia= mysqli_query($bsr, $query_subgcia) or die(mysqli_error($bsr));
$row_subgcia = mysqli_fetch_assoc($subgcia);

$query_gestor = "select * from gestor_adm where vigencia = 1";
$gestor= mysqli_query($bsr, $query_gestor) or die(mysqli_error($bsr));
$row_gestor = mysqli_fetch_assoc($gestor);

$query_tipo = "select * from tipologias";
$tipo= mysqli_query($bsr, $query_tipo) or die(mysqli_error($bsr));
$row_tipo = mysqli_fetch_assoc($tipo);

$query_programa = "select * from programas where estado = 1 and ano = ".date('Y');
$programa= mysqli_query($bsr, $query_programa) or die(mysqli_error($bsr));
$row_programa = mysqli_fetch_assoc($programa);

?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script>
$('document').ready(function() {
	
	$('#provee3').on('change', function() {
		var seleccion = $('#provee3 option:selected');
		var valor = seleccion.val();
		var ano = $('#ano_pea3').val();
		$.ajaxSetup({ cache: false });
		$.ajax({
	            type: "GET",
	            url: 'select_contrato.php?id=' + valor + '&ano=' + ano,
	       		success: function(a) {
                    $('#contrato3').html(a);
				}
			});
		
	})

	$('#contrato3').on('change', function() {
		var seleccion = $('#contrato3 option:selected');
		var valor = seleccion.val();
		$.ajax({
	            type: "GET",
	            url: 'select_naturaleza.php?id=' + valor,
	       		success: function(a) {
                    $('#natu3').val(a);                  
                }
			});

	})

	

})
</script>
<form id="form_nva_sol">
<table cellpadding="0" cellspacing="0" border="0" align="center"  bgcolor="#E1E1E1" style="padding:30px 60px 30px 30px" >
	<tr>
		<td colspan="2" width="100%">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="font-size:16px" align="center" width="97%" valign="middle">Ingreso de Nueva Solicitud</td>
					<td align="right" width="3%" valign="top">
						<input type="button" id="cerrar" class="boton redondo rojo" value="X" onclick="javascript:document.getElementById('estado').style.display ='none', $('#inicio').removeClass('blur')" />
					</td>
				</tr>
			</table>
		</td>
</tr>
<tr>
</tr>
<tr>
<td colspan="2">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td  width="130">Título</td>
<td><input type="text" id="tit3" name="tit3" value="" /></td>
</tr>
</table>
	</td>
</tr>
<tr>
<td>
<table cellpadding="0" cellspacing="0" border="0">

<tr>
<td >Proveedor</td>
<td ><select id="provee3" name="provee3" value="" >
<option value=""></option>
<?php do { ?>
<option value="<?php echo $row_provee['id'];?>"><?php echo $row_provee['provee'];?></option>
<?php } while ($row_provee = mysqli_fetch_assoc($provee));?>
</select>
</td>
</tr>
<tr>
<td >Contrato</td>
<td ><select id="contrato3" name="contrato3" value="" >
<option value=""></option>
</select>
</td>
</tr>
<tr>
<td >Requirente</td>
<td ><select id="subg3" name="subg3" ><option value=""></option>
<?php do { ?>
<option value="<?php echo $row_subgcia['id'];?>"><?php echo $row_subgcia['alias'];?></option>

<?php } while ($row_subgcia = mysqli_fetch_assoc($subgcia));?>
</select>
</td>
</tr>
<tr>
<td >Línea de Gastos</td>
<td><select id="origen3" name="origen3" onchange="origen(this.id)"><option value=""></option>
<?php do { ?>
<option value="<?php echo $row_origen['origen'];?>"><?php echo $row_origen['origen'];?></option>

<?php } while ($row_origen = mysqli_fetch_assoc($origen));?>
</select>
</td>
</tr>
<tr>
<td >Naturaleza           </td>
<td ><input type="text" id="natu3" name="natu3" value=""/></td>
</tr>
<tr>
<td >CEGE           </td>
<td><input type="text" id="cege3" name="cege3" value=""/></td>
</tr>
</table>
</td>
<td>
<table cellpadding="0" cellspacing="0" border="0">

<tr>
<td >Tipolog&iacute;a           </td>
<td >
<select id="tipo3" name="tipo3" value="" >
<option value=""></option>
<?php do { ?>
<option value="<?php echo $row_tipo['id'];?>"><?php echo $row_tipo['cod']." - ".$row_tipo['detalle'];?></option>
<?php } while ($row_tipo = mysqli_fetch_assoc($tipo));?>
</select></td>
</tr>

<td >Gestor ADM         </td>
<td><select id="gestor_adm3" name="gestor_adm3" ><option value=""></option>
<?php do { ?>
<option value="<?php echo $row_gestor['id'];?>"><?php echo $row_gestor['apellido'].', '.$row_gestor['nombre'];?></option>

<?php } while ($row_gestor = mysqli_fetch_assoc($gestor));?>
</select></td>
</tr>
<tr>
<td >Año PEA           </td>
<td ><input type="text" id="ano_pea3" name="ano_pea3" value="<?php echo date('Y');?>"/></td>
</tr>
<tr>
<td >Proyecto         </td>
<td><select id="proy3" name="proy3" value="" >
<option value=""></option>
<?php do { ?>
<option value="<?php echo $row_programa['id'];?>"><?php echo $row_programa['id_programa']." - ".$row_programa['titulo'];?></option>
<?php } while ($row_programa = mysqli_fetch_assoc($programa));?>
</select></td>
</tr>
<tr>
<td >Monto Solicitud $</td>
<td ><input type="text" id="mon_sol3" name="mon_sol3" value="" /></td>
</tr>
	<tr>
<td align="right" colspan="2"><input type="button" id="ingresar3" name="ingresar3" value="Ingresar" class="boton redondo rojo" onclick="ingresar('proc_nueva_sol.php?', 'form_nva_sol'), $('#inicio').removeClass('blur')"/></td>
</tr>
</table>
</td>
</tr>
</table>
</form>