adjust();
$('.script').mouseup(function() { adjust(); }).mouseleave(function() { adjust(); });

function adjust() {
	var h = $('.script').height();
	h = h-14;
	$('.log').css({'height':h+'px'}).scrollTop($('.log').prop('scrollHeight'));
}

$('#kvl').submit(function(e) {
    e.preventDefault();
	$.ajax({
		url:"query.php",
		type:'POST',
		data: $(this).serializeArray()
}).done(function(msg) { $("#content").html(msg); });
});

$(document).on("keydown","textarea",function(e){
  var keyCode = e.keyCode || e.which;

  if (keyCode == 9) {
    e.preventDefault();
    var start = this.selectionStart;
    var end = this.selectionEnd;

    // set textarea value to: text before caret + tab + text after caret
    $(this).val($(this).val().substring(0, start)
                + "\t"
                + $(this).val().substring(end));

    // put caret at right position again
    this.selectionStart =
    this.selectionEnd = start + 1;
  }
});

$('#mdgen').submit(function(e) {
    e.preventDefault();
	$.ajax({
		url:"keygen.php",
		type:'POST',
}).done(function(msg) { $("#keyholder").val(msg); });
});

$('.fa-question-circle').mouseup(function() {
	$.ajax({
		url:"query.php",
		type:'POST',
		data: {'script':'#about'}
}).done(function(msg) { $("#content").html(msg); });
});

$('#clearscript').mouseup(function() {
	$("#kvl .script").val('');
});

$('#clearlog').mouseup(function() {
	$.ajax({
		url:"query.php",
		type:'POST',
		data: {'script':'#clear'}
}).done(function(msg) { $("#content").html(msg); });
});