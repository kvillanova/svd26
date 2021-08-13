$("#click-box").click(function() {
	sendResp(1);
});

function sendResp($x) {
	if(typeof $x ==='undefined') $x=1;
	$.ajax({
		'url':'resp.php',
		'type':'POST',
		'data':{'click':$x}
	}).done(function(m){
		$("#r").html(m);
	});
}