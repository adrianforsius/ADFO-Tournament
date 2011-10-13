$(function(){
	$("#createTournament").click(function(){
		var container = $("#bracketHolder");
		//wordpress like, radio buttons (custom meta boxes)
		if($("#bracketType").val() == 16){
			$("<div class="++"").appendTo(container);
		//4 players bracket, single elimination
		}else if("#bracketType").val() == 4){
			$("<div class="++"").appendTo(container);
		//8 players bracket, double elimination
		}else if("#bracketType").val() == "8d"){
			
		//8 players bracket, single elimination	
		}else{
			$("<div class="++"").appendTo(container);
		}
	}	
});
