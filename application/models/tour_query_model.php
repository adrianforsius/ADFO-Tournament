<?php
class Tour_query_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function team_advance($teamId, $position){
		$bracketId = $this->input->post('bracketId');
		$sql =
		'
			INSERT INTO team__attend__bracket
			(team_id, bracket_id, position)
			VALUES('.$teamId.','.$bracketId.','.$position.')
		';
		if($this->db->query($sql)){
			return true;
		}
		return false;
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
			SET position = 0
			WHERE match_id = '.$matchId.'
		';
		if($this->db->query($sql)){
			$bracketInfo = $this->get_bracket_set_by_id($bracketId, 1);
			return $bracketInfo[0][0]['arena']; 
		}
		return false;
		
	}
	
	
	//break up into to functions as in tour_model(CI-style)
	function random_teams($bracketId){
		$bracket = $this->get_bracket_set_by_id($bracketId, 1);
		
		$sql =
		'
			SELECT team.*, team__attend__bracket.position
			FROM team, team__attend__bracket, bracket
			WHERE team.id = team__attend__bracket.team_id
				AND team__attend__bracket.bracket_id = bracket.id
				AND bracket.id = '.$bracketId.'
				AND team__attend__bracket.position != 0
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
