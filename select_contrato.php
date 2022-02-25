<?php require_once('../Connections/bsr.php');

$id = ''; $ano = "";
if (isset($_GET['id'])) {$id = $_GET['id'];}
if (isset($_GET['ano'])) {$ano = $_GET['ano'];}

$query_servicio = "select * from contratos where provee_id = $id and ano = $ano order by id asc";
$servicio= mysqli_query($bsr, $query_servicio) or die(mysqli_error($bsr));
$row_servicio = mysqli_fetch_assoc($servicio);

?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<option value=""></option>
<?php do { ?>
	<option value="<?php echo $row_servicio['id'];?>"><?php echo $row_servicio['detalle'];?></option>
<?php } while ($row_servicio = mysqli_fetch_assoc($servicio)) ;?>