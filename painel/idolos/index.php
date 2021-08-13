<?php
/* PAINEL DE ADMINISTRAÇÃO - IDOLOS */
$force_microtime=1;
require_once "../header.php";
?>
<h1><i class="fa fa-binoculars"></i> Ídolos</h1>
<?php
$dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false;
if($dev) include "../kvlrow.php";
?>
<div class="section">
<div class="botoes">
<button class="section ativo" data-req="idolos">Ver Ídolos</button>
<button class="section" data-req="pistas">Ver Pistas</button>
</div>
</div>
<div id="r"></div>
<script>
$("button.section").on("click",function(e){
	var $x = $(this).data("req");
	$("button.section").removeClass("ativo");
	$(this).addClass("ativo");
	$.ajax({
		'url':'ajax/'+$x,
		'type':'POST'
	}).done(function(m){
		$("#r").html(m);
	});
});
$("button[data-req=idolos]").trigger("click");
</script>
<?php if($dev): ?>
<script src="../kvl.js"></script>
<?php endif; ?>
</body>
</html>