<?php
if(!isset($_GET['no'])) die;
$no = (float) $_GET['no'];

$tribo = isset($_GET['tribo']) ? mb_strtoupper($_GET['tribo']) : 'ALL';

const TN_NAME = "\$ct_e";
const FOOTER_NAME = "unlimited";
const NO_CHALLENGE = true;
const NO_FINISH = true;
include "../inc/db/header.php";

$mod = $vd->getTribe($tn,$cook);
if($mod!=='MOD') die;
?>
<pre>
<?php
$qvd = $vd->query("SELECT voto, justificativa FROM ".$vd::CT." t INNER JOIN ".$vd::KEYS." k ON k.tkey=t.tkey WHERE t.num={$no} AND t.tribo='{$tribo}' ORDER BY voto ASC, -t.tpost DESC;");
while($oz = $qvd->fetch_assoc()):

$voto = mb_strtoupper($oz['voto']);
$just = stripslashes(html_entity_decode($oz['justificativa']));?>
.
.
.
º VOTO:
.
<?=$just . PHP_EOL;?>
.
<?=$voto . PHP_EOL . "<br><br>";?>
<?php endwhile; ?>
</pre>