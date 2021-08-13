<?php
require_once "func-req.php";

/*
RECHECK DE ABERTURA
*/
$open = $vd->query("SELECT * FROM {$vd->services} WHERE id='destinos' AND open=1;");
if(!$open->num_rows) blankrerro();

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='destinos_abre';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = false;

if($open) {
$now = new DateTime(NULL,$dtz);
$open = new DateTime($open,$dtz);
if($now<$open) blankrerro();
}

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='destinos_fecha';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = false;

if($open) {
$now = new DateTime(NULL,$dtz);
$open = new DateTime($open,$dtz);
if($now>$open) blankrerro();
}

/*
CHECK SE ESTÁ NO EXÍLIO
*/
$nomes = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='exilados';");
if(!$nomes->num_rows) $nomes=array();
else {
	$nomes = $nomes->fetch_assoc()['valor'];
	$nomes = trim($nomes);
	$nomes = preg_split("%[,\s+]%uis",$nomes);
}
$nome = $vd->getNome($tn,$cook);
if(!in_array($nome,$nomes)) rerro("Você não foi selecionado para escolher destinos!");

/*
CHECK DO CÓDIGO
*/
$codigo = $_POST['d'][0]['value'];
$qvd = $vd->query("SELECT *,
(SELECT COUNT(tkey) FROM {$vd->destinos}_c WHERE id='{$codigo}') usados
FROM {$vd->destinos} d WHERE id='{$codigo}' AND ativo=1;");
if(!$qvd->num_rows) rerro("Esse código ({$codigo}) não existe ou não está ativo.");

/*
CHECK DOS REQUISITOS
*/
$oz = $qvd->fetch_assoc();
$reqs = trim($oz['requisitos']);
$reqs = preg_split("%[,\s+]%uis",$reqs);
$reqs = checkReqs($tkey,$reqs);

if(!$reqs) rerro("Você não cumpre os requisitos para selecionar esse destino.");

/*
CHECK DA QUANTIDADE
*/
$quantidade = $oz['quantidade']>$oz['usados'] ? true : false;
if(!$quantidade) rerro("Esse destino não está mais disponível.");

/*
CHECK DA RODADA
*/
$rodada = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='rodada';");
if(!$rodada->num_rows) $rodada=1;
else $rodada = +$rodada->fetch_assoc()['valor'];

$qvd = $vd->query("SELECT * FROM {$vd->destinos}_c WHERE rodada={$rodada} AND tkey='{$tkey}';");
if($qvd->num_rows) rerro("Você já escolheu um destino nessa rodada!");

//FINALIZANDO
$vd->query("INSERT INTO {$vd->destinos}_c VALUES ('{$codigo}','{$tkey}',$rodada,NOW(6));");
rerro("Destino escolhido com sucesso.","green",false,"$('.hide').css('display','none');");
?>