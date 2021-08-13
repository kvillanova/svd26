<?php
const
TEND_NO=16;
include "../inc/db/header.php";

$score = $vd->query("SELECT SUM(score) soma FROM {$tn}_x WHERE tkey='{$tkey}';");
$score = +$score->fetch_assoc()['soma'];
if($score<15) {
	$vd->query("DELETE FROM {$tn}_x WHERE tkey='{$tkey}' AND score IS NOT NULL;");
	location();
}
?>
<!doctype html>
<html>
<head>
<?php
include "../inc/styles/og.php";
include "../inc/styles/rel.php";
?>
</head>
<body>
<h1>Quiz 10 Anos</h1>
<div class="w3-panel w3-green">
	<p>Prova concluída com sucesso.</p>
</div>
</body>
</html>