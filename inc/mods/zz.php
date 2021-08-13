<?php
if(!isset($_GET['n'])) die;
extract($_GET);

$n = mb_strtolower($n);

if(file_exists("{$n}.jpg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("{$n}.jpg");
}

if(file_exists("{$n}.jpeg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("{$n}.jpeg");
}

if(file_exists("{$n}.png")) {
	header("Content-Type: image/png");
	echo file_get_contents("{$n}.png");
}

if(file_exists("{$n}.gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents("{$n}.gif");
}

else {
	header("Content-Type: image/jpg");
	echo file_get_contents("../mini/unknown.jpg");
}
?>