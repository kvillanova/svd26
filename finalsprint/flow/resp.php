<?php
const
ATUAL_NO=4,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";

if($atual!==4) die;
$colors = $_POST['c'];

$erros = 0;

$gaba = file_get_contents("403/gaba.txt");
$gaba = preg_replace("%\s%", "", $gaba);
$gaba = str_split($gaba);

foreach($gaba as $i => $gg) {
	if(empty($colors[$i])) $colors[$i] = '.';
	if($colors[$i]!==$gg) $erros++;
}

$colors = implode("",$colors);

if($erros) {
	$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',{$atual},NOW(6),{$erros},'{$colors}');");
	rerro("Não é bem isso. Tente novamente.");
}

++$atual;
$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',{$atual},NOW(6),{$erros},'{$colors}');");
blankrerro();
?>