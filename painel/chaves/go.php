<?php
include "kvl.php";

if($loc) $vd = new KvLKeys(true);
else $vd = new KvLKeys();

$tkey = $_POST['key'];
$code = (int) $_POST['code'];

if($tkey==="all") {
	switch($code):
	case 0: $vd->desligarTodos(); break;
	case 1: $vd->ligarTodos(); break;
	case 2: $vd->desligarTodos(true); break;
	case 3: $vd->ligarTodos(true); break;
	endswitch;
}

else {
	switch($code):
	case 0: $vd->desligar($tkey); break;
	case 1: $vd->ligar($tkey); break;
	case 2: $vd->desligar($tkey,true); break;
	case 3: $vd->ligar($tkey,true); break;
	case 4: $vd->eliminar($tkey); break;
	endswitch;
}
?>