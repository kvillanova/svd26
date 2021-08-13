<form>
	<?php if($tribo==="FIM"): ?>
	<h3>Quem você deseja ver como vencedor da temporada?</h3>
	<?php elseif($tribo==="IDL"): ?>
	<h3>Para quem você deseja entregar o ídolo?</h3>
	<?php else: ?>
	<h3>Quem você deseja eliminar?</h3>
	<?php endif; ?>
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
	<h3>Justificativa</h3>
	<textarea name="justificativa" maxlength="8000"><?=$just;?></textarea>
	<input type="hidden" name="num" value="<?=$no;?>">
	<input type="hidden" name="tribo" value="<?=$tribo;?>">
	<button type="submit">Enviar</button>
	</form>
	<div id="r"></div>
	<script>
	$(".pessoa").mouseup(function(e) {
		checkB = $(this).parent().children("input");
		if(checkB.prop("checked")===true) checkB.prop("checked", false);
		else {
			$(".pessoa-container input").prop("checked",false);
			checkB.prop("checked", true);
		}
	});
		
	$("form").submit(function(e) {
		e.preventDefault();
		$.ajax({
			url:"resp.php",
			type:'POST',
			data:$("form").serializeArray()
		}).done(function(msg) { 
			$("#r").html(msg);
			$("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
		});
	});
</script>
</body>
</html>