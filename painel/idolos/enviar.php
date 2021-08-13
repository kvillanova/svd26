<?php
include "../../db/header.php";

$key = $_POST['participante'];

$qvd = $vd->query("SELECT tribo FROM 19_keys WHERE tkey='{$key}';");
$qvd = $qvd->fetch_assoc();
$tribo = $qvd['tribo'];

switch($tribo):
case 'ANG': $tribo = 'angra'; break;
case 'IAR': $tribo = 'iara'; break;
case 'JAC': $tribo = 'jaci'; break;
endswitch;

$qvd = $vd->query("UPDATE 19_exilios SET tkey='{$key}' WHERE nome='{$tribo}';");

echo $vd->error;

?>