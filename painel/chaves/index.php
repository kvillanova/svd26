<?php
$force_microtime=1;
/* PAINEL DE ADMINISTRAÇÃO - CHAVES */
require_once "../header.php";

$peeps = array();
$xcode = 0;
$qvd = $vd->query( "SELECT * FROM ".$vd::KEYS_T_LAST." ORDER BY nome ASC;" );
if(preg_match("%doesn't exist%",$vd->error)) header("Location: ../auxiliares/setup");
while($oz = $qvd->fetch_assoc()) $peeps[] = $oz;
?>
<h1><i class="fa fa-key"></i> Chaves</h1>
<?php 
$dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false;
if($dev) include "../kvlrow.php";

$participantes = preg_grep("%^(0|1)$%uis",array_column($peeps,'tstatus'));
$participantes = array_intersect_key($peeps,$participantes);

$moderadores = preg_grep("%^(2|3)$%uis",array_column($peeps,'tstatus'));
$moderadores = array_intersect_key($peeps,$moderadores);

$eliminados = preg_grep("%^(4|5)$%uis",array_column($peeps,'tstatus'));
$eliminados = array_intersect_key($peeps,$eliminados);

if($participantes):
?>
<div class="section">
	<h3>Participantes</h3>
	<div>
	<button class="ativar" onClick="mudar('all',1);">Ativar</button>
	<button class="desativar" onClick="mudar('all',0);">Desativar</button>
	</div>
</div>
<div class="peeps part">
<div class="header">Nome</div>
<div class="header">Chave</div>
<div class="header">Tribo</div>
<div class="header">Status</div>
<div class="header"></div>
<?php foreach($participantes as $pp): 
extract($pp);
$tstatus = +$tstatus;
if($tstatus===1) $status="Ativ".$genero;
else $status="Desativad".$genero;
?>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$nome;?></p></div>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tkey;?></p></div>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tribo;?></p></div>
<div class="status <?=+$tstatus===1?"green":"red";?>" onClick="mudar('<?=$tkey;?>',<?=$tstatus===1?0:1;?>)"><p><?=$status;?></p></div>
<div class="eliminar" onClick="mudar('<?=$tkey;?>',4)"><p><i class="fa fa-times"></i></p></div>
<?php endforeach; ?>
</div>
<?php endif; 
if($moderadores): ?>
<div class="section">
	<h3>Moderadores</h3>
	<div>
	<button class="ativar" onClick="mudar('all',3);">Ativar</button>
	<button class="desativar" onClick="mudar('all',2);">Desativar</button>
	</div>
</div>
<div class="peeps mods">
<div class="header">Nome</div>
<div class="header">Chave</div>
<div class="header">Status</div>
<?php foreach($moderadores as $pp): 
extract($pp);
$tstatus = +$tstatus;
if($tstatus===3) $status="Ativ".$genero;
else $status="Desativad".$genero;
?>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$nome;?></p></div>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tkey;?></p></div>
<div class="status <?=$tstatus===3?"green":"red";?>" onClick="mudar('<?=$tkey;?>',<?=$tstatus===3?2:3;?>)"><p><?=$status;?></p></div>
<?php endforeach; ?>
</div>
<?php endif;
if($eliminados): ?>
<div class="section">
	<h3>Eliminados</h3>
</div>
<div class="peeps elim">
<div class="header">Nome</div>
<div class="header">Chave</div>
<div class="header">Tribo</div>
<div class="header">Status</div>
<?php foreach($eliminados as $pp): 
extract($pp);
$tstatus = +$tstatus;
if($tstatus===4) $status="Eliminad".$genero;
else $status="Desistente";
?>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$nome;?></p></div>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tkey;?></p></div>
<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tribo;?></p></div>
<div class="status <?=$tstatus===4?"out":"quitter";?>" onClick="mudar('<?=$tkey;?>',1)"><p><?=$status;?></p></div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<script>
	function mudar( k, n ) {
		$.ajax( {
			'url': "go.php",
			'type': 'POST',
			'data': {
				'key': k,
				'code': n
			}
		} ).done( function () {
			location.reload();
		} );
	}
</script>
<?php if($dev): ?>
<script src="../kvl.js?v3"></script>
<?php endif; ?>
</body>
</html>