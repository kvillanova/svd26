<?php
if(!isset($_GET['n'])) die;
extract($_GET);

$n = mb_strtolower($n);

if(file_exists("{$n}.jpg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("{$n}.jpg");
}

elseif(file_exists("{$n}.jpeg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("{$n}.jpeg");
}

elseif(file_exists("{$n}.png")) {
	header("Content-Type: image/png");
	echo file_get_contents("{$n}.png");
}

elseif(file_exists("{$n}.gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents("{$n}.gif");
}

elseif(file_exists("../mods/{$n}.jpg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("../mods/{$n}.jpg");
}

elseif(file_exists("../mods/{$n}.jpeg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("../mods/{$n}.jpeg");
}

elseif(file_exists("../mods/{$n}.png")) {
	header("Content-Type: image/png");
	echo file_get_contents("../mods/{$n}.png");
}

elseif(file_exists("../mods/{$n}.gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents("../mods/{$n}.gif");
}

elseif(file_exists("../lovedones/{$n}.jpg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("../lovedones/{$n}.jpg");
}

elseif(file_exists("../lovedones/{$n}.jpeg")) {
	header("Content-Type: image/jpg");
	echo file_get_contents("../lovedones/{$n}.jpeg");
}

elseif(file_exists("../lovedones/{$n}.png")) {
	header("Content-Type: image/png");
	echo file_get_contents("../lovedones/{$n}.png");
}

elseif(file_exists("../lovedones/{$n}.gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents("../lovedones/{$n}.gif");
}

else {
	$n = mb_strtoupper($n);
	if(strlen($n)<=3) $size=150;
	elseif(strlen($n)<=5) $size=100;
	elseif(strlen($n)<=7) $size=72;
	elseif(strlen($n)<=10) $size=64;
	elseif(strlen($n)<=12) $size=48;
	elseif(strlen($n)<=17) $size=36;
	elseif(strlen($n)<=20) $size=30;
	#echo strlen($n);
	
	$im = imagecreatefromjpeg("unknown.jpg");
	$font = __DIR__ . "/../styles/fontes/MyriadPro-Regular.otf";

	$white = imagecolorallocate($im,255,255,255);
	$black = imagecolorallocate($im,0,0,0);
	
	$bbox = imagettfbbox($size,0,$font,$n);
	
	$x = abs($bbox[0]-$bbox[2]);
	$x = (imagesx($im)-$x)/2;
	
	$y = abs($bbox[1]-$bbox[5]);
	$y = (imagesy($im)/2)+($y/2);
	
	imagettfstroketext($im,$size,0,$x,$y,$black,$white,$font,$n,1);
	header("Content-Type: image/jpg");
	imagejpeg($im);
	imagedestroy($im);
}

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
    for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
        $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
    return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}
?>