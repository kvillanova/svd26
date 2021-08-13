<?php
const
ATUAL_NO=3,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";

$p=0;
$v[] = $_REQUEST['v1'];
$v[] = $_REQUEST['v2'];
$v[] = $_REQUEST['v3'];
$v[] = $_REQUEST['v4'];
$v[] = $_REQUEST['v5'];
$v[] = $_REQUEST['v6'];

if(empty($v[0]) || empty($v[1]) || empty($v[2]) || empty($v[3]) || empty($v[4]) || empty($v[5]))
	rerro("Complete todas as cores antes de testar.");


if($v[0]==="framboesa0") ++$p;
if($v[1]==="verdeclaro1") ++$p;
if($v[2]==="verdeclaro0") ++$p;
if($v[3]==="rosa1") ++$p;
if($v[4]==="amarelo0") ++$p;
if($v[5]==="azul1") ++$p;
	
$vz = addslashes(implode("-",$v));
$tkey = $vd->getTkey($tn,$cook);
if($p<6) {
	$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',3,NOW(6),$p,'$vz');");
	
}

if($p===0) rerro("Lamento, você não acertou <strong>nenhuma</strong> cor.");
elseif($p===1) rerro("Bom, foi pelo menos <strong>uma</strong>, né?");
elseif($p===2) rerro("<strong>Duas</strong> cores, já é um caminho.");
elseif($p===3) rerro("<strong>Três</strong> cores, está progredindo.");
elseif($p===4) rerro("Boa! Já vamos nas <strong>quatro</strong> cores!");
elseif($p===5) rerro("<strong>Cinco</strong>! Só falta uma!");
			
$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',4,NOW(6),$p,'$vz');");
blankrerro();
?>
