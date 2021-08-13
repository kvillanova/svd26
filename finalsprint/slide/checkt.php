<?php
const 
TN_SUBDIR = true;
include "../../inc/db/header.php";

$t = $_POST['a'];

$tkey = $vd->getTkey($tn,$cook);

if($t!=='b01ef992b9f8c67248ceb990412b17f1') die;

$vd->query("INSERT INTO {$tn}_x VALUES('{$tkey}',6,NOW(6),NULL,NULL);");
blankrerro();
?>