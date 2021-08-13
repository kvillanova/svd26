<?php
$filename = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : false;

if($filename) {
	if($_FILES['file']['error']>0) $c = "ERRO AO CARREGAR ARQUIVO!";
	else {
		echo $filename;
	//$handle = fopen($filename, "r");
  	$c = file_get_contents($filename);
		echo $c;
	}
}
else {
	$c = isset($_POST['texto']) ? $_POST['texto'] : NULL;
}

if($c):
  
include __DIR__ . "/sanitize2.php";

endif;
?>