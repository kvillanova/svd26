<?php
const
ATUAL_NO=2,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php"; 

function mdfy2($x) {
	$m = mdfy($x,array("SOMEDAYSMXMX","MAKEITRIGHT"),15);
	$x = ord($x);
	$x = dechex($x);
		
	return substr($m,0,8) . $x . substr($m,8);
}
?>
<!doctype html>
<html>
<head>
<?php
$jquery_ui = true;
$jquery_ver = "1.11.2";
include "../../inc/styles/og.php";
include "../../inc/styles/rel.php";
?>
</head>
<body>
<h1>QG da Moderação</h1>
<h3>
<?php
$itens = file_get_contents("403/lista.txt");
$itens = preg_split("%\r?\n%uis",$itens,-1,PREG_SPLIT_NO_EMPTY);
	
$cabecalho = $itens;
array_pop($cabecalho);
echo implode(" • ",$cabecalho);
?>
</h3>
<form>
<input type="text" name="resp" maxlength="100">
<input type="hidden" name="at" value="<?=$atual;?>">
<button>Enviar</button>
</form>
<div id="r"></div>
<div id="letras">
<?php
$letras = implode("",$itens);
$letras = mb_strtoupper(removeAccents($letras,true));
$letras = str_split($letras);
shuffle($letras);

foreach($letras as $ll): ?>
<img class="letra" src="<?=mdfy2($ll);?><?=$atual;?>.png" alt="?" style="top:<?=mt_rand(0,70);?>%;left:<?=mt_rand(25,65);?>%">
<?php endforeach; ?>
</div>
<script>
$("form").submit(function(e) {
e.preventDefault();
$.ajax({
"url":"resp.php",
"type":"POST",
"data": $(this).serializeArray()
}).done(function(m) {
$("#r").html(m);
});
});
$(".letra").draggable();
</script>
</body>
</html>