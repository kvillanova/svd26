<?php 
require_once "kvl.php";

if($loc) $vd = new KvLKeys(true);
else $vd = new KvLKeys();

$txt = $_POST['script'];

if(empty(trim($txt))) return $vd->digiteHelp();
if(preg_match("%^(\s*)#?help;?$%uis", $txt)) return $vd->comandos();
if(preg_match("%^(\s*)#?about$%uis", $txt)) return $vd->creditos();
if(preg_match("%^(\s*)#?clear%uis", $txt)) return $vd->textclear();

if(preg_match("%^(\s*)#?delete%uis", $txt)) { 
	$txt = trim(preg_replace("%^.*?delete%uis","",$txt));
	$txt = preg_split("%\s+%uis",$txt);
	return $vd->removerJogador($txt);
}

if(preg_match("%^(\s*)#?(quitter|desistente)%uis", $txt)) { 
	$txt = trim(preg_replace("%^.*?(quitter|desistente)%uis","",$txt));
	$txt = preg_split("%\s+%uis",$txt);
	return $vd->quitterJogador($txt);
}

$txt = preg_split("%[\n;]%uis",$txt);
$txt = array_map(function($x){
	$x =  preg_split("%[\t:]%uis",$x);
	$x = array_map(function($xx){
		$xx = trim($xx);
		if(!empty($xx)) return $xx;
	},$x);
	return $x;
},$txt);

foreach($txt as $txtl) {
	check($txtl);	
}	   
	   
function check($a) {
	global $vd;
	$count = count($a);
	
	if($count===2) {
		if(strlen($a[1])===1 && is_numeric($a[1])) return $vd->mudarTstatus($a[0],$a[1]);
		if(strlen($a[1])===1) return $vd->mudarGen($a[0],$a[1]);
		if(strlen($a[1])===3) return $vd->mudarTribo($a[0],$a[1]);
		if(strlen($a[1])===8) return $vd->mudarChave($a[0],$a[1]);
		
		$vd->textarea("Comando não reconhecido.");
	}
	
	if($count>=3) {
		$nome = $a[0]; 
		unset($a[0]);
		
		$params = array_map(function($x){
			if(strlen($x)===1) return 'gen';
			if(strlen($x)===3) return 'tribo';
			if(strlen($x)===8) return 'tkey';
		},$a);
		$a = array_combine($params,$a);
		extract($a);
		
		return $vd->inserirJogador($nome,$tribo??'YYZ',$gen??'o',$tkey??false);
	}
}

?>