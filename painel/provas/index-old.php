<?php 
/* PAINEL DE ADMINISTRAÇÃO - PROVAS */
require_once "../header.php"; 

$qvd = $vd->query("SELECT * FROM ".$vd::PROVAS." ORDER BY num DESC, rotulo DESC;");
?>
<h1><i class="fa fa-gamepad"></i> Provas</h1>
<?php $dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false; ?>
<?php if($dev) include "../kvlrow.php"; ?>
<table class="provas">
<tr>
<th>#</th>
<th>Código/Nome</th>
<th>Identificador</th>
<th>Acesso</th>
</tr>
<?php 
while($oz = $qvd->fetch_assoc()) { 

switch($oz['pstatus']):
case 0: $statusc = "mod"; $statust = "Apenas moderadores"; break;
case 1: $statusc = "jog"; $statust = "Liberado para Jogadores"; break;
case 2: $statusc = "elim"; $statust = "Ativos e Eliminados"; break;
case 3: $statusc = "pub"; $statust = "Liberado para Plateia"; break;
endswitch;
?>
<tr>
<td><?=$oz['num']; ?></td>
<td><?=$oz['rotulo'] . " - " . $oz['nome']; ?></td>
<td><a href="../../<?=$oz['id'];?>" target="_blank">/<?=$oz['id'];?></a></td>
<td><?=mb_strtoupper($oz['tipo']);?></td>
<td class="pstatus <?=$statusc;?>"><?=$statust;?></td>
<td class="pstatus ico mod" title="Apenas moderadores" onClick="javascript:mudar('<?=$oz['id'];?>',0);"><i class="fa fa-ban fa-2x"></i></td>
<td class="pstatus ico jog" title="Liberado para Jogadores" onClick="javascript:mudar('<?=$oz['id'];?>',1);"><i class="fa fa-child fa-2x"></i></td>
<td class="pstatus ico elim" title="Ativos e Eliminados" onClick="javascript:mudar('<?=$oz['id'];?>',2);"><i class="fa fa-user-slash fa-2x"></i></td>
<td class="pstatus ico pub" title="Liberado para Plateia" onClick="javascript:mudar('<?=$oz['id'];?>',3);"><i class="fa fa-globe fa-2x"></i></td>
</tr>
<?php } ?>
</table>
<?php
$provas = $vd->query("SELECT id FROM ".$vd::PROVAS." ORDER BY num DESC, id ASC;");
$moderas = $vd->query("SELECT nome,tkey FROM ".$vd::KEYS." WHERE tstatus IN(2,3) ORDER BY nome ASC;");
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
<input type="submit" value="Zerar" />
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