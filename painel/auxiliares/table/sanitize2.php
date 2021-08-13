<?php
if(!empty($_POST['titulo'])) {
	$titulo = $_POST['titulo'];
	$ii = "";
	for($i=0; $i<$_POST['secao']; $i++) $ii .= "=";
	$titulo = "{$ii}{$titulo}{$ii}";	
}
else $titulo = NULL;

if(isset($_POST['temporada']) && !empty($_POST['temporada'])) $temp = $_POST['temporada']; else $temp = 0;

//REMOVE OLD TITLE
$c = preg_replace("%^\s*==+(.*?)==+\s*%uis","",$c);

//CLEANING
$c = preg_replace(array("%>\s+%uis","%\s+<%uis"),array("> "," <"),$c);
$c = preg_replace(array("%</(td|tr|th|table|br)>\s+%uis","%\s+<(td|tr|th|table|br)%uis"),array("</$1> "," <$1"),$c);

//MEANINGLESS TAGS
$c = preg_replace("%</?t(body|head)>%uis","",$c);

//END TAGS NOT NEEDED
$c = preg_replace("%</t(able|d|r|h)>%uis","",$c);

//COLOCA ESTILOS
$c = preg_replace("%<t(able|d|r|h)\s+(.*?)>%uis","<t$1> $2 |", $c);

echo $titulo;

$rows = explode("<tr>",$c);

foreach($rows as $i => $r):
	if($i>0) echo PHP_EOL . "|-";

	$td = preg_split("%(<t(?:d|h|able)>)%", $r, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach($td as $j => $dd):
		if($j===0 && empty($dd)) continue;
		if($dd==="<table>") echo PHP_EOL . "{|";
		elseif($dd==="<th>") echo PHP_EOL . "!";
		elseif($dd==="<td>") echo PHP_EOL . "|";
		elseif(preg_match("%(\d+)/(\d+).*?Votos?%uis", $dd) or preg_match("%Empate%uis",$dd)) {
			$votos[] = preg_replace("%\|.*?(\d+)/(\d+).*$%uis","|$1/$2",$dd);
			$dd = preg_replace("%^(.*?)<br\s*/?>\s*\d+/\d+.*$%uis", "$1", $dd);
			$dd = preg_replace("%\|\s*(\w+)%uis", "class=\"votedout\" |[[Arquivo:$1 s{$temp}.jpg|50px|link=$1 xxx]]<br>$1",$dd);
			if(preg_match("%Empate%uis",$dd)) $dd = " style=\"background-color:#aeaeae;\" |";
			echo html2wiki($dd);
		}
		else echo html2wiki($dd);
	endforeach;

	if(!empty($votos)) {
		echo PHP_EOL . "|-";
		echo PHP_EOL . "|Votos";
		foreach($votos as $v):
			echo PHP_EOL . "|{$v}";
		endforeach;
	} unset($votos);

	if($i===count($rows)-1) echo PHP_EOL . "|}";
	
endforeach;

function html2wiki($el) {
	$json = json_decode(file_get_contents(__DIR__."/searchreplace.json"),true);
	$json = $json['serp'];
	foreach($json as $j) {
		$el = preg_replace($j['search'],$j['replace'],$el);
	}
	if(preg_match("%rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)%", $el, $col)) {
		$col = sprintf("%02x%02x%02x",$col[1],$col[2],$col[3]);
		$el = preg_replace("%rgb\s*\(.*?\)%", "#{$col}", $el);
	}
	return $el;
}


?>