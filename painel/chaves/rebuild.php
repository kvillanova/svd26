<?php
const NO_TN = true;
include "../../inc/db/header.php";

if(!$loc) die;
$vd->vd3();
$qvd = $vd->query("SELECT * FROM {$vd->keys_t_last}");
echo $vd->error;
$vd->local();
$vd->query("DELETE FROM {$vd->keys}");
$vd->query("DELETE FROM {$vd->tribes}");

$lines = array();
$i=0;
while($oz = $qvd->fetch_assoc()) {
	extract($oz,EXTR_OVERWRITE);
	$lines[$i]['tkey'] = $tkey;
	$lines[$i]['nome'] = $nome;
	$lines[$i]['nomef'] = $nomef;
	$lines[$i]['tribo'] = $tribo;
	$lines[$i]['genero'] = $genero;
	$lines[$i]['tstatus'] = $tstatus;
	++$i;
}

foreach($lines as $ll):
	extract($ll);
	$vd->query("INSERT INTO {$vd->keys} VALUES ('{$nome}','{$nomef}','{$genero}','{$tkey}');");
	$vd->query("INSERT INTO {$vd->tribes} VALUES ('{$tkey}','{$tribo}',{$tstatus},NOW(6));");
endforeach;
location();
?>