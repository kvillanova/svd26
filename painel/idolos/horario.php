<?php
include "../../db/header.php";

$now = date("c");
$inicio = $_POST['horainicio'];
$fim = $_POST['horafim'];
$nome = $_POST['exilio'];

if($nome!=='all')
$vd->query("UPDATE 19_exilios SET tstart='{$inicio}',tend='{$fim}',tset='{$now}' WHERE nome='{$nome}';");

else
	$vd->query("UPDATE 19_exilios SET tstart='{$inicio}',tend='{$fim}',tset='{$now}';");

?>