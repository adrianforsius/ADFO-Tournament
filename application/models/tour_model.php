<?php
class Tour_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	//Match stats go thru admin-tournament-view to and check each match for result 
	function match_stats(){
		$bracketId = $this->input->post('bracketId');
		$bracketInfo = $this->get_bracket_set_by_id($bracketId);
		
		$base = 0;
		$colo = log($bracketInfo[0][0]['size'],2);
		for($i = 0; $i <= $colo; $i++){
			$jump = ($bracketInfo[0][0]['size']/pow(2,$i)+1);
			for($e = 0; $e <= ($bracketInfo[0][0]['size']/pow(2,$i)-2);$e+=2){

				//Get all the matches from the start to the end for each match two teams are fetch
				$team1Points = $this->input->post('team_'.($base+$e+1));
				$team2Points = $this->input->post('team_'.($base+$e+2));
				
				$match1Id = $this->input->post('matchId_'.($base+$e+1));
				$match2Id = $this->input->post('matchId_'.($base+$e+2));

				/*print_r('$e '.$e);
				print_r('$jump '.$jump);
				print_r('$base '.$base);
				echo '<br>';*/
				
				//Check the points for the current match and see if its legit
				if
				(
					!empty($team1Points) && 
					!empty($team2Points) &&  
					$team2Points != $team1Points
				)
				{
					
					//print_r('team1Points: '.$team1Points);
					//print_r('team2Points: '.$team2Points);
					
					
					//both teams have good values sql is started
					$sql =
					'
						INSERT INTO team__attend__bracket
						(team_id, bracket_id, position)
					';
					
					if($team1Points > $team2Points){
						$sql.=
						'
							VALUES('.$bracketInfo[1][$base+$e]['team_id'].','.$bracketInfo[0][0]['id'].','.($base+$e+$jump).')
						';
					}else{
						$sql.=
						'
							VALUES('.$bracketInfo[1][$base+$e+1]['team_id'].','.$bracketInfo[0][0]['id'].','.($base+$e+$jump).')
						';
					}
					$this->db->query($sql);
					
					$this->update_points($team1Points, $match1Id);
					$this->update_points($team2Points, $match2Id);
				}
				//Adjust jump for next match
				$jump--;
				//Update base if the end of the line
				if($e == ($bracketInfo[0][0]['size']/pow(2,$i)-2)){
					$base+=($e+2);
				}
			}
		}
		return $bracketInfo[0][0]['arena'];
	}
	
	//Upgrade points
	function update_points($teamPoints, $matchId){
		$sql = 
		'
			UPDATE team__attend__bracket
			SET points = '.$teamPoints.' 
			WHERE match_id = '.$matchId.'
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	/*	
	 * 	Delete one teams position in the bracket
	 * 
	 * 	@param INT
	 * 	@param INT
	 *	return INT
	 */
	function delete_team_position($matchId, $bracketId){
		//Get both values from link get-valued
		$sql =
		'
			DELETE FROM team__attend__bracket
			WHERE match_id = '.$matchId.'
		';
		if($this->db->query($sql)){
			$bracketInfo = $this->get_bracket_set_by_id($bracketId, 1);
			return $bracketInfo[0][0]['arena']; 
		}
		return false;
	}
	
	function undo_team_position($matchId, $bracketId){
		$sql =
		'
			UPDATE team__attend__bracket
			SET position = NULL
			WHERE match_id = '.$matchId.'
		';
		if($this->db->query($sql)){
			$bracketInfo = $this->get_bracket_set_by_id($bracketId, 1);
			return $bracketInfo[0][0]['arena']; 
		}
		return false;
		
	}
	
	
	/*	Random teams TOVERIFY
	 *
	 * 
	 */
	function random_teams($bracketId){
		
		$sql =
		'
			SELECT team, team__attend__bracket.position
			FROM team, team__attend__bracket, bracket
			WHERE team.id = team__attend__bracket.team_id
				AND team__attend__bracket.bracket_id = bracket.id
				AND bracket.id = '.$bracketId.'
		';
		
		$query = $this->db->query($sql);
		$teams = $query->result_array();
		
		suffle($teams);
		
		$max = count($teams);
		for($i = 1; $i <= $teams; $i++){
			$sql = 
			'
				UPDATE team__attend__bracket
				SET team__attend__bracket.name = '.$team['name'].'
				SET team__attend__bracket.points = 0
				WHERE team__attend__bracket.bracket_id = '.$bracketId.'
				AND team__attend__bracket.position = '.$i.'
			';
		}
	}
	
	//Create team
	function create_team($teamName){
		$this->db->insert('team', array('name' => $teamName));
		$teamId = $this->db->insert_id();
		if($this->db->insert('user__register__team', array('team_id' => $teamId, 'user_id' => $this->session->userdata('id'), 'officer' => '1'))){
			return true;
		}
		return false;
	}
	
	function place_team(){
		$bracketId = $this->input->post('bracketId');
		$bracket = $this->get_bracket_set_by_id($bracketId, 1);
		$appliedTeam = $this->fetch_applied_team($bracket[0][0]['arena']);
	
		$max = count($appliedTeam);
		for($i = 0; $i < $max; $i++){
			$position = $this->input->post('position_'.$i);
			$teamId = $this->input->post('teamId_'.$i);
			
			if($position != 0){
				$sql =
				'
					SELECT position
					FROM team__attend__bracket
					WHERE position = '.$position.'
						AND bracket_id = '.$bracketId.'
				';
				
				$query = $this->db->query($sql);
				$result = $query->result_array();
				
				if(empty($result)){
					$sql =
					'
						UPDATE team__attend__bracket
						SET position = '.$position.'
							WHERE bracket_id = '.$bracketId.'
							AND team_id = '.$teamId.'
					';
					$query = $this->db->query($sql);
				}
			}
		}
		return $bracket[0][0]['arena'];
	}
	
	function get_teams_by_user_and_size($size){
		$sql = 
		'
			SELECT team.*
			FROM
			(
			SELECT team.*, COUNT(team.name) AS team_size
			FROM team, user__register__team, user
			WHERE team.id = user__register__team.team_id
				AND user__register__team.user_id = user.id
			GROUP BY team.name
			HAVING team_size = '.$size.'
			) AS team, user, user__register__team
			WHERE team.id = user__register__team.team_id
				AND user__register__team.user_id = user.id
				AND user.id = '.$this->session->userdata('id').'
		';
		$query = $this->db->query($sql);
		$teamName = $query->result_array();
		if(!empty($teamName)){
			return $teamName;
		}
		return false;
	}
	
	function get_teams_by_user(){
		$sql = 
		'
			SELECT team.*
			FROM team, user__register__team, user
			WHERE team.id = user__register__team.team_id
				AND user__register__team.user_id = user.id
				AND user.id = '.$this->session->userdata('id').'
		';
		
		$query = $this->db->query($sql);
		$teamName = $query->result_array();
		if(!empty($teamName)){
			return $teamName;
		}
		return false;
	}
	
	function get_item_by_id($item, $id){
		$sql = 
		'
			SELECT *
			FROM '.$item.'
			WHERE '.$item.'.id = '.$id.'
		';
		
		$query = $this->db->query($sql);
		$data = $query->result_array();
		if(!empty($data)){
			return $data;
		}
		return false;
	}
	
	function get_team_member($id){
		$sql =
		'
			SELECT user.name, user.id, urt.officer
			FROM user__register__team AS urt, user
			WHERE urt.user_id = user.id
				AND urt.team_id ='.$id.'
		';
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		if(!empty($data)){
			return $data;
		}
		return false;
	}
	
	function get_all($table){
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(!empty($data)){
			return $data;
		}
		return false;
	}
	
	function fetch_applied_team($arena = 1){
		//Get active brackets
		$activeBracket = $this->get_active_bracket($arena);
		
		//if there is no activeBrackets aka no tournamnets active during this point in time
		if(!empty($activeBracket)){
			$sql = 
			'
				SELECT team.*
				FROM bracket, team__attend__bracket, team
				WHERE bracket.id = team__attend__bracket.bracket_id
					AND team__attend__bracket.team_id = team.id
					AND bracket.id = '.$activeBracket[0]['id'].'
					AND team__attend__bracket.position = 0
				ORDER BY created ASC
			';
			$query = $this->db->query($sql);
			$appliedTeams = $query->result_array();
			
			if(!empty($appliedTeams)){
				return $appliedTeams;
			}
		}
		return false;
	}
	
	/**
	 *	Fetch brackets information and team assigned to brackets 
	 * 
	 * 	return ass-array
	 */
	function fetch_bracket($arena){
		//Get active brackets
		$activeBracket = $this->get_active_bracket($arena);
		
		//if there is no activeBrackets aka no tournamnets active during this point in time
		if(!empty($activeBracket)){
			$sql = 
			'
				SELECT team.name, team__attend__bracket.* 
				FROM bracket, team__attend__bracket, team
				WHERE bracket.id = team__attend__bracket.bracket_id
					AND team__attend__bracket.team_id = team.id
					AND bracket.id = '.$activeBracket[0]['id'].'
					AND team__attend__bracket.position > 0
				ORDER BY team__attend__bracket.position ASC
			';
			$query = $this->db->query($sql);
			$bracketset = $query->result_array();
			$bracket[] = $activeBracket[0];
			$bracket[] = $bracketset;
			if(!empty($bracket)){
				return $bracket;
			}
		}
		return false;
	}
	
	
	//Get active bracket from arena
	function get_active_bracket($arena = 0){
		$sql = 
		'
			SELECT *
			FROM bracket
			WHERE start < NOW()
				AND end > NOW()
				AND arena = '.$arena.'
		';		
		
		$query = $this->db->query($sql);
		$activeBracket = $query->result_array();
		if(!empty($activeBracket)){
			return $activeBracket;
		}
		return false;
	}
	
	//Fetch all active brackets
	function get_active_brackets(){
		$sql = 
		'
			SELECT *
			FROM bracket
			WHERE start < NOW()
				AND end > NOW()
			ORDER BY arena ASC
		';
		
		$query = $this->db->query($sql);
		$activeBracket = $query->result_array();
		if(!empty($activeBracket)){
			return $activeBracket;
		}
		return false;
	}
	
	//Get bracket information and teams information in a ass-array
	function get_bracket_set_by_id($bracketId, $part = 0){
		//bracketset to be filled with bracket information as the first part and teams as the secound part
		$bracketSet = array();
		$sql =
		'
			SELECT *
			FROM bracket
			WHERE bracket.id = '.$bracketId.'
		';
		$query = $this->db->query($sql);
		$bracket = $query->result_array();
		$bracketSet[] = $bracket;
		
		if($part = 1){
			return $bracketSet;
		}
		
		$sql = 
		'
			SELECT team.name, team__attend__bracket.* 
			FROM bracket, team__attend__bracket, team
			WHERE bracket.id = team__attend__bracket.bracket_id
				AND team__attend__bracket.team_id = team.id
				AND bracket.id = '.$bracketId.'
				AND team__attend__bracket.position > 0
			ORDER BY team__attend__bracket.position ASC
			
		';
		$query = $this->db->query($sql);
		$teams = $query->result_array();
		$bracketSet[] = $teams;
		
		if(!empty($bracketSet)){
			return $bracketSet;
		}
		return false;
	}
	
	function get_bracket_set(){
		$activeBrackets = $this->get_active_brackets();
		//bracketset to be filled with bracket information as the first part and teams as the secound part
		$bracketSet = array();
		$appliedTeams = array();
		$teams = array();
		$bracketSet[] = $activeBrackets;
			
		foreach($activeBrackets as $index => $bracket){
			$sql = 
			'
				SELECT team.name, team__attend__bracket.* 
				FROM bracket, team__attend__bracket, team
				WHERE bracket.id = team__attend__bracket.bracket_id
					AND team__attend__bracket.team_id = team.id
					AND bracket.id = '.$bracket['id'].'
					AND team__attend__bracket.position > 0
				ORDER BY team__attend__bracket.position ASC
				
			';
			$query = $this->db->query($sql);
			$teams[] = $query->result_array();
			$appliedTeams[] = $this->fetch_applied_team($bracket['arena']);
			
		}
		$bracketSet[] = $teams;
		$bracketSet[] = $appliedTeams;
		
		if(!empty($bracketSet)){
			return $bracketSet;
		}
		return false;
	}
	
	//Fetch game names for active brackets, to display as the menu
	function fetch_game_name(){
		$activeBrackets = $this->get_active_brackets();
		$amount = count($activeBrackets);
		
		$gameNames = array();
		for($i = 0; $i < $amount; $i++){
			$sql = 
			'
				SELECT game.name, bracket.arena
				FROM bracket, game
				WHERE game_id = game.id
					AND bracket.id = '.$activeBrackets[$i]['id'].'
				ORDER BY arena ASC
			';
			
			$query = $this->db->query($sql);
			$gameNames[] = $query->result_array();
			
		}
		if(!empty($gameNames)){
			return $gameNames;
		}
		return false;
	}
		
	/**
	 *	Add team to bracket and place in db 	
	 * 
	 *  return bool
	 */
	function sign_up_team_to_bracket(){
		$data = array
						(
							'team_id' =>  $this->input->post('tourTeam'),
							'bracket_id' =>  $this->input->post('tourBracketId'),
							'position' => 0,
						);
		
		$this->db->where($data);
		$query = $this->db->get_where('team__attend__bracket');
		if (!$query->num_rows()) {
			$this->db->insert('team__attend__bracket', $data);
		}
		return false;
	}
	
	function sign_up_player_to_bracket(){
		$userInfo = $this->session();
		$sql =
		'
			SELECT *
			FROM user__register__team
			WHERE id='.$userInfo['id'].'
				AND player=1
		';
		$query = $this->db->query($sql);
		$teamInfo = $query->result_array();
		
		$data = array
						(
							'team_id' =>  $teamInfo['id'],
							'bracket_id' =>  $this->input->post('tourBracketId'),
							'position' => 0,
						);
		
		$this->db->where($data);
		$query = $this->db->get_where('team__attend__bracket');
		if (!$query->num_rows()) {
			$this->db->insert('team__attend__bracket', $data);
		}
		return false;
		
	}

	/**
	 * Get user data from session
	 * 
	 * return bool
	 */
	function session($data = ''){
		if($data == '') {
			$userdata = $this->session->all_userdata();
		} else {
			$userdata = $this->session->userdata($data);
		}
		if(!empty($userdata)){
			return $userdata;
		}
		return false;
	}
}
