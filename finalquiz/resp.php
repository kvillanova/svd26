<?php
const
TEND_NO=16;
include "../inc/db/header.php";

$at = +$_POST['at'];
if($atual!==$at) die;

if(!isset($_POST['resp'])) die;

$resp = is_numeric($_POST['resp']) ? +$_POST['resp'] : NULL;
if($resp===NULL) die;

$gaba = json_decode(file_get_contents("403/perguntas.json"),true);
$gaba = $gaba[$atual-1]["correta"];

if($resp===$gaba) $score=1;
else $score=0;

++$at;
$resp = $score ? chr($resp+65) : chr($resp+65) . " (" . chr($gaba+65) . ")";

$vd->query("INSERT INTO {$tn}_a VALUES ('{$tkey}',{$at},NOW(6),{$score},'{$resp}');");
$vd->query("INSERT INTO {$tn}_x VALUES ('{$tkey}',{$at},NOW(6),{$score},'{$resp}');");
blankrerro();
?>