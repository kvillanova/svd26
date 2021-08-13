<?php 
require_once "../kvl.php";
$vd = new KvL;
if($_SERVER['HTTP_HOST']==='localhost') $vd->local();

$a = $_POST['script'];

$x = array_values(array_filter(explode("#", $a)));

if(empty($x)) { $vd->digiteHelp(); die(); }

if(preg_match("%^help;?$%uis", $x[0])) { $vd->comandosProvas(); die(); } 
if(preg_match("%^about;?$%uis", $x[0])) { $vd->creditos(); die(); }

##LOOP PRINCIPAL DOS COMANDOS
foreach($x as $i => $v):
$v = trim($v);

$b = explode(":", $v, 2);

//DESAFIOS
if(preg_match("%^challenges?$%uis", $b[0])):

if(empty($b[1])) { $vd->textarea("Não tá faltando parâmetro aí?"); die(); }

$c = explode(";", $b[1]);
$c = array_filter($c);

$t = '<br/>MUDANÇA DE PROVA:';
$vd->textarea($t);

foreach($c as $cc):
$cc = trim($cc);
if(!preg_match("%^/?([a-z0-9_]{3,80})\s*:\s*([0-9]{1,2}|null)(?:\s*:\s*([a-z0-9]+)?)?(?:\s*:\s*((?:\pL|[a-z0-9\s])+)?)?(?:\s*:\s*([a-z]+)?)?$%uis", $cc, $d)) 
{ $vd->textarea("Desaprendeu a programar?"); die(); }

$id = $d[1];
$num = $d[2];
$rotulo = mb_strtoupper($d[3]) ?? NULL; 
if(preg_match("%[a-z]{1,2}[0-9]{1,2}%uis", $rotulo)) $rotulo = preg_replace("%([a-z]{1,2})([0-9]{1,2})%uis", "$1#$2", $rotulo);
$nome = $d[4] ?? NULL;
$tipo = $d[5] ?? NULL;

$vd->mudarChallenge($id,$num, $rotulo, $nome, $tipo);
$t = <<<M
DESAFIO: /{$id}
num: {$num}, 
rotulo: {$rotulo},
nome: {$nome},
tipo: {$tipo}.
--
M;

$vd->textarea($t);

endforeach;

//DROP
elseif(preg_match("%^drop?$%uis", $b[0])):
if(empty($b[1])) { $vd->textarea("Não tá faltando parâmetro aí?"); die(); }

$c = explode(";", $b[1]);
$c = array_filter($c);

$t = '<br/>DROP DE PESSOAS:';
$vd->textarea($t);

foreach($c as $cc):
$cc = trim($cc);
if(!preg_match("%^/?([a-z0-9_]{3,80}):([a-z]{3,20})$%uis", $cc, $d)) 
{ $vd->textarea("Desaprendeu a programar?"); die(); }

$id = $d[1];
$nome = $d[2];

$q = $vd->derrubarJogador($id,$nome);
if($q) die($vd->textarea($q));

$t = <<<M
Pessoa: {$nome}, Prova: {$id}
M;

$vd->textarea($t);

endforeach;

//LIMPAR TELA
elseif(preg_match("%^(clear|limpar?)%uis", $b[0])):
$vd->textclear();

else:
$vd->textarea("Não há nada de útil para mim aí.");

endif;

endforeach; //fim da linha.

?>