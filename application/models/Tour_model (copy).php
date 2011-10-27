<?php
class Tour_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	//Insert, tournament
	function team_advance($teamId, $position){
		$data = array
		(
			'team_id' => $teamId, 
			'bracket' => $this->input->post('bracketId'), 
			'position'=> $positon,
		);
		return $this->insert('team__attend__bracket', $data);
	}
	
	//Update, tournament
	function update_points($teamPoints, $matchId){
		$data = array
		(
			'points' => $teamPoints, 
		);
		$where = array
		(
			'match_id' => $matchId
		);
		return $this->update('team__attend__bracket', $data, $where);
	}
	
	//Delete tournament
	function delete_team_position($matchId, $bracketId){
		//Get both values from link get-valued
		$where = array
		(
			'match_id' => $matchId
		);
		if($this->delete('team__attend__bracket', $data, $where)){
			$bracketInfo = $this->get_bracket_set_by_id($bracketId, 1);
			return $bracketInfo[0][0]['arena'];
		}
	}
	
	//Update tournament
	function undo_team_position($matchId, $bracketId){
		$data = array
		(
			'position' => 0
		);
		
		$where = array
		(
			'match_id' => $matchId
		);
		return $this->update('team__attend__bracket');
		
		if($this->db->query($sql)){
			$bracketInfo = $this->get_bracket_set_by_id($bracketId, 1);
			return $bracketInfo[0][0]['arena']; 
		}
		return false;
	}
	
	//select from join where whereSwitch
	//TournamentSupervise select
	function get_verfied_teams($bracketId){
		$where = array
		(
			'bracket.id' => $bracketId,
			'team__attend__bracket.position !=' => 0,
		);	
		$join = array
		(
			'team' => 'team.id = team__attend__bracket.team_id',
			'bracket' => 'bracket.id = team__attend__bracket.bracket_id'
		);
		return $this->join('*', 'team', $join, $where, true);
	} 

	function random_teams($bracketId){
		$bracket = $this->get_bracket_set_by_id($bracketId, 1);
		
		$sql =
		'
			SELECT team.*, team__attend__bracket.position
			FROM team, team__attend__bracket, bracket
			WHERE team.id = team__attend__bracket.team_id
				AND team__attend__bracket.bracket_id = bracket.id
		';
		
		$query = $this->db->query($sql);
		$teams = $query->result_array();
		
		shuffle($teams);
		
		$max = count($teams);
		for($i = 0; $i < $max; $i++){
			$sql = 
			'
				UPDATE team__attend__bracket AS tab
				SET tab.team_id = '.$teams[$i]['id'].',
					tab.position = '.($i+1).',
					tab.points = 0
				WHERE tab.bracket_id = '.$bracketId.'
					AND tab.position = '.($i+1).'
			';
			$query = $this->db->query($sql);
		}
		return $bracket[0][0]['arena'];
	}
	
	//Create team
	function create_team($teamName){
		$this->db->insert('team', array('name' => $teamName));
		$teamId = $this->db->insert_id();
		if($this->db->insert('user__register__team', array('team_id' => $teamId, 'user_id' => $this->session_manager('id'), 'officer' => '1'))){
			return true;
		}
		return false;
	}
	
	
	/************** TEAM REQUESTS **************/
	function leave_team($teamId){
		$sql =
		'
			DELETE FROM user__register__team
			WHERE team_id = '.$teamId.'
				AND user_id = '.$this->Tour_model->session_manager('id').'
				ACTIVE = 1
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	function apply_to_team($teamId){
		$sql =
		'
			INSERT INTO user__register__team
			(user_id, team_id, active)
			VALUES('.$this->session_manager('id').', '.$teamId.', 0)
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	function accept_team_applicant($userId, $teamId){
		if($this->is_officer($teamId)){
			$sql =
			'
				UPDATE user__register__team
				SET active = 1
				WHERE user_id = '.$userId.'
					AND team_id = '.$teamId.'
					AND active = 0
			';
			if($this->db->query($sql)){
				return true;
			}
		}
		return false;
	}
	
	function decline_team_applicant($userId, $teamId){
		$sql =
		'
			DELETE FROM user__register__team
			WHERE user_id = '.$userId.'
				AND team_id = '.$teamId.'
				AND active = 0
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	function decline_invite_from_team($teamId){
		$sql =
		'
			UPDATE user__register__team
			SET active = 1
			WHERE user_id = '.$this->session_manager('id').'
				AND team_id = '.$teamId.'
				AND active = 2
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	function accept_invite_from_team($teamId){
		$sql =
		'
			UPDATE user__register__team
			SET active = 1
			WHERE user_id = '.$this->session_manager('id').'
				AND team_id = '.$teamId.'
				AND active = 2
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	function is_officer($teamId){
		$sql =
		'
			SELECT *
			FROM user__register__team AS urt
			WHERE urt.user_id = '.$this->session_manager('id').'
				AND team_id = '.$teamId.'
				AND urt.officer = 1
		';
		$query = $this->db->query($sql);
		$team = $query->result_array();
		
		if($team){
			return true;
		}
		return false;
	}
	
	function get_team_invites(){
		$sql =
		'
			SELECT team.*
			FROM user__register__team AS urt, team
			WHERE team.id = urt.team_id
				AND user_id = '.$this->session_manager('id').'
				AND active = 2
		';
		
		$query = $this->db->query($sql);
		$invites = $query->result_array();
		if($invites){
			return $invites;
		}
		return false;
	}
	
	function get_applicants_for_team($teamId){
		$sql =
		'
			SELECT user.id, username
			FROM user__register__team AS urt, user
			WHERE urt.user_id = user.id
				AND urt.team_id = '.$teamId.'
				AND urt.active = 0
		';
		
		$query = $this->db->query($sql);
		$users = $query->result_array();
		return $users;
	}
	
	function get_team_requests(){
		$sql =
		'
			SELECT team.*
			FROM user__register__team AS urt, team
			WHERE team.id = urt.team_id
				AND user_id = '.$this->session_manager('id').'
				AND officer = 1
		';
		
		$query = $this->db->query($sql);
		$teamIds = $query->result_array();
		
		if($teamIds){
			$teamRequest = array();
			foreach($teamIds as $i => $value){
				$users = $this->get_applicants_for_team($value['id']);
				if($users){
					$value[] = $users;
					$teamRequest[] = $value;
				}
			}
			if($teamRequest){
				return $teamRequest;
			}
		}
		return false;
	}
	
	
	/************** TOUR ADMINISTRATION *************/
	
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
				AND user__register__team.active = 1
				AND user.id = '.$this->session_manager('id').'
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
			FROM team, user__register__team AS urt, user
			WHERE team.id = urt.team_id
				AND urt.user_id = user.id
				AND user.id = '.$this->session_manager('id').'
				AND active = 1
		';
		
		$query = $this->db->query($sql);
		$teamName = $query->result_array();
		if(!empty($teamName)){
			return $teamName;
		}
		return false;
	}
	
	function get_officer_teams($userId){
		$sql = 
		'
			SELECT *
			FROM
			(
				SELECT team.*
				FROM team, user__register__team AS urt, user
				WHERE team.id = urt.team_id
					AND urt.user_id = user.id
					AND user.id = '.$this->session_manager('id').'
					AND active = 1
					AND officer = 1
			) AS teams, user__register__team AS urt, user
			WHERE urt.user_id = user.id
				AND urt.team_id = teams.id
				AND user.id != '.$userId.'
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
		$data = $query->row_array();
		if(!empty($data)){
			return $data;
		}
		return false;
	}
	
	function get_team_member($id){
		$sql =
		'
			SELECT user.*, urt.officer
			FROM user__register__team AS urt, user
			WHERE urt.user_id = user.id
				AND urt.active = 1
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
	
	function get_all_pagination($table, $num, $offset){
		$query = $this->db->get($table, $num, $offset);
		$result = $query->result_array();	
		if($result){
			return $result;
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
					AND bracket.id = '.$activeBracket['id'].'
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
					AND bracket.id = '.$activeBracket['id'].'
					AND team__attend__bracket.position > 0
				ORDER BY team__attend__bracket.position ASC
			';
			$query = $this->db->query($sql);
			$teams = $query->result_array();
			$bracket[] = $activeBracket;
			$bracket[] = $teams;
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
			WHERE start_time < NOW()
				AND end_time > NOW()
				AND arena = '.$arena.'
		';		
		
		$query = $this->db->query($sql);
		$activeBracket = $query->row_array();
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
			WHERE start_time < NOW()
				AND end_time > NOW()
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
		
		if($part == 1){
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
			$gameNames[] = $query->row_array();
			
		}
		if(!empty($gameNames)){
			return $gameNames;
		}
		return false;
	}
		
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
		$userInfo = $this->session_manager();
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
	
	function edit_tournament($bracketId){
		$sql =
		'
			UPDATE bracket
			SET 
				name = "'.$this->input->post('editTourName').'",
				size = "'.$this->input->post('editTourSize').'",
				team_size = "'.$this->input->post('editTourTeamSize').'",
				type = "'.$this->input->post('editTourType').'",
				start_time = "'.$this->input->post('editTourStart').'",
				end_time = "'.$this->input->post('editTourEnd').'"
			WHERE bracket.id = "'.$bracketId.'"
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
	}
	
	//General functions
	function insert($table, $data){
		if($this->db->insert($table, $data)){
			return true;
		}
		return false;
	}
	
	function update($table, $data, $where){
		$this->db->where($where);
		if($this->db->update($table, $data)){
			return true;
		}
		return false;
	} 
	function delete($table, $where){
		if($this->db->delete($table, $where)){
			return true;
		}
		return false;
	}
	
	function select($table, $where, $whereSwitch = false){
		if($whereSwitch){
			$result = $this->db->get_where($table, $where);
			if($result){
				return $result;
			}
		}
		$result = $this->db->get($table); 
		if($result){
			return $result; 
		}
		return false;
	}
	
	//select from join where whereSwitch
	function join($select, $from, $join, $where, $whereSwitch = false){
		$this->select($select);
		$this->db->from($from);
		foreach($join as $index => $condition){
			$this->db->join($index, $condition);
		}
		if($whereSwitch){
			$this->db->where($where);
		}
		$result = $this->db->get(); 
		if($result){
			return $result; 
		}
		return false;
	}

	function session_manager($data = ''){
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
