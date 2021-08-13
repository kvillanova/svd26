<?php
/* PAINEL DE ADMINISTRAÇÃO - MAIS */
$force_microtime=1;
require_once "../header.php";
?>
<h1><i class="fa fa-concierge-bell"></i> Serviços</h1>
<?php 
$dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false;
if($dev) include "../kvlrow.php";
?>
<div id="services">
<div class="header">Nome</div>
<div class="header">Acesso</div>
<div class="header"></div>
<?php 
$qvd = $vd->query("SELECT * FROM ".$vd::SERVICES." ORDER BY id ASC;");
while($oz = $qvd->fetch_assoc()) { 

switch($oz['open']):
case 0: $statusc = "mod"; $statust = "Serviço fechado"; break;
case 1: $statusc = "jog"; $statust = "Serviço aberto"; break;
endswitch;
?>
<div><p><a href="../../<?=$oz['id'];?>" target="_blank"><?=$oz['nome']; ?><br><small>(/<?=$oz['id'];?>)</small></a></p></div>
<div class="pstatus <?=$statusc;?>"><p><?=$statust;?></p></div>
<div class="icos">
	<button class="pstatus ico mod" title="Fechar serviço" onClick="javascript:mudar('<?=$oz['id'];?>',0);"><i class="fa fa-lock fa-2x"></i></button>
	<button class="pstatus ico jog" title="Abrir serviço" onClick="javascript:mudar('<?=$oz['id'];?>',1);"><i class="fa fa-unlock fa-2x"></i></button>
</div>
<?php } ?>
</div>
<h2>Variáveis</h2>
<div id="vars">
<div class="header">On</div>
<div class="header">Variável</div>
<div class="header">Valor</div>
<div class="header">Expira em</div>
<div class="header">Atualizada em</div>
<?php
	$qvd = $vd->query("SELECT * FROM {$vd->vars_all} ORDER BY ativa DESC, varname ASC;");
	while($oz = $qvd->fetch_assoc()):
	extract($oz);
?>
<div><?php if($ativa) echo '<i class="fa fa-check-square"></i>'; else echo '<i class="fa fa-square"></i>'; ?></div>
<div><?=$varname; ?></div>
<div><?=!empty($valor)?$valor:"&mdash;"; ?></div>
<div><?=!empty($expira)?(new DateTime($expira,$dtz))->format("d/m/Y - H:i:s"):"&mdash;"; ?></div>
<div><?=!empty($atualizada)?(new DateTime($atualizada,$dtz))->format("d/m/Y - H:i:s"):"&mdash;"; ?></div>
<?php endwhile; ?>
</div>
<script>
function mudar(i, s) {
	$.ajax(
		{'url': "go.php", 'type':'POST', 'data':{'id':i, 'status':s} }
).done( function(m) { location.reload(); 
	});
}
</script>
<?php if($dev): ?>
<script src="../kvl.js"></script>
<?php endif; ?>
</body>
</html>