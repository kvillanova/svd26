<?php
if(defined("FORCE_STATUSES")) {
	if(is_array(FORCE_STATUSES)) $statuses = implode(",",FORCE_STATUSES);
	else $statuses = FORCE_STATUSES;
}
else {
	if($pstatus===0) $statuses="3";
	elseif($pstatus===1) $statuses="1,3";
	elseif($pstatus===2) $statuses="1,3,4";
}

$code = isset($_POST['code']) ? $_POST['code'] : NULL;
$cook = isset($_COOKIE[$tn]) ? $_COOKIE[$tn] : NULL;

$ua = $_SERVER['HTTP_USER_AGENT'];
$salt = array(
'gpu87J4/~Qo4[~2zLK= ^mU+yA(_,:79Ts1)v~umQtziu|5NEgOS-v-<1rYxG|2F',
'L@+^xU/tv[.(+9xyF;$t`D<e{.YF(,QeGesfk<geC-^,ro`h@v]N8K[sDc?^a^yW'
);

if($cook) {
	$tncheck = $vd->checkCook($cook,$tn,$statuses);
	if($tncheck) return $tkey = $vd->getTkey($tn,$cook);
	unset($_COOKIE[$tn]);
	setcookie($tn,"",time()-3600,'/');
	echo "<script>location.reload();</script>";
	die;
}
elseif($code) {
	$qvd = $vd->query("SELECT cook FROM {$tn} WHERE tkey='{$code}';");
	echo $vd->error;
	if($qvd->num_rows>0) {
		$cook = $qvd->fetch_assoc()['cook'];
	}
	else {
		$cook = mdfy($code.$ua.$tn,$salt,30);
	}
}

define('__ERRDIR__', __DIR__ . "/../err/");

//SEM SENHA DIGITADA:
if(!$code) { include __ERRDIR__ . "sem-acesso.php"; die; }

//ACESSO SÓ PARA MODERADORES
if($statuses==="3" || $statuses==="2,3") {
	$om = $vd->onlyMods($code);
	if(!$om) { 
		echo <<<L
		<script>$("h2").html("Acesso somente para Moderadores:");$("h4").html("Você é um Moderador?");</script>
L;
	die;
	}
}

//SENHA OFFLINE
$offline = $vd->offlineKey($code,$statuses);
if($offline) { 
		echo <<<L
		<script>$("h2").html("O código que você digitou está offline.");$("h4").html("Por favor, digite outro código.");</script>
L;
	die;	 
}

$err = $vd->insertKey($cook, $ip, $code, $tn);

//CÓDIGO INVÁLIDO
if($err===2) { 
echo <<<L
		<script>$("h2").html("O código que você digitou é inválido.");$("h4").html("Por favor, digite outro código.");</script>
L;
	die;
}

setcookie($tn,$cook,time()+60*60*24*30,'/');
echo "<script>location.reload();</script>";
die;
?>