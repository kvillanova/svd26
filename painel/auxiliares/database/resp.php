<?php
const NO_TN = true;
include "../../../inc/db/header.php";

$c = $_POST['texto'];

$c = preg_split("%\n%uis",$c);
$c = array_map(function($x){
	return preg_split("%\t%uis",$x);
},$c);

$tabela = $_POST['tabela'];
if(!preg_match("%^vd_%uis",$tabela)) die("Não é uma tabela VD");

#echo $vd->character_set_name(); die;

$columns = NULL;
$update = NULL;
$values = array();

foreach($c as $i => $cc):
$line = NULL;
if($i===0) {
	foreach($cc as $ccc) {
		$ccc = addslashes(trim(removeAccents($ccc)));
		$columns .= "$ccc,";
		$update .= "{$ccc}=VALUES({$ccc}),";
	}
	$update = trim($update,",");
	$columns = trim($columns,",");
}
else {
	$values[$i] = "(";
	foreach($cc as $ccc) {
		$ccc = addslashes(trim($ccc));
		if(is_numeric($ccc)) $ccc = floatval($ccc);
		elseif(empty($ccc)) $ccc = "NULL";
		else $ccc = "'{$ccc}'";
		$values[$i] .= "{$ccc},";
	}
	$values[$i] = trim($values[$i],",") . ")";
	#echo $values[$i] . "\n";
}
endforeach;

$valores = implode(",",$values);

//echo "$columns\n$update";

$vd->query("INSERT INTO {$tabela}($columns) VALUES {$valores} ON DUPLICATE KEY UPDATE {$update};")
;
if($vd->error) echo $vd->error;
else echo "DADOS INSERIDOS COM SUCESSO, MESTRE.";
?>