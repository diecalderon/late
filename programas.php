<?php require_once('../Connections/bsr.php');

$query_sol = "select * from (select id ids, ano_pea, naturaleza, proyecto from solicitudes) tabla join programas on tabla.proyecto = programas.id_programa where ano_pea = 2019 and naturaleza = 139 and proyecto <> '' and proyecto is not null";
$sol= mysqli_query($bsr, $query_sol) or die(mysqli_error($bsr));
$row_sol = mysqli_fetch_assoc($sol);

do {

mysqli_query($bsr, "UPDATE solicitudes SET proyecto = ".$row_sol['id']." WHERE id = ".$row_sol['ids']) or die(mysqli_error($bsr));
	
} while ($row_sol = mysqli_fetch_assoc($sol));

?>