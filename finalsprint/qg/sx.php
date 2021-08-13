<?php
if(!isset($_GET['n'])) die;
$n = $_GET['n'];
$a = +$_GET['a'];

$n = mb_strtoupper(chr(hexdec($n)));

$fonte = __DIR__ . "/../../inc/styles/fontes/blemished.ttf";

$c = imagettfbbox(30,0,$fonte,$n);
$w = $c[4]-$c[0];
$h = $c[5]-$c[1];

$x = (60-$w)/2;
$y = (60-$h)/2;

//header("Content-type: image/jpeg");
$im = imagecreate(60, 60);

$colour = imagecolorallocate($im, hexdec("83"), hexdec("4d"), hexdec("ff"));
$texto = imagecolorallocate($im, 255, 255, 255);

imagefill($im, 0, 0, $colour);
// Replace path by your own font path
imagettftext($im, 30, 0, $x, $y, $texto,$fonte, $n);
imagepng($im);
imagedestroy($im);
?>