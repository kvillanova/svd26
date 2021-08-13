<?php 
require_once "kvl.php";

if($loc) $vd = new KvLServicos(true);
else $vd = new KvLServicos();

$txt = $_POST['script'];

if(empty(trim($txt))) return $vd->digiteHelp();
if(preg_match("%^(\s*)#?help;?$%uis", $txt)) return $vd->comandos();
if(preg_match("%^(\s*)#?about$%uis", $txt)) return $vd->creditos();
if(preg_match("%^(\s*)#?clear%uis", $txt)) return $vd->textclear();

if(preg_match("%^(\s*)#?delete%uis", $txt)) { 
	$txt = preg_split("%\t%uis",$txt,-1,PREG_SPLIT_NO_EMPTY);
	unset($txt[0]);
	return $vd->removerServicos($txt);
}

if(preg_match("%^(\s*)#?servi[cç][oe]s%uis", $txt)) { 
	$txt = preg_split("%\n%uis",$txt);
	unset($txt[0]);
	$txt = array_map(function($x){
		$x = preg_split("%\t%uis",$x,-1,PREG_SPLIT_NO_EMPTY);
		return $x;
	},$txt);
	foreach($txt as $tt) $vd->inserirServico($tt[0],$tt[1]);
	return;
}

if(preg_match("%^(\s*)#?vars?%uis", $txt)) { 
	$txt = preg_split("%\n%uis",$txt);
	unset($txt[0]);
	$txt = array_map(function($x){
		$x = preg_split("%\t%uis",$x);
		return $x;
	},$txt);
	return $vd->inserirVars($txt);
}

if(preg_match("%^(\s*)#?unset?%uis", $txt)) { 
	$txt = preg_split("%\n%uis",$txt);
	unset($txt[0]);
	$txt = array_map(function($x){
		$x = preg_split("%\t%uis",$x);
		return $x;
	},$txt);
	return $vd->unsetVars($txt);
}
?>