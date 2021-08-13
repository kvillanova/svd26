<?php
const
NO_CHALLENGE=true;
include "../inc/db/header.php";

if(!$loc && $vd->getTribe($tn,$cook)!=='MOD') die;

$file = file_get_contents("403/perguntas.txt");
$file = preg_split("%-----%",$file,-1,PREG_SPLIT_NO_EMPTY);
$perguntas = array();
foreach($file as $i => $ff) {
	$ff = trim($ff);
	$ff = preg_split("%\r?\n%",$ff,-1,PREG_SPLIT_NO_EMPTY);
	$perguntas[$i]["pergunta"] = trim($ff[0]);
	unset($ff[0]);
	$ff = array_values($ff);
	
	$perguntas[$i]["alternativas"] = [];
	foreach($ff as $j => $alternativa) {
		if(preg_match("%^#%",$alternativa)) $perguntas[$i]["correta"] = $j;
		$alternativa = preg_replace("%^#?[A-Z].\s%", "",$alternativa);
		$perguntas[$i]["alternativas"][] = $alternativa;
	}
}

$json = json_encode($perguntas,JSON_UNESCAPED_UNICODE);
file_put_contents("403/perguntas.json",$json);
?>