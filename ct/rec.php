<?php
const TN_NAME = "\$ct_e";
const FOOTER_NAME = "unlimited";
const NO_CHALLENGE = true;
const NO_FINISH = true;
include "../inc/db/header.php";

$tribo = $vd->getTribe($tn,$cook);
if($tribo!=='MOD') die;

$v = $_POST['v'];
$num = (float) $_POST['num'];
$tribo = $_POST['tribo'];
if($v==="votos") include "403/votos.php";
elseif($v==="contagem") include "403/contagem.php";
elseif($v==="encerrar") {
	$closed = (bool) $_POST['closed'];
	$closed = !$closed;
	$closed = (int) $closed;
	$vd->query("REPLACE INTO ".$vd::CT."_c VALUES ({$num},'{$tribo}',$closed);");
	echo "<script>location.reload();</script>";
}
?>