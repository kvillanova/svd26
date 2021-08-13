<?php
const NO_TN=true;
include "../../inc/db/header.php";

$id = $_POST['id'];
$status = (int) $_POST['status'];

$vd->setChallengeStatus($id,$status);

?>