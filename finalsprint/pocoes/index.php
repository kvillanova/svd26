<?php
const
ATUAL_NO=3,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";

?>
<!doctype html>
<html>
<head>
<?php
$jquery_ui=true;
$jquery_ver="1.11.2";
include "../../inc/styles/og.php";
include "../../inc/styles/rel.php";
?>
</head>
<body>
<h1>Poções</h1>
<div id="drops">
<div class="droppable" id="drop1"></div>
<div class="droppable" id="drop2"></div>
<div class="droppable" id="drop3"></div>
<div class="droppable" id="drop4"></div>
<div class="droppable" id="drop5"></div>
<div class="droppable" id="drop6"></div>
</div>
<form method="post" action="javascript:send();">
<input type="hidden" id="v1" name="v1" value="" />
<input type="hidden" id="v2" name="v2" value="" />
<input type="hidden" id="v3" name="v3" value="" />
<input type="hidden" id="v4" name="v4" value="" />
<input type="hidden" id="v5" name="v5" value="" />
<input type="hidden" id="v6" name="v6" value="" />
<div id="garrafas">
<img src="img/preto.png" alt="preto0" class="garrafa" />
<img src="img/rosa.png" alt="rosa0" class="garrafa" />
<img src="img/laranja.png" alt="laranja0" class="garrafa" />
<img src="img/marrom.png" alt="marrom0" class="garrafa" />
<img src="img/verdeclaro.png" alt="verdeclaro0" class="garrafa" />
<img src="img/framboesa.png" alt="framboesa0" class="garrafa" />
<img src="img/vermelho.png" alt="vermelho0" class="garrafa" />
<img src="img/verde.png" alt="verde0" class="garrafa" />
<img src="img/amarelo.png" alt="amarelo0" class="garrafa" />
<img src="img/roxo.png" alt="roxo0" class="garrafa" />
<img src="img/azul.png" alt="azul0" class="garrafa" />
<img src="img/ciano.png" alt="ciano0" class="garrafa" />
<img src="img/laranja.png" alt="laranja1" class="garrafa" />
<img src="img/ciano.png" alt="ciano1" class="garrafa" />
<img src="img/rosa.png" alt="rosa1" class="garrafa" />
<img src="img/verdeclaro.png" alt="verdeclaro1" class="garrafa" />
<img src="img/vermelho.png" alt="vermelho1" class="garrafa" />
<img src="img/marrom.png" alt="marrom1" class="garrafa" />
<img src="img/azul.png" alt="azul1" class="garrafa" />
<img src="img/roxo.png" alt="roxo1" class="garrafa" />
<img src="img/preto.png" alt="preto1" class="garrafa" />
<img src="img/framboesa.png" alt="framboesa1" class="garrafa" />
<img src="img/amarelo.png" alt="amarelo1" class="garrafa" />
<img src="img/verde.png" alt="verde1" class="garrafa" />
</div>
<button type="submit">Testar</button>
</form>
<div id="r"></div>
<script type="text/javascript">
$('.garrafa').draggable({
	revert : function(e) {
	$(this).data("uiDraggable").originalPosition = { top : 0, left : 0 };
	return !e; }
	});
$('.droppable').droppable({
	drop: function(e, ui) { 
	$(this).addClass('yellow');
	$(this).droppable('option', 'accept', ui.draggable);
	alvo = e.target.id; alvo = alvo.substr(4,1);
	$('#v'+alvo).attr('value', ui.draggable.attr('alt'));
	console.log(alvo);
	console.log(ui.draggable.attr('alt'));
	},
	out: function(e, ui) { 
	$(this).removeClass('yellow'); 
	$(this).droppable('option', 'accept', '.garrafa');
	alvo = e.target.id; alvo = alvo.substr(4,1);
	if(ui.draggable.attr('alt')==$('#v'+alvo).attr('value')) { $('#v'+alvo).attr('value', '');  }
	},
});

function send() {
$.ajax({
  type: "POST",
  url: "resp.php",
  data: { v1: $('#v1').attr('value'), 
  v2: $('#v2').attr('value'), 
  v3: $('#v3').attr('value'), 
  v4: $('#v4').attr('value'), 
  v5: $('#v5').attr('value'), 
  v6: $('#v6').attr('value'), }
})
  .done(function( msg ) {
   $("#r").html(msg);
  });
}
</script>
</body>
</html>