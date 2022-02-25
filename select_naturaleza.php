<?php require_once('../Connections/bsr.php');

$id = ''; 
if (isset($_GET['id'])) {$id = $_GET['id'];}

$query_servicio = "SELECT cod from (select * from (select * from contratos where id = $id)tabla left join (select id ids, natu_id from servicios)tabla2 on tabla.servicio_id = tabla2.ids)tabla1 LEFT join naturalezas on tabla1.natu_id = naturalezas.id";
$servicio= mysqli_query($bsr, $query_servicio) or die(mysqli_error($bsr));
$row_servicio = mysqli_fetch_assoc($servicio);

?>

<?php echo $row_servicio['cod'] ;?>