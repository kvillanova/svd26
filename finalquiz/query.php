<?php
$info = array(15,"I#15","Quiz");
$vd->createTN($tn,$info,true);
$vd->query("CREATE TABLE {$tn}_a LIKE {$tn}_x");
$vd->query("ALTER TABLE {$tn}_a DROP PRIMARY KEY;");
?>