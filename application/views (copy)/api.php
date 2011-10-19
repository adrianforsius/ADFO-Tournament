<?
	$connection = mysql_connect("127.0.0.1", "root", "root");
	mysql_select_db("tournament");
	
	if($_POST['reguser'] != '' && $_POST['regpassword'] != ''){
		$sql = "INSERT INTO users (user_name, user_password) VALUES('".$_POST['reguser']."', '".$_POST['regpassword']."')";
		mysql_query($sql);
	}

	if($_POST['team'] != null){
		updateTeam();
	}
	if($_GET['startoption'] == 1){
		fetchTeams();
	}
	if($_POST['resetoption'] == 1){
		resetTeams();
	}
	function resetTeams(){
		for($i = 0; $i < 32; $i++){
			$sql = "UPDATE teams SET team_name='".$i."', team_id='".$i."' WHERE id='".($i+1)."'";
			mysql_query($sql);
		}
	}
	function fetchTeams(){
		$sql = "SELECT * FROM teams";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$return[] = $row;
		}
		echo json_encode($return);
	}
	function updateTeam(){
		$sql = "UPDATE teams SET team_name='".$_POST['team']."' WHERE id='".($_POST['place']+1)."'";
		$result = mysql_query($sql);
	}
