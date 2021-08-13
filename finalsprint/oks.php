<?php
const ONLY_TN = true;
include "../inc/db/header.php";
$mods = isset($_GET['mods']) ? "WHERE tribo='MOD'" : "WHERE tribo<>'MOD'";
$mini = isset($_GET['mods']) ? "../inc/mods" : "../inc/mini";
$I=0;
?>
<!doctype html>
<html>
<head>
	<?php
	$force_microtime=1;
	include "../inc/styles/og.php";
	include "../inc/styles/rel.php";
	?>
</head>

<body>
	<h1>OKs</h1>
	<?php
	$qvd = $vd->query("SELECT *
	FROM {$tn}_x t INNER JOIN {$vd->keys_t_last} k ON k.tkey=t.tkey {$mods} AND atual=0 ORDER BY tribo ASC, -tpost DESC;");
	echo $vd->error;
	if($qvd->num_rows>0):
	?>
	<div id="oks">
		<div class="header"><p>#</p></div>
		<div class="header"><p>Nome</p></div>
		<div class="header"><p>Tribo</p></div>
		<div class="header"><p>Entrada</p></div>
		<?php while($oz = $qvd->fetch_assoc()):
		extract($oz,EXTR_OVERWRITE);
		$inicio = (new DateTime($tpost,$dtz))->format("d/m/Y - H:i:s.u");
		?>
		<div><p><?=++$I;?></p></div>
		<div><img src="<?=$mini;?>/<?=mb_strtolower($nome);?>.jpg" alt="<?=$nome;?>" title="<?=$nome;?>"></div>
		<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tribo;?></p></div>
		<div><p><?=$inicio;?></p></div>
		<?php endwhile; ?>
		</div>
	<?php else: ?>
	<p>Não há nada pra mostrar.</p>
	<?php endif; ?>
</body>
</html>