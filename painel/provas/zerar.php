<?php
const NO_TN = true;
include "../../inc/db/header.php";

$mod = $_POST['mod'];
$prova = $_POST['prova'];

$vd = new VDSQL();
if($_SERVER['HTTP_HOST']==='localhost') $vd->local();

$vd->zerarModerador($prova, $mod);
?>