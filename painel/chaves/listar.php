<?php
define("NO_TN",true);
require_once __DIR__ . "/../../inc/db/header.php";

$qvd = $vd->query("SELECT * FROM {$vd->keys_t_last} WHERE tstatus NOT IN (2,3) ORDER BY nome ASC;");

while($oz = $qvd->fetch_assoc()):
	echo "\n<br>.\n<br>.\n<br>.\n<br>";
	echo "Bem-vind{$oz['genero']}, {$oz['nome']}. Sua chave é {$oz['tkey']}.";
endwhile;

?>