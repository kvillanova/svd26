<?php 
require_once "../kvl.php";
$vd = new KvL;
if($_SERVER['HTTP_HOST']==='localhost') $vd->local();

$a = $_POST['script'];

$x = array_values(array_filter(explode("#", $a)));

if(empty($x)) { $vd->digiteHelp(); die(); }

if(preg_match("%^help;?$%uis", $x[0])) { $vd->comandosBanco(); die(); } 
if(preg_match("%^about$%uis", $x[0])) { $vd->creditos(); die(); }

##LOOP PRINCIPAL DOS COMANDOS
foreach($x as $i => $v):
$v = trim($v);

$b = explode(":", $v, 2);

//ADD
if(preg_match("%^add$%uis", $b[0])):

if(empty($b[1])) { $vd->textarea("Não tá faltando parâmetro aí?"); die(); }

$c = explode(";", $b[1]);
$c = array_filter($c);

foreach($c as $cc):
	if(!preg_match("%(\pL{3,20}):([-]?\d+(?:[.,]\d+)?):((?:\pL{3,}(?:[\pL\s.]+)?)|(?:[\"'].*?[\"']))(?:\:(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}))?%uis",$cc,$d)) { 
	$vd->textarea("Não tá faltando parâmetro aí?"); die(); 
}

$nome = "(SELECT tkey FROM ".$vd::KEYS." WHERE nome='{$d[1]}')";
$valor = preg_replace("%\,%", ".", $d[2]);
$valor = (float) $valor;
$descr = addslashes(trim($d[3],"'".'"'));
$descr = "'{$descr}'";
$horario = !empty($d[4]) ? "'{$d[4]}'" : "NOW(6)";

$vd->query("INSERT INTO ".$vd::BANK." VALUES ($nome,$horario,$valor,$descr, REPLACE(PASSWORD(NOW(6)),'*','') );");

$t = <<<LAT
FUNDOS ADICIONADOS:
Pessoa: {$nome}, Valor: {$valor}, Descrição: {$descr}.
LAT;

$vd->textarea($t);

endforeach;

//REMOVE
elseif(preg_match("%^remove?$%uis", $b[0])):

if(empty($b[1])) { $vd->textarea("Não tá faltando parâmetro aí?"); die(); }

$c = explode(";", $b[1]);
$c = array_filter($c);

foreach($c as $cc):
	if(!preg_match("%([A-Z0-9]{40})%uis",$cc,$d)) { 
	$vd->textarea("Não tem coisa errada aí não?"); die(); 
}

$tid = $d[1];
$qvd = $vd->query("SELECT * FROM ".$vd::BANK." WHERE tid='{$tid}';");
if($qvd->num_rows<1) { $vd->textarea("TID #{$tid} não encontrado."); continue; }

$qvd = $qvd->fetch_assoc();
$nome = $vd->getNome($vd::BANK,$qvd['tkey']);
$valor = $qvd['valor'];
$horario  = $qvd['tpost'];
$descr = $qvd['descr'];

$vd->query("DELETE FROM ".$vd::BANK." WHERE tid='{$tid}';");

$t = <<<LAT
TRANSAÇÃO REMOVIDA:
Pessoa: {$nome}, Valor: {$valor}, Descrição: {$descr}, Horário: {$horario}, TID: {$tid}.
LAT;

$vd->textarea($t);

endforeach;

else:
$vd->textarea("Não há nada de útil para mim aí.");

endif;
endforeach; //fim da linha.

?>