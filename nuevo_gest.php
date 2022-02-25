<?php require_once('../Connections/bsr.php');

?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<form id="form_gestor">
<table cellpadding="0" cellspacing="0" border="0" align="center"  bgcolor="#E1E1E1" style="padding:30px" class="redondo">
	<tr>
<td colspan="2" align="right"><input type="button" id="cerrar" class="boton redondo rojo" value="X" onclick="javascript:document.getElementById('estado').style.display ='none', $('#inicio').removeClass('blur')" /></td>
</tr>
<tr>
<td colspan="2" style="font-size:16px" align="center">Ingreso de Nuevo Gestor</td>
</tr>
<tr>
<td  height="30"></td>
<tr>

<tr>
<td  width="130" >Nombre</td>
<td ><input type="text" id="nombre" name="nombre" value="" /></td>
</tr>

<tr>
<td ></td>
<td align="right"><input type="button" id="ingresar2" name="ingresar2" value="Ingresar" class="boton redondo rojo" onclick="ingresar('proc_nuevo_gest.php?', 'form_gestor'), $('#inicio').removeClass('blur')"/>  </td>
</tr>
</table>
</form>