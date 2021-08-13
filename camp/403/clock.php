<?php
$now = new DateTime(NULL,$dtz);
$qvd = $vd->query("SELECT 
IFNULL((SELECT COUNT(*) FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND ciclo=(SELECT ciclo FROM {$vd->idolo}_c WHERE tkey='{$tkey}' ORDER BY tpost DESC LIMIT 1)),0) cliques,
IFNULL((SELECT tpost FROM {$vd->idolo}_c WHERE tkey='{$tkey}' ORDER BY tpost DESC LIMIT 1),0) tempo;
");
$oz = $qvd->fetch_assoc();
extract($oz);

if(!$tempo) $tempo = clone $now;
else $tempo = new DateTime($tempo,$dtz);
$diff = $now<$tempo ? ($now->diff($tempo))->format("%H:%I:%S") : "08:00:00";

if($diff==="08:00:00") $cliques=0;
$cliques = 10-$cliques;

echo "Cliques restantes: " . $cliques . " - " .  $diff;
?>