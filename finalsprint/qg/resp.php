<?php
const
ATUAL_NO=2,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";

if($atual!==+$_POST['at']) die;

$resp = removeAccents($_POST['resp']);
$gaba = file_get_contents("403/lista.txt");
$gaba = preg_split("%\r?\n%",$gaba,-1,PREG_SPLIT_NO_EMPTY);
$gaba = removeAccents(end($gaba));

$rresp = addslashes($resp);

if($resp!==$gaba) {
	$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',{$atual},NOW(6),0,'{$rresp}');");
	rerro("Não é bem isso. Tente novamente.");
}

++$atual;
$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',{$atual},NOW(6),1,'{$rresp}');");
blankrerro();
?>