<?php
$jquery_ui=1;
require_once "header.php";
?>
<h1><i class="fa fa-binoculars"></i> Busca do Ídolo</h1>
<?php
$open = $vd->query("SELECT * FROM {$vd->services} WHERE id='idolos' AND open=1;");
if(!$open->num_rows) {
	echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;
}

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='idolos_abre';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = NULL;

if(empty($open));
else {
	$now = new DateTime(NULL,$dtz);
	$open = new DateTime($open,$dtz);
	if($now<$open) {
		echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;
	} 
}

$open = $vd->query("SELECT valor FROM {$vd->vars} WHERE varname='idolos_fecha';");
if($open->num_rows) $open = $open->fetch_assoc()['valor'];
else $open = NULL;

if(empty($open));
else {
	$now = new DateTime(NULL,$dtz);
	$open = new DateTime($open,$dtz);
	if($now>$open) {
	echo <<<LAT
<div class="w3-panel w3-orange"><p>Não é possível utilizar esse serviço no momento.</p></div></body></html>	
LAT;
die;	
	}
}

$grid = file_get_contents("403/grid-{$tribo}.txt");
$grid = preg_split("%\r?\n%uis",$grid,-1,PREG_SPLIT_NO_EMPTY);
$grid = array_map(function($x){
		return preg_split("%\t%uis",$x,-1,PREG_SPLIT_NO_EMPTY);
},$grid);
?>
<h3 class="clock"></h3>
<div id="r"></div>
<div id="grid-wrapper">
<div id="grid-container" class="camp-<?=count($grid);?>">
<div id="grid-idol" class="camp-<?=count($grid);?>">
<div></div>
<?php
	
	$l1 = range("A","Z");
	$l2 = array_map(function($x){
		return "A" . $x;
	},$l1);
	$cols = array_merge($l1,$l2);
	
	foreach($grid[0] as $h => $o) {
		$o = $cols[$h];
		echo "<div class=\"header\"><p>{$o}</p></div>" . PHP_EOL;
	}
	
	foreach($grid as $l => $linha) {
		$ln = $l+1;
		echo "<div class=\"header\"><p>{$ln}</p></div>" . PHP_EOL;
	
	foreach($linha as $c => $coluna) {
?>
<div class="area c<?=$coluna;?>" data-clique="<?=$cols[$c].$ln;?>"></div>
<?php
		}
	}
	
?>
</div>
</div>
</div>
<div id="area-alert" class="w3-modal">
	<div class="w3-modal-content">
	<p class="texto">Você quer mesmo acessar a área <strong>A1</strong>?</p>
	<button class="y">Sim</button>
	<button class="n">Não</button>
	</div>
</div>
<script>
var area = "A1";
var clock = setInterval(function(){rodarclock();},1000);
rodarclock();
$(".area").on("click",function(e){
	area = $(this).data("clique");
	$("#area-alert").find(".texto>strong").html(area);
	$("#area-alert").show();
	e.stopPropagation();
});
	
$("#area-alert .w3-modal-content").on("click", function(e){
	e.stopPropagation();
});
	
$(document).on("click",function(e){
	$("#area-alert").hide();
});
	
$("button.n").on("click",function(e){
	$("#area-alert").hide();
});
	
$("button.y").on("click",function(e){
	procurarArea(area);
	$("#area-alert").hide();
	location.href = './idolo#';
});
	
function procurarArea(a) {
	$.ajax({
		url:'resp.php',
		type:'POST',
		data:{sender:'idolos',area:a}
	}).done(function(m){
		$("#r").html(m);
	});
}
	
function rodarclock() {
	$.ajax({
		url:'resp.php',
		type:'POST',
		data:{sender:'clock'}
	}).done(function(m){
		$(".clock").html(m);
	});
}
	
//$("form").on("submit", function(e){
//	e.preventDefault();
//	$.ajax({
//		url:'resp.php',
//		type:'POST',
//		data:$(this).serializeArray()
//	}).done(function(m){
//		$("#r").html(m);
//	});
//});
</script>
</body>
</html>