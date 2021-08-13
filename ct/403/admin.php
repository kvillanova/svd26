<?php
$closed = $vd->query("SELECT closed FROM ".$vd::CT."_c WHERE num={$no} AND tribo='{$tribo}';");
if($closed->num_rows===0) {
	$vd->query("INSERT INTO ".$vd::CT."_c VALUES($no,'{$tribo}',false)");
	$closed = 0;
}
else {
	$closed = $closed->fetch_assoc()['closed'];
	$closed = (int) $closed;
}
?>
<h3>Participantes do CT</h3>
<div class="pessoas">
<?php 
$pessoas = glob("../inc/mini/*.jpg");
$pessoas = $vd->query("SELECT nome FROM ".$vd::KEYS_T_LAST." {$xtribo}{$nnome}");

$nomes = array();
while($oz = $pessoas->fetch_assoc()) {
	$nomes[] = removeAccents($oz['nome']);
}
sort($nomes);

foreach($nomes as $nome):
	if($voto===$nome) $checked = 'checked="checked"';
	else $checked = NULL;
?>
<div class="pessoa-container<?=$winner?" winner":NULL;?>"><input type="checkbox" name="voto[]" value="<?=$nome;?>"<?=$checked;?>><div class="pessoa"><img src="../inc/mini/<?=ucwords($nome);?>.jpg" /></div></div>
<?php endforeach; ?>
</div>
<button data-req="votos" class="mini">Ver votos</button>
<button data-req="contagem" class="mini">Ver contagem</button>
<button data-req="encerrar" class="mini<?php if($closed===0) echo " red"; else echo " green";?>"><?php if($closed===0) echo "Encerrar CT"; else echo "Reiniciar votação";?></button>
<a href="./texto/<?=$no;?>-<?=$tribo;?>" target="_blank"><button data-req="texto" class="mini">Ver texto</button></a>
<div id="main"></div>
<script>
$(".pessoa").mouseup(function(e) {
	checkB = $(this).parent().children("input");
	if(checkB.prop("checked")===true) checkB.prop("checked", false);
	else {
		$(".pessoa-container input").prop("checked",false);
		checkB.prop("checked", true);
	}
});

$("button").mousedown(function(){
	if($(this).data("req")==="texto") return false;
	$.ajax({
		url:"rec.php",
		type:"POST",
		data:{v:$(this).data("req"),num:'<?=$no;?>',tribo:'<?=$tribo;?>',closed:<?=$closed;?>}
	}).done(function(m){
		$("#main").html(m);	
	});

	$("button").removeClass("invert");
	$(this).addClass("invert");
});
$("button[data-req=votos]").trigger("mousedown");
</script>
</body>
</html>