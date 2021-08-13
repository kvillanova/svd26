<?php
include "../../db/class.php";

$id = $_POST['id'];
$status = (int) $_POST['status'];

$vd = new VDSQL();
if($_SERVER['HTTP_HOST']==='localhost') $vd->local();

$vd->setChallengeStatus($id,$status);

?>