<?php
function md5gen($a=NULL) {
$a = $a . "@##@#@##@#3++#__##+#+#Feels like its coming the coming of age";
$c = microtime();
$m = substr(md5($a.$c),0,8);

$b = mb_strtolower(base64_encode($m));
$b = preg_replace("%[+/_=-]%", "", $b);
		
$m = str_split($m);
$b = str_split($b);
		
$t = "";
$no = 0;
$w = 0;
		
for($i=0;$i<8;$i++):
if($no>3)
{  
while(!preg_match("%[a-z]%", current($b))) next($b);
$t .= current($b);
next($b);
next($m);
continue;
} 
if($w>3)
{
while(!preg_match("%[0-9]%", current($m))) next($m);
$t .= current($m);
next($b);
next($m);
continue;	
}
		
if(preg_match("%[0-9]%", current($m))) {
$t .= current($m);
next($b);
next($m);
$no++;
}
else {
while(!preg_match("%[a-z]%", current($b))) next($b);
$t .= current($b);
next($b);
next($m);
$w++;
}
		
endfor;
		
return $t;
}
?>