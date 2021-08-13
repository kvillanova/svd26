<?php
$title = isset($title) ? " | $title" : ": ".$vd::TEMPORADA;
$desc = isset($desc) ? $desc : $vd::FRANQUIA." ".$vd::SEASON.": ".$vd::TEMPORADA;
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if(!isset($responsive)) 
	$responsive = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

$site = $vd::SITE;
$short = $vd::SHORT;

if(defined('VDSQL::IMG')) $og_img = $vd::IMG;
else $og_img = NULL;

if(!$loc) $og = PHP_EOL . <<<OG
<meta property="og:image" content="{$og_img}" />
<meta property="og:title" content="{$short}{$title}" />
<meta property="og:description" content="{$desc}" />
<meta property="og:url" content="{$url}" />
<meta property="og:type" content="website" />
OG;

else $og=NULL;

echo <<<O
{$responsive}
<meta charset="UTF-8">{$og}
<title>{$short}{$title}</title>
O;
echo PHP_EOL;
?>