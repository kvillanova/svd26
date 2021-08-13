<?php
const
TEND_NO=6;
include "../inc/db/header.php";
extract($_GET,EXTR_SKIP);
if($atual>0 || !isset($getsetgo)) location();

$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',1,NOW(6),NULL,NULL);");
location();
?>