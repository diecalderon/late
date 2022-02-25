<?php require_once('../Connections/bsr.php');

$id = "";
if ($_GET['id']) {
$id = $_GET['id'];}

$query_sol = "select * from proveedores where id=".$id;
$sol = mysqli_query($bsr, $query_sol) or die(mysqli_error($bsr));
$row_sol = mysqli_fetch_assoc($sol);
$totalRows_sol = mysqli_num_rows($sol);

?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<form id="form_provee">
<table cellpadding="0" cellspacing="0" border="0" align="center"  bgcolor="#E1E1E1" style="padding:30px" >
<tr>	
	<td colspan="2" align="right" valign="top">
		<input type="button" id="cerrar" value="X" class="boton rojo" onclick="javascript:document.getElementById('edicion').style.display ='none', $('#inicio').removeClass('blur')" />
	</td>
</tr>
<tr>
<td  >Proveedor</td>
<td><input type="text" id="provee" name="provee" value="<?php echo $row_sol['provee'];?>" /></td>
</tr>
<tr>
<td >Alias</td>
<td ><input type="text" id="alias" name="alias" value="<?php echo $row_sol['nombre'];?>" /></td>
</tr>
<tr>
<td>CUIT</td>
<td><input type="text" id="cuit" name="cuit" value="<?php echo $row_sol['cuit'];?>" /></td>
</tr>

<tr>

<td colspan="2"><input type="hidden" id="id2" name="id2" value="<?php echo $row_sol['id'];?>" /><input type="button" id="ingresar2" name="ingresar2" value="Ingresar" class="boton redondo rojo" onclick="ingresar('proc_edit_provee.php?', 'form_provee'), $('#inicio').removeClass('blur')"/>  </td>
</tr>
</table>
</form>