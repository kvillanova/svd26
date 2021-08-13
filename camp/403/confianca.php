<?php
$d = array_map(function($x){
	return preg_replace("%^img_%","",$x);
},$_POST['d']);

$rodada = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='rodada';");
if(!$rodada->num_rows) $rodada=1;
else $rodada = +$rodada->fetch_assoc()['valor'];

$pairs=array();
foreach($d as $i => $dd) {
	$j=$i+1;
	$pairs[] = "('{$tkey}',{$rodada},'{$dd}',{$j})";
}

$d = implode(",",$d);
$pairs = implode(",",$pairs);
$vd->query("INSERT INTO {$vd->confianca}_a VALUES ('{$tkey}',{$rodada},NOW(6),'{$d}');");
$vd->query("INSERT INTO {$vd->confianca}_x VALUES {$pairs} ON DUPLICATE KEY UPDATE pos=VALUES(pos);");
rerro("Lista enviada com sucesso!","green",false,"$('.hide').css('display','none');");
?>