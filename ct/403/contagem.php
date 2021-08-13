<?php
$qvd = $vd->query("SELECT voto, count(voto) qtde FROM ".$vd::CT." t INNER JOIN ".$vd::KEYS." k ON k.tkey=t.tkey WHERE voto IS NOT NULL AND t.num={$num} AND t.tribo='{$tribo}' GROUP BY voto ORDER BY qtde DESC, voto ASC;");
echo $vd->error;
if($qvd->num_rows<1) {
	echo "<p>Ainda não há votos.</p>";
	die;
}
?>
<div class="score contagem">
<div class="header"><p>Jogador</p></div>
<div class="header"><p>Votos</p></div>
<?php while($oz = $qvd->fetch_assoc()): 
$voto = $oz['voto'];
if(!empty($voto)) $voto = ucwords($voto);
else $voto = "unknown";
$qtde = (int) $oz['qtde'];
?>
<div><img src="../inc/mini/<?=$voto;?>.jpg"></div>
<div><p><?=$qtde;?></p></div>
<?php endwhile; ?>
</div>