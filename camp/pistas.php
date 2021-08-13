<?php
$jquery_ui=1;
require_once "header.php";
?>
<h1><i class="fa fa-map-marked-alt"></i> Pistas</h1>
<?php
$json = json_decode(file_get_contents("403/idolos.json"),true);
$coords = $json[$tribo]["pistas"];
$textos = $json[$tribo]["textos"];
$a1 = implode("','",$coords[0]);
$a2 = implode("','",$coords[1]);
$a3 = implode("','",$coords[2]);

$qvd = $vd->query("SELECT 
EXISTS(SELECT * FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND tribo='{$tribo}' AND coords IN('{$a1}')) pista1,
EXISTS(SELECT * FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND tribo='{$tribo}' AND coords IN('{$a2}')) pista2,
EXISTS(SELECT * FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND tribo='{$tribo}' AND coords IN('{$a3}')) pista3
");

$oz = $qvd->fetch_assoc();
$pistas[] = (bool) +$oz['pista1'];
$pistas[] = (bool) +$oz['pista2'];
$pistas[] = (bool) +$oz['pista3'];

#var_dump($pistas);

if(!$pistas[0] && !$pistas[1] && !$pistas[2]):
?>
<div class="w3-panel w3-red"><p>Só lamento, mas você não possui nenhuma pista desse ídolo.</p></div>
</body>
</htmL>
<?php endif; foreach($pistas as $i => $pp): if($pp): ?>
<h3>Pista <?=$i+1;?></h3>
<p><?=$textos[$i];?></p>
<?php endif; endforeach; ?>
<div id="r"></div>
<script>
</script>
</body>
</htmL>