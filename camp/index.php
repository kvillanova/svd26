<?php
$jsclipboard=1;
$force_microtime=1;
$fancybox=true;
require_once "header.php";
?>
<h1><i class="fa fa-map-signs"></i> Destinos</h1>
<?php
$now = new DateTime(NULL,$dtz);

$open = $vd->query("SELECT * FROM {$vd->services} WHERE id='destinos' AND open=1;");
if(!$open->num_rows) {
	echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;
}

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='destinos_abre';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = NULL;

if(empty($open));
else {
	
$open = new DateTime($open,$dtz);
if($now<$open) {
		echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;
	} 
}

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='destinos_fecha';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = NULL;

if(empty($open));
else {
	$open = new DateTime($open,$dtz);
	if($now>$open) {
	echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;	
	}
}
?>
<div id="destinos">
<div class="header"><p>#</p></div>
<div class="header"><p>Jogador</p></div>
<div class="header"><p>Destinos</p></div>
<?php
$qvd = $vd->query("SELECT * FROM {$vd->idolo}_vall WHERE tribo='{$tribo}' AND (tstart>'2020-12-03 23:50:00' OR tstart IS NULL) ORDER BY -tend DESC, nome ASC;");
$peeps = array();
while($oz = $qvd->fetch_assoc()) {
	extract($oz);
	$peeps[$nome]['starts'][] = new DateTime($tstart,$dtz);
	$peeps[$nome]['ends'][] = empty($tend) ? new DateTime("0000-00-00 00:00:00") : new DateTime($tend,$dtz);
	$peeps[$nome]['coords'][] = $coords;
}
	
$I=0;
foreach($peeps as $nome => $pp):
ob_start();
?>
<div style="grid-row: span //SPANROW//"><p><?=++$I;?></p></div>
<div style="grid-row: span //SPANROW//"><img src="../inc/mini/<?=$nome;?>.jpg" alt="<?=$nome;?>" title="<?=$nome;?>"></div>
<?php $C=0; foreach($pp['ends'] as $i => $ss): if($ss>=$now): ++$C; ?>
<div class="coords"><p><strong><?=$pp['coords'][$i];?>: </strong><?=$pp['starts'][$i]->format("d/m/Y - H:i:s");?></p></div>
<?php endif; endforeach; 
	$ob = ob_get_clean();
	echo preg_replace("%//SPANROW//%uis",$C===0?1:$C,$ob);
	if($C===0) {
		echo "<div class=\"coords\"><p>NO ACAMPAMENTO</p></div>";
	}
endforeach; ?>
</div>
<div id="r"></div>
<script>
$(".codigo").click(function(e){
	e.preventDefault();
});
new ClipboardJS(".codigo");
$("form").on("submit", function(e){
	e.preventDefault();
	$.ajax({
		url:'resp.php',
		type:'POST',
		data:{sender:'destinos',d:$(this).serializeArray()}
	}).done(function(m){
		$("#r").html(m);
	});
});
</script>
</body>
</html>