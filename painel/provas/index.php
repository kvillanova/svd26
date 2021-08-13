<?php 
$force_microtime=true;
/* PAINEL DE ADMINISTRAÇÃO - PROVAS */
require_once "../header.php"; 

$qvd = $vd->query("SELECT * FROM ".$vd::PROVAS." ORDER BY num DESC, rotulo DESC;");
?>
<h1><i class="fa fa-gamepad"></i> Provas</h1>
<?php $dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false; ?>
<?php if($dev) include "../kvlrow.php"; ?>
<div class="provas">
<div class="header"><p>#</p></div>
<div class="header"><p>Nome</p></div>
<div class="header"><p>ID</p></div>
<div class="header"><p>Tipo</p></div>
<div class="header"><p>Acesso</p></div>
<div class="header"></div>
<?php 
while($oz = $qvd->fetch_assoc()) { 
extract($oz);
switch($pstatus):
case 0: $statusc = "mod"; $statust = "Apenas moderadores"; break;
case 1: $statusc = "jog"; $statust = "Liberado para Jogadores"; break;
case 2: $statusc = "elim"; $statust = "Ativos e Eliminados"; break;
case 3: $statusc = "pub"; $statust = "Liberado para Plateia"; break;
endswitch;
?>
<div class="nimp"><p><?=$num;?></p></div>
<div class="nome"><p><?=$rotulo . " - " . $nome; ?></p></div>
<div class="id"><a href="../../<?=$id;?>" target="_blank"><p>/<?=$id;?></p></a></div>
<div class="tipo nimp"><p><?=mb_strtoupper($tipo);?></p></div>
<div class="pstatus <?=$statusc;?>"><p><?=$statust;?></p></div>
<div class="botoes">
<button type="button" title="Apenas moderadores" class="pstatus mod" onClick="mudar('<?=$id;?>',0)"><i class="fa fa-ban fa-2x"></i></button>
<button type="button" title="Apenas moderadores" class="pstatus jog" onClick="mudar('<?=$id;?>',1)"><i class="fa fa-child fa-2x"></i></button>
<button type="button" title="Apenas moderadores" class="pstatus elim" onClick="mudar('<?=$id;?>',2)"><i class="fa fa-user-slash fa-2x"></i></button>
<button type="button" title="Apenas moderadores" class="pstatus pub" onClick="mudar('<?=$id;?>',3)"><i class="fa fa-globe fa-2x"></i></button>
</div>
<?php } ?>
</div>
<?php
$provas = $vd->query("SELECT id FROM ".$vd::PROVAS." ORDER BY num DESC, id ASC;");
$moderas = $vd->query("SELECT nome,tkey FROM ".$vd::KEYS_T_LAST." WHERE tstatus IN(2,3) ORDER BY nome ASC;");
?>
<h3>Zerar placar</h3>
<form id="zerar">
<select name="prova">
<?php 
if($provas->num_rows<1) echo "<option value=\"na\" selected>—</option>";
else while($p = $provas->fetch_assoc()) {
	$id = $p['id'];
	echo "<option value=\"".$vd::PREFIX."{$id}\">/{$id}</option>";	
}
?>
</select>
<select name="mod">
<?php
while($m = $moderas->fetch_assoc()) {
	$n = $m['nome']; $t = $m['tkey'];
	echo "<option value=\"{$t}\">{$n}</option>";	
}
?>
</select>
<button type="submit">Zerar</button>
</form>
<div id="r"></div>
<script type="text/javascript">
function mudar(i, s) {
	$.ajax(
		{'url': "go.php", 'type':'POST', 'data':{'id':i, 'status':s} }
).done( function(e) { location.reload(); });
}

$('#zerar').submit(function(e) {
    e.preventDefault();
	$.ajax({
		url:"zerar.php",
		type:'POST',
		data: $(this).serializeArray()
}).done(function(msg) { console.log(msg); });
});
</script>
<?php if($dev): ?>
<script src="../kvl.js"></script>
<?php endif; ?>
</body>
</html>