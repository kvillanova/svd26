<?php
const NO_TN = true;
include "header.php";
$vd->vd3();
$qvd = $vd->query("SELECT * FROM 21_keys WHERE tstatus=1 ORDER BY nome ASC");

while($oz = $qvd->fetch_assoc()):

$nome = $oz['nome'];
$gen = $oz['genero'];
$key = $oz['tkey'];

echo ".<br/>.<br/>Olá, {$nome}, bem-vind{$gen} ao VD Amazonas.<br/>Sua chave é {$key}.<br/>"; 

endwhile;

?>