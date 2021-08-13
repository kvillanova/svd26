<?php
$qvd = $vd->query("SELECT * FROM ".$vd::CT." t INNER JOIN ".$vd::KEYS." k ON k.tkey=t.tkey WHERE t.num={$num} AND t.tribo='{$tribo}' ORDER BY -t.tpost DESC;");
if($qvd->num_rows<1) {
	echo "<p>Ainda não temos votos.</p>"; die;
}
?>
<div class="score votos">
<div class="header"><p>Jogador</p></div>
<div class="header"><p>Votou em</p></div>
<div class="header"><p>Horário</p></div>
<div class="header"><p>Justificativa</p></div>
	<?php while($oz = $qvd->fetch_assoc()): 
	$nome = $oz['nome'];
	$voto = !empty($oz['voto']) ? ucwords(mb_strtolower($oz['voto'])) : "unknown";
	$horario = !empty($oz['tpost']) ? (new DateTime($oz['tpost']))->format("d/m/Y - H:i:s") : "—";
	$just = !empty($oz['justificativa']) ? $oz['justificativa'] : "—";
	?>
	<div><img src="../inc/mini/<?=$nome;?>.jpg" title="<?=$nome;?>"></div>
	<div><img src="../inc/mini/<?=$voto;?>.jpg"></div>
	<div><p><?=$horario;?></p></div>
	<div><p><?=$just;?></p></div>
	<?php endwhile; ?>
</div>