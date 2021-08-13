<?php
include "../inc/db/header.php";
extract($_GET,EXTR_SKIP);
if(isset($thelasttime) && $atual===0) {
	$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',1,NOW(6),NULL,NULL);");
}
location();
?>