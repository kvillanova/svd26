$(document).ready(function(){
	var e={
		rows:4,
		cols:4,
		hole:4,
		shuffle:true,
		numbers:false,
		language:"pt",
		control:{
			shufflePieces:false,
			confirmShuffle:false,
			toggleOriginal:false,
			toggleNumbers:false,
			counter:false,
			timer:false,
			pauseTimer:false
		},
		success:{
			fadeOriginal:true,
			callback:function(){ finishIt(); },
			callbackTimeout:0
		},
		animation:{
			shuffleRounds:3,
			shuffleSpeed:800,
			slidingSpeed:200,
			fadeOriginalSpeed:600
		},
		style:{
			gridSize:2,
			overlap:true,
			backgroundOpacity:0.3
		}
	};
$("#screen").jqPuzzle(e);
});

function finishIt() {  
	$.ajax({
	url:"checkt",
	type:'POST', 
	data:{'a':"b01ef992b9f8c67248ceb990412b17f1"}
	}).done(function(e) { $("#r").html(e); });
}