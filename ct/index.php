<?php
if(!isset($_GET['no'])) die("Cadê o número do CT?");
if(!isset($_GET['tribo'])) die("Cadê a tribo?");

$no = (float) $_GET['no'];
$ino = (int) $no;
$tribo = mb_strtoupper($_GET['tribo']);

const 
TN_NAME = "\$ct_e",
FOOTER_NAME = "unlimited",
NO_CHALLENGE = true,
NO_FINISH = true,
NO_OK=true;
include "../inc/db/header.php";

$tribo = preg_replace("%\s+%","",$tribo);
$xtribo = preg_split("%,%uis",$tribo);
$xtribo = array_map(function($x){
	return "'{$x}'";
},$xtribo);
$xtribo = implode(",",$xtribo);
$xtribo = "WHERE tribo IN({$xtribo})";

if($tribo==="FIM") $winner=true; else $winner=false;

$nome = $vd->getNome($tn,$cook);
$nnomes[] = "'{$nome}'";
if(isset($_GET['imunes']) && !empty($_GET['imunes'])) {
	$imunes = preg_split("%,%",$_GET['imunes'],-1,PREG_SPLIT_NO_EMPTY);
	$imunes = array_map(function($x){
		return "'{$x}'";
	},$imunes);
	$nnomes = array_unique(array_merge($nnomes,$imunes));
}
$nnomes = implode(",",$nnomes);
$nnome = " AND nome NOT IN({$nnomes}) AND tstatus IN(0,1)";
$tkey = $vd->getTkey($tn,$cook);
$mod = $vd->getTribe($tn,$cook);
if($mod==='MOD') $mod = true; else $mod = false;
?>
<!doctype html>
<html>
<head>
<?php
$force_microtime=1;
include "../inc/styles/og.php";
include "../inc/styles/rel.php";
	
$voto = $vd->query("SELECT voto FROM ".$vd::CT." WHERE num={$no} AND tribo='{$tribo}' AND tkey='{$tkey}';");
echo $vd->error;
$voto = $voto->fetch_assoc()['voto'] ?? NULL;
$just = $vd->query("SELECT justificativa FROM ".$vd::CT." WHERE num={$no} AND tribo='{$tribo}' AND tkey='{$tkey}';");
$just = $just->fetch_assoc()['justificativa'] ?? NULL;
?>
</head>
<body>
	<h1>Conselho Tribal</h1>
	<h3>CT #<?=str_pad($ino,2,0,STR_PAD_LEFT);?>
	<?php
	if($tribo!=='ALL') echo " | Tribo: {$tribo}";	
	?></h3>
	<?php
	if($mod) include "403/admin.php";
	else include "403/participantes.php";
	?>