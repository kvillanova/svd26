<?php
if(!isset($h2)) $h2 = "Antes de começar, digite sua chave:";
if(!isset($h4)) $h4 = "Qual o seu identificador?";
?>
<!doctype html>
<html>
<head>
<?php
const NO_OWN_CSS = true;
include __DIR__."/../styles/og.php"; 
include __DIR__."/../styles/rel.php";
?>
</head>
<body>
<div class="err-bar">
<h2><?=$h2;?></h2>
<h4><?=$h4;?></h4>
<form action="" method="post">
<input type="text" name="code" maxlength="<?=!isset($maxlen)?8:$maxlen;?>" />
<button>OK</button>
</div>
</form>
<div id="r"></div>
<script>
$("form").submit(function(e) {
	e.preventDefault();
	$("button").attr("disabled",true);
	$.ajax({
		"url":"",
		"type":"POST",
		"data":$("form").serializeArray()
	}).done(function(m){
		$("#r").html(m);
		$("button").attr("disabled",false);
	});
});
</script>
</body>
</html>