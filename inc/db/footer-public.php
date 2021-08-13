<?php
$tn .= "_p";

$code = isset($_POST['code']) ? $_POST['code'] : NULL;

if(isset($_COOKIE[$tn]))
	$cook = $_COOKIE[$tn];
else {
	$cook = time();
	$cook = "/{$code}/{$cook}";
}

$tncheck = $vd->checkCookPublic($cook,$tn);
if($tncheck!==1):

define('__ERRDIR__', dirname(dirname(__FILE__)));

//SEM SENHA DIGITADA:
if(!$code) { include __ERRDIR__ . "/err/sem-acesso-pub.php"; die(); }

$err = $vd->insertKeyPublic($cook, $tn);

//CÓDIGO USADO
if($err===1) { include __ERRDIR__ . "/err/cod-usado-pub.php"; die(); }

setcookie($tn,$cook,time()+60*60*24*2,'/');
	
endif; //#ipchk

$tkey = $vd->getTkey($tn,$cook);
?>