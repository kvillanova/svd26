<?php
const
ATUAL_NO=4,
TEND_NO=6,
TN_SUBDIR=true;
include "../../inc/db/header.php";
?>
<!doctype html>
<html>
<head>
<?php
$force_microtime=1;
include "../../inc/styles/og.php";
include "../../inc/styles/rel.php";
?>
</head>
<body>
	<h1>Flow</h1>
	<?php
	$flow = file_get_contents("403/blank.txt");
	$flow = preg_replace("%\s+%uis", "", $flow);
	$flow = str_split($flow);
	$size = count($flow)**(1/2);
	?>
	<div class="grid x<?=$size;?>">
	<?php foreach($flow as $ff): 
		if($ff==='.'):
	?>
		<div class="paint" data-color=""></div>	
		<?php else: ?>
		<div class="painted" data-color="<?=$ff;?>"><div class="<?=mb_strtolower($ff);?>"><?=$ff;?></div></div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
	<button onMouseDown="sendC();">Enviar</button>
	<div id="r"></div>
	<script>
	var atColor = 'A';
	$(".painted").on("mousedown",function() {
		atColor = $(this).data("color");
	});
	$(".paint").on("mousedown",function() {
		$(this).removeClass().addClass("paint "+atColor.toLowerCase());
		$(this).data("color",atColor);
		$(this).html(atColor);
	});
		
	function sendC() {
		$x = [];
		$(".paint, .painted").each(function(e) {
			$x.push( $(this).data("color") ); 
		});
		$.ajax({
		'url':'resp.php',
		'type':'POST',
		'data':{'c':$x,'f':<?=$atual;?>}	
		}).done(function(e) {
			$("#r").html(e);
		});
	}
	</script>
</body>
</html>