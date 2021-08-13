<?php
include "../../db/header.php";

$key = $_POST['participante'];
$opc = $_POST['opc']; echo $opc;

$qvd = $vd->query("UPDATE 19_exilios SET tkey='{$key}' WHERE nome='{$opc}';");

echo $vd->error;

?>