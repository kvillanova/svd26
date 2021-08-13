<?php
const
ATUAL_NO=1,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";

if($atual!==1) die;

extract($_POST,EXTR_SKIP);

$click = intval($click);

$count = file_exists("403/peeps/{$tkey}.txt") ? file_get_contents("403/peeps/{$tkey}.txt") : NULL;
$count = strlen($count);

if($click) { 
	file_put_contents("403/peeps/{$tkey}.txt","1",FILE_APPEND); 
	$count++;
}

if($loc) $max=50;
else $max=3165;

if($count<$max) {
	echo "<script>$('h4').html('{$count}');</script>";
	die;
}

$vd->query("INSERT INTO {$tn}_x VALUES('{$tkey}',2,NOW(6),NULL,$count);");
blankrerro();
?>