<?php
function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true) {
    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
    $enc = preg_replace_callback(
        '/"(.*?)"/s',
        function ($field) {
            return urlencode($field[1]);
        },
        $enc
    );
    $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
    return array_map(
        function ($line) use ($delimiter, $trim_fields) {
            $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
            return array_map(
                function ($field) {
                    return str_replace('!!Q!!', '"', urldecode($field));
                },
                $fields
            );
        },
        $lines
    );
}

function removeAccents($name,$hifen=true) {
$subs = array(
'%[á|ä|à|ã|â|ă]%ui',
'%[é|ê|è|ë]%ui',
'%[í|î|ì]%ui',
'%[ó|ô|õ|ò]%ui',
'%[ú|û|ù]%ui',
'%ç%ui',
'%ñ%ui',
'%ş%ui',
'%ț%ui'
);
	
$n = array('a', 'e', 'i', 'o', 'u', 'c', 'n', 's', 't');
$name = preg_replace($subs, $n, $name);
	
$p = array(
'%\s+%',
"%'%",
'%[.,;!?]%',
);

if($hifen) $p[] = '%-%';

$name = preg_replace($p, "", $name);
$name = preg_replace('%-+%', '-', $name);
$name = mb_strtolower($name);
return $name;
}

function rerro($m=NULL,$c="red",$r=false,$disappear=NULL,$add=NULL) {
	if($r) $r = "location.reload();";
	if(!$r && is_int($disappear)) $add .= "setTimeout(function(){ $(\"#r\").html(\"\").removeClass(); },$disappear);";
	echo <<<LAT
	<script>$("#r").removeClass().addClass("w3-panel w3-{$c}").html("<p>{$m}</p>");{$r}{$add}</script>
LAT;
	die;
}

function scrollr($a,$c="red",$rto=false,$time=2000) {
	$x = <<<LAT
	$("html, body").animate({scrollTop:$("#r").offset().top},$time);
LAT;
	if(is_numeric($rto)) $x .= "setTimeout(function(){location.reload();},{$rto});";
	rerro($a,$c,false,$x);
}

function blankrerro($force=false) {
	if(!$force) echo <<<LAT
	<script>location.reload();</script>
LAT;
	elseif(is_bool($force) && $force===true) echo <<<LAT
	<script>location.href = './';</script>
LAT;
	
	else echo <<<LAT
	<script>location.href = '{$force}';</script>
LAT;
	die;
}

function mdfy($input,$salt=array(NULL,NULL),$size=15,$before=NULL,$after=NULL,$start=0) {
	if($size===-1) $a = md5($salt[0].$input.$salt[1]);
	else $a = substr(md5($salt[0].$input.$salt[1]),$start,$size);
	
	return $before . $a . $after;
}

function atualfloat($atual, $min=0) {
	if($min===0) return (int) preg_replace("%^(\d+)\.\d+$%", "$1", $atual);
	if($min===1) return (int) preg_replace("%^\d+\.(\d+)$%", "$1", $atual);
}

function vd_session() {
if(!in_array($_SERVER['HTTP_HOST'],array('localhost','127.0.0.1','192.168.0.117')))
session_save_path(dirname(realpath($_SERVER['DOCUMENT_ROOT'])) . "/tmp");
session_cache_expire( 60 * 24 );
session_start();
}

function location($x=NULL,$bar=true) {
	if($bar) header("Location: ./{$x}");
	else header("Location: {$x}");
	die;
}
?>