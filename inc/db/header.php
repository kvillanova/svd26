<?php
date_default_timezone_set("America/Sao_Paulo");
$dtz = new DateTimeZone("America/Sao_Paulo");

require_once(__DIR__."/class.php");
require_once(__DIR__."/ip.php");
require_once(__DIR__."/functions.php");

if(defined("TN_SUBDIR") && TN_SUBDIR) {
	if(!defined("FINISH_SUBDIR")) define("FINISH_SUBDIR",true);
	if(!defined("NO_QUERY")) define("NO_QUERY",true);
}

if(defined("NO_CHALLENGE") && NO_CHALLENGE) {
	if(!defined("NO_FINISH")) define("NO_FINISH",true);
	if(!defined("NO_OK")) define("NO_OK",true);
}

if(defined("ATUAL_FLOAT") && ATUAL_FLOAT) {
	if(!defined("TEND_NO")) define("TEND_NO",999);
}

if(in_array(basename($_SERVER['SCRIPT_FILENAME'],".php"),array("checkt","check","resp"))) {
	if(!defined("NO_FINISH")) define("NO_FINISH",true);
}

if(in_array(basename($_SERVER['SCRIPT_FILENAME'],".php"),array("checkt","check","resp","ignite"))) {
	if(!defined("NO_FINISH")) define("NO_FINISH",true);
	if(!defined("NO_OK")) define("NO_OK",true);
}

if(!defined("NO_TN")) define("NO_TN",false);
if(!defined("ONLY_TN")) define("ONLY_TN",false);
if(!defined("NO_QUERY")) define("NO_QUERY",false);
if(!defined("NO_FOOTER")) define("NO_FOOTER",false);
if(!defined("NO_FINISH")) define("NO_FINISH",false);
if(!defined("FINISH_SUBDIR")) define("FINISH_SUBDIR",false);
if(!defined("TN_SUBDIR")) define("TN_SUBDIR",false);
if(!defined("TN_NAME")) define("TN_NAME",false);
if(!defined("FOOTER_NAME")) define("FOOTER_NAME",false);
if(!defined("NO_CHALLENGE")) define("NO_CHALLENGE",false);
if(!defined("NO_OK")) define("NO_OK",false);

if(!defined("FORCE_VD3")) define("FORCE_VD3",false);
if(!defined("ATUAL_FLOAT")) define("ATUAL_FLOAT",false);
if(!defined("TEND_NO")) define("TEND_NO",2);
if(!defined("ATUAL_NO")) define("ATUAL_NO",false);

if((in_array($_SERVER['HTTP_HOST'],array("localhost","127.0.0.1")) || preg_match("%^192\.168\.[0|1]\.(\d{1,3})$%",$_SERVER['HTTP_HOST'])) && !FORCE_VD3) $loc = true;
else $loc = false;
	
if(!$loc) $vd = new VDSQL();
else $vd = new VDSQL(true);

if(NO_TN) return;

if(TN_NAME) {
	$prefix = $vd::PREFIX;
	if(preg_match("%^{$prefix}%",TN_NAME)) $tn = TN_NAME;
	else $tn = $prefix . TN_NAME;
}
elseif(TN_SUBDIR) $tn = $vd::PREFIX . basename(dirname(getcwd()));
else $tn = $vd::PREFIX . basename(getcwd());

if(ONLY_TN) return;

if(!NO_CHALLENGE) $pstatus = $vd->getChallengeStatus($tn);
else $pstatus = 1;

if(!NO_QUERY && !NO_CHALLENGE) {
	if($pstatus===-1 && file_exists(getcwd()."/query.php")) {
		include getcwd() . "/query.php";
	}
}

if(NO_CHALLENGE) {
	if(file_exists(getcwd()."/query.php")) {
		include getcwd() . "/query.php";
	}
}

if(!NO_FOOTER) {
	if(FOOTER_NAME) include __DIR__ . "/footer-".FOOTER_NAME.".php";
	elseif($pstatus<3 && $loc) include __DIR__ . "/footer-unlimited.php";
	elseif($pstatus<3) include __DIR__ . "/footer.php";
	else include __DIR__ . "/footer-public.php";
}

if(!NO_CHALLENGE) {
	$atual = $vd->getAtual($tn,$cook,true);

	if(ATUAL_FLOAT) {
		$atual_int = atualfloat($atual,0);
		$atual_min = atualfloat($atual,1);
		$atual = &$atual_int;
		if($atual_int<0) {
			$atual_int=0;
			$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',0,NOW(6),NULL,NULL);");
		}
	}
	else {
		$atual = atualfloat($atual,0);
		$atual_int = &$atual;
		if($atual<0) {
			$atual=0;
			$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',0,NOW(6),NULL,NULL);");
		}
	}
}

if(!NO_FINISH) {

	$tend = TEND_NO!==999 ? $vd->getTend($tn,$cook,TEND_NO) : $vd->getTend($tn,$cook,"{$atual_int}.999");

	if(basename($_SERVER['SCRIPT_FILENAME'],".php")!=='finish') {
		if(!empty($tend) && !FINISH_SUBDIR) location("finish");
		if(!empty($tend) && FINISH_SUBDIR) location("../finish");
	}

	else {
		if(empty($tend)) location();
	}
	
}

if(!NO_OK) {

if(is_numeric(ATUAL_NO)) {
	if($atual!==ATUAL_NO && TN_SUBDIR) location("../"); 
}
	
	if(basename($_SERVER['SCRIPT_FILENAME'],".php") === 'ok' && !TN_SUBDIR && $atual<1) return;

	if(basename($_SERVER['SCRIPT_FILENAME'],".php") !== 'index' && !TN_SUBDIR && $atual<1) location();
	if(basename($_SERVER['SCRIPT_FILENAME'],".php") === 'index' && !TN_SUBDIR && $atual<1) location("ok");

	if(basename($_SERVER['SCRIPT_FILENAME'],".php") === 'ok' && $atual>0) location();
}
?>