$(function(){
	$('.current').show();
	$('#nav a').click(function(){
		$(this).addClass('current');
	});
	//$("div#bracketHolder > div").hide();
	//$("#stTournament").show();
	$("#tabs li:first-child").addClass("show");
	
	
	$('.delete').click(function(){
		var answer = confirm('Delete?');
		return answer // answer is a boolean
	});  
	
	arena = $(".tabNavigation li").attr('title');
	$("#"+arena+"Tournament").fadeIn(2000);
	updateBoard(arena);
	
	$(".tabNavigation li").click(function(){
		$('.bracketHolder').removeClass('current');
		$('.tournamentInfo').removeClass('current');
		$(".tabNavigation li").removeClass("show");
		$(this).addClass("show");
		arena = $(this).attr('value');
		$('.arena'+arena).addClass('current');
		$('.tourInfo'+arena).addClass('current');
		updateBoard(arena);
		$("#tournament").fadeIn(2000);
	});
	
	
	//updateBoard();
	function updateBoard(arena){
		$.getJSON('get_bracket/1/'+arena, function(data){
			$.each(data[0], function(index, value){
				$('.bracketName').text(value.name);
				$('.bracketSize').text(value.size);
				$('.bracketTeamSize').text(value.team_size);
				$('.bracketType').text(value.type);
				$('.bracketStart').text(value.start_time);
				$('.bracketEnd').text(value.end_time);
				//$('.player p').text('');
				//$('.supervise').attr('href', 'http://localhost/tournament-0.0.2/tour_controller/supervise_tournament/'+arena);
			});
		});
		//start to loop
		//setTimeout(updateBoard, 5000);
	}
	
	
	/*var id = '';
	var teamname = '';
	$('.player').click(function(){
		if($(this).hasClass('active')){
			
		}else{
			$('.active').css('background-color', '#666');
			$('.activeform').remove();
			$('.active').html('<div class="playerinfo"><p id="'+id+'">'+teamname+'</p><p id="points"></p></div><div class="frame"><img src=""></div>');
			$('.active').removeClass('active');

			$(this).addClass('active');
			id = $('.active p').attr('id');
			teamname = $('#'+id).html();
			
			$(this).html('<form class="activeform" method="post" action="load_tournament/insert_F/'+teamname+'/'+arena+'/'+id+'"><input class="teaminput" name="teaminput" type="text" size="12"></input><input class="teamsubmit" name="teamsubmit" type="submit" value="submit"></input><input class="closeplayer" name="closeplayer" type="submit" value="close"></input></form>');
			$(this).css('background-color', 'green');
		}
	});*/
	
	
	/*$('.activeform').submit(function() {
		alert('Handler for .submit() called.');
		return false;
	});*/
	
	
	//Modify teams, insert teams into database.
	$('#teamsubmit').click(function(){
		var current = $('.active p').attr('id');
		var team_id = $(".teaminput").val();
		var getTeam = [];
		
		//$.getJSON() kontra $.post(), varför funkar inte post i api.php??
		//wisdome, man kan inte använda .get() eller [] efter man har fetchat elemet från domträdet dom man vill använda
		//ytterligare funktioner
		//.innerHTML är den resättninglösningen för array[i].text(team); eller liknande
		//also $.each() mer vänlig 
				
		//Push needed data so a single array
		getTeam.push(place);
		getTeam.push(team);
		
		//Update the DB-table
		$.post("insert_team", {	"team" : getTeam[0].val(), "place" : getTeam[1].val() }, function(){ 
			updateBoard(); 
		});
		
		return false;
	});
	
	$("#reset").click(function(){
		if(confirm("Are you sure you want to reset?")){
			$.post("api.php", { "resetoption" : 1 }, function(){updateBoard();});
		}
		return false;
	});
	
	
	
	//Form validation JQUERY FUCK YEAH
	$("#regformsubmit").click(function(){
		console.debug($("#regpassword").val());
		var user = $("#reguser").val();
		var password = $("#regpassword").val();
		var usercheck = validate(user);
		var passwordcheck = validate(password);
		
		if(usercheck && passwordcheck){
			return true;
		}else{
			return false;
		}
	});
	function validate(text){
		var ValidChars = "abcdefghijklmnopqrstuvwxyzåäö1234567890";
		if(text != ""){
			for (i = 0; i < text.length; i++){ 
				Char = text.charAt(i); 
				if (ValidChars.indexOf(Char) == -1){
					return false;
				}
			}
		}
		return true;
	}
});
