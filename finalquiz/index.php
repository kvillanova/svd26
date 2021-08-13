<?php
const
TEND_NO=16;
include "../inc/db/header.php"; 
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
<h1>Quiz 10 Anos</h1>
<?php
$json = json_decode(file_get_contents("403/perguntas.json"),true);
$json = $json[$atual-1];

$pergunta = &$json['pergunta'];
$alternativas = &$json['alternativas'];
?>
<p class="pergunta"><?="{$atual}. $pergunta";?></p>
<form>
<div id="alternativas">
<?php foreach($alternativas as $i => $aa): ?>
<div class="alternativa">
<input type="radio" name="resp" value="<?=$i;?>">
<i class="fa fa-square fa-lg"></i>
<p><?=$aa;?></p>
</div>
<?php endforeach; ?>
</div>
<input type="hidden" name="at" value="<?=$atual;?>">
<button>Enviar</button>
</form>
<div id="r"></div>
<script>
$(".alternativa").on("click",function(){
	$("input[type=radio]").attr("checked",false);
	$(this).children("input").attr("checked",true);
	$(".alternativa").removeClass("selecionada");
	$(this).addClass("selecionada");
	$(".alternativa").find("i").removeClass("fa-check-square").addClass("fa-square");
	$(this).find("i").addClass("fa-check-square").removeClass("fa-square");
});
$("form").submit(function(e){
	e.preventDefault();
	$.ajax({
		url:'resp.php',
		type:'POST',
		data:$(this).serializeArray()
	}).done(function(m){
		$("#r").html(m);
	});
});
</script>
</body>
</html>