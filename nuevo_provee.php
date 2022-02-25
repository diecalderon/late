<?php require_once('../Connections/bsr.php');

?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<form id="form_nvo_provee">
<table cellpadding="0" cellspacing="0" border="0" align="center"  bgcolor="#E1E1E1" style="padding:30px 60px 30px 30px" width="35%">
<tr>
	
<td colspan="2" style="font-size:16px" align="center" width="34%">Ingreso de Nuevo Proveedor</td>
<td align="right" width="1%"><input type="button" id="cerrar" class="boton rojo" value="X" onclick="javascript:document.getElementById('estado').style.display ='none', $('#inicio').removeClass('blur')" /></td>
</tr>
	
<tr>
<td  width="130" >Razón Social</td>
<td colspan="2"><input type="text" id="nomb" name="nomb" value="" /></td>
</tr>
<tr>
<td  >Alias</td>
<td colspan="2"><input type="text" id="alias" name="alias" value="" /></td>
</tr>
<tr>
<td  >Nro CUIT</td>
<td colspan="2"><input type="text" id="cuit" name="cuit" value="" /></td>
</tr>
<tr>
<td ></td>
<td align="right"><input type="button" id="ingresar2" name="ingresar2" value="Ingresar" class="boton redondo rojo" onclick="ingresar('proc_nuevo_provee.php?', 'form_nvo_provee'), $('#inicio').removeClass('blur')"/>  </td>
</tr>
</table>
</form>