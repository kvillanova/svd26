<?php
const ONLY_TN = true;
include "../inc/db/header.php";
extract($_GET,EXTR_SKIP);
$where = isset($mods) ? "WHERE tribo='MOD'" : "WHERE tribo<>'MOD' AND tstatus IN(0,1)";
$mini = isset($mods) ? "../inc/mods" : "../inc/mini";
$I=0;

$qvd = $vd->query("SELECT
	(SELECT tpost FROM {$tn}_x WHERE tkey=k.tkey AND atual=1 ORDER BY tpost ASC LIMIT 1) tstart,
	(SELECT tpost FROM {$tn}_x WHERE tkey=k.tkey AND atual=6 ORDER BY tpost ASC LIMIT 1) tend,
	(SELECT resp FROM {$tn}_x WHERE tkey=k.tkey AND atual>0 ORDER BY tpost DESC LIMIT 1) lresp,
	(SELECT tpost FROM {$tn}_x WHERE tkey=k.tkey AND atual>1 ORDER BY atual DESC, tpost ASC LIMIT 1) ltime,
	(SELECT atual FROM {$tn}_x WHERE tkey=k.tkey ORDER BY tpost DESC LIMIT 1) atual,
	tkey, nome, tribo FROM ".$vd::KEYS_T_LAST." k {$where} HAVING atual>=1
	ORDER BY atual DESC, ltime ASC, -tend DESC, atual DESC, tstart ASC;
	");
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
	<h1>Maratona<wbr><em>/Score</em></h1>
	<?php
	echo $vd->error;
	if($qvd->num_rows>0):
	?>
	<div class="score<?php if(isset($raflemcurioso)) echo ' god'; ?>">
		<div class="header"><p>#</p></div>
		<div class="header"><p>Nome</p></div>
		<div class="header"><p>Tribo</p></div>
		<div class="header"><p>Status</p></div>
		<div class="header"><p>Tempos</p></div>
		<?php if(isset($raflemcurioso)): ?>
		<div class="header"><p>UR</p></div>
		<?php endif; $I=0;
		while($oz = $qvd->fetch_assoc()):
		extract($oz,EXTR_OVERWRITE);
		$tstart = !empty($tstart) ? new DateTime($tstart,$dtz) : "—";
		$tend = !empty($tend) ? new DateTime($tend,$dtz) : "—";		
		$ltime = !empty($ltime) ? new DateTime($ltime,$dtz) : "—";		
		$duracao = ($tend instanceof DateTime && $tstart instanceof DateTime) ? $tstart->diff($tend) : "—";
		
		$tstart = $tstart instanceof DateTime ? $tstart->format("d/m/Y - H:i:s.u") : $tstart;
		$tend = $tend instanceof DateTime ? $tend->format("d/m/Y - H:i:s.u") : $tend;
		$ltime = $ltime instanceof DateTime ? $ltime->format("d/m/Y - H:i:s.u") : $ltime;
		$duracao = $duracao instanceof DateInterval ? $duracao->format("%H:%I:%S") : $duracao;
		if($atual == 0) $status = "AGUARDANDO";
		elseif($atual == 6) $status = "FIM";
		else $status = +$atual;
		?>
		<div class="pos"><p><?=++$I;?></p></div>
		<div><img src="<?=$mini;?>/<?=mb_strtolower($nome);?>.jpg" alt="<?=$nome;?>" title="<?=$nome;?>"></div>
		<div class="tribo <?=mb_strtolower($tribo);?>"><p><?=$tribo;?></p></div>
		<div class="placar"><p><?=$status;?></p></div>
		<div class="tempos">
		<div class="label"><p>Início</p></div>
		<div class="tempo"><p><?=$tstart;?></p></div>
		<div class="label"><p>Fim</p></div>
		<div class="tempo"><p><?=$tend;?></p></div>
		<div class="label"><p>Último Tempo</p></div>
		<div class="tempo"><p><?=$ltime;?></p></div>
		<div class="label"><p>Duração</p></div>
		<div class="tempo"><p><?=$duracao;?></p></div>
		</div>
		<?php if(isset($raflemcurioso)): ?>
		<div class="resp"><p><?=$lresp;?></p></div>
		<?php endif; ?>
		<?php endwhile; ?>
	</div>
	<?php else: ?>
	<p>Não há nada pra mostrar.</p>
	<?php endif; ?>
</body>
</html>