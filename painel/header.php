<?php
function getAddress($x='painel') {
	global $vd;
	if($x==='style') return "../style.css";
	
	$q = isset($_SERVER['QUERY_STRING']) ? "/?" . $_SERVER['QUERY_STRING'] : NULL;
	
	$q = preg_replace("%&?(p|hide_([a-z]+))=([a-zA-Z0-9]+)%", "", $q);
	$q = preg_replace("%/\?&+%", "/?", $q);
	
	$bn = basename($_SERVER['REQUEST_URI']);
	$dn = basename(dirname($_SERVER['REQUEST_URI']));
	
	if($bn===$vd->address_f || $dn===$vd->address_f) return "#";
	else return $vd->address_f."painel/".$x.$q;
}

define( "NO_TN", true );
require_once __DIR__ . "/../inc/db/header.php";
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Painel de Administração</title>
<?php
include __DIR__ . "/../inc/styles/og.php";
include __DIR__ . "/../inc/styles/rel.php";
?>
<link rel="stylesheet" href="<?=$vd->address_f;?>painel/style.css?v=<?=microtime(true);?>">
<link rel="shortcut icon" href="<?=$vd->address_f;?>painel/veto.ico" />
</head>
<body>
<header>
<nav>
<h2>Painel</h2>
<div>
<a href="<?=getAddress('chaves');?>"><p><i class="fa fa-key"></i> Chaves</p></a>
<a href="<?=getAddress('provas');?>"><p><i class="fa fa-gamepad"></i> Provas</p></a>
<a href="<?=getAddress('idolos');?>"><p><i class="fa fa-binoculars"></i> Ídolos</p></a>
<a href="<?=getAddress('servicos');?>"><p><i class="fa fa-concierge-bell"></i> Serviços</p></a>
</div>
</nav>
<?php if(!isset($_GET['developer'])): ?>
<a href="?developer=1"><p><i class="fa fa-square"></i> Modo developer desativado</p></a>
<?php else: ?>
<a href="?"><p><i class="fas fa-check-square"></i> Modo developer ativado</p></a>
<?php endif; ?>
</header>