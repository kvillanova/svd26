<?php
include "../../../inc/db/class.php";
include "../../../inc/db/setup.php";
if($_SERVER['HTTP_HOST']==='localhost') $vd = new VDSQLSetup(true);
else $vd = new VDSQLSetup();

header("Location: ../../");
?>