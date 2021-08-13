<?php
const NO_TN = true;
include "../inc/db/header.php";
$vd = new VDSQL();
$qvd = $vd->query("SELECT * FROM 21_keys WHERE tribo='MOD';");
if($loc) $vd->local();
$lines = array();
while($oz = $qvd->fetch_assoc())
	$vd->query("INSERT INTO ".$vd::KEYS." VALUES ('{$oz['nome']}','{$oz['genero']}','{$oz['tkey']}','{$oz['tribo']}',{$oz['tstatus']});");
?>