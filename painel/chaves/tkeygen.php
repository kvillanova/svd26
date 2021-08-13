<?php
function genTkey() {
	$m = range("a","z");
	$n = range("0","9");
	$tkey = array();
	
	for($i=0;$i<4;$i++) {
		shuffle($m);
		$tkey[] = $m[0];
	}
	
	for($i=0;$i<4;$i++) {
		shuffle($n);
		$tkey[] = $n[0];
	}
	
	$count = array_count_values($tkey);
	rsort($count,SORT_NATURAL);
	
	if($count[0]>2) return genTkey();
	
	shuffle($tkey);
	$tkey = implode("",$tkey);
	
	if(preg_match("%([a-z]{3,})|([0-9]{3,})%uis",$tkey)) return genTkey();
	if(preg_match("%([a-z0-9])(\\1)%uis",$tkey)) return genTkey();
	
	return $tkey;
}
?>