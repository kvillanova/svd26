<?php
const
TN_NAME = "\$services_e",
FOOTER_NAME = "unlimited",
NO_CHALLENGE = true,
FORCE_STATUSES = "0,1,2,3";
include "../inc/db/header.php";

if(basename($_SERVER['SCRIPT_NAME'])==='conta.php'):
	if($vd->getTribe($tn,$cook)!=="MOD") { header("Location: ./"); die; }
	$tkey = $_GET['t'] ?? die("Faltou a chave");
	$tribo = $vd->getTribe($tn,$tkey);
	$genero = $vd->getGen($tn,$tkey);
	$nome = $vd->getNome($tn,$tkey);
	$godmode = true;

else:
	$tkey = $vd->getTkey($tn,$cook);
	$tribo = $vd->getTribe($tn,$cook);
	$genero = $vd->getGen($tn,$tkey);
	$nome = $vd->getNome($tn,$tkey);
	$godmode = false;
endif;
?>
<!doctype html>
<html>
<head>
<?php
$force_microtime=1;
include "../inc/styles/og.php";
include "../inc/styles/rel.php";
?>
</head>
<body>
<header>
<nav>
<h2><i class="fa fa-campground"></i> Camp</h2>
<div>
<?php if(!$godmode): ?>
<a href="./"><p><i class="fa fa-lg fa-map-signs"></i> Destinos</p></a>
<a href="./idolo"><p><i class="fas fa-lg fa-binoculars"></i> Ídolo</p></a>
<a href="./pistas"><p><i class="fas fa-lg fa-map-marked-alt"></i> Pistas</p></a>
<?php else:  ?>
<a href="./conta?t=<?=$tkey;?>&destinos"><p><i class="fa fa-lg fa-map-signs"></i> Destinos</p></a>
<a href="./conta?t=<?=$tkey;?>&idolo"><p><i class="fas fa-lg fa-binoculars"></i> Ídolo</p></a>
<a href="./conta?t=<?=$tkey;?>&pistas"><p><i class="fas fa-lg fa-map-marked-alt"></i> Pistas</p></a>
<?php endif; ?>
</div>
</nav>
<p>Olá, Sr<?=$genero==='a'?$genero:NULL;?>. <?=mb_strtoupper($nome);?>.</p>
</header>