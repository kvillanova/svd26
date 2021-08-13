<?php
const NO_TN = true;
include "../../inc/db/header.php";

$id = $_POST['id'];
$status = (int) $_POST['status'];

if($loc) $vd = new VDSQL(true);
else $vd = new VDSQL();

$vd->query("UPDATE ".$vd::SERVICES." SET open={$status} WHERE id='{$id}';");
?>