<?php
const TN_NAME = "\$ct_e";
const FOOTER_NAME = "unlimited";
const NO_CHALLENGE = true;
const NO_FINISH = true;
include "../inc/db/header.php";

$nome = $vd->getNome($tn,$cook);
$gen = $vd->getGen($tn,$cook);

$voto = $_POST['voto'] ?? NULL;
$just = isset($_POST['justificativa']) ? addslashes(htmlentities($_POST['justificativa'])) : NULL;

if(mb_strtolower($nome)===$voto) rerro("Não dá pra querer votar em você mesm".$gen.", né?");

if(!$voto) rerro("Escolha alguém antes de votar, né?");
if(count($voto)>1) rerro("Só é permitido votar em uma pessoa!");

if(!$just) rerro("Cadê a justificativa, gatinh".$gen."?");

$voto = addslashes($voto[0]);

$tkey = $vd->getTkey($tn,$cook);
$num = (float) $_POST['num'];
$tribo = addslashes($_POST['tribo']);

$closed = $vd->query("SELECT closed FROM ".$vd::CT."_c WHERE num={$num} AND tribo='{$tribo}';");
if($closed->num_rows===0) $closed = false;
else $closed = $closed->fetch_assoc['closed'];

if($closed) rerro("Votação para esse CT está encerrada.");

if(empty($voto)) $voto = 'NULL';
else $voto = "'{$voto}'";

$vd->query("INSERT INTO ".$vd::CT."_a VALUES ('{$tkey}',$num,'{$tribo}',{$voto},'{$just}',NOW(6));");
$vd->query("REPLACE INTO ".$vd::CT." VALUES ('{$tkey}',$num,'{$tribo}',{$voto},'{$just}',NOW(6));");

$vd->updateTend($tn,$cook);
rerro("Voto enviado com sucesso!","green");
?>