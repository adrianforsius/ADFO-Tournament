<?php
class Tour_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	/******************** ADMIN ACTIONS TOURNAMENT ********************/
	
	function get_bracket_position($position, $bracketId){
		$where = array
		(
			'position' => $position,
			'bracket_id' => $bracketId,
		);
		return $this->select('team__attned__bracket', $where);
	}
	
	function place_team($position, $teamId, $bracketId){
		$data = array
		(
			'position' => $position,
		);
		$where = array
		(
			'bracket_id' => $bracketId,
			'team_id' => $teamId,
			'position' => 0,
		);
		return $this->update('team__attend__bracket', $data, $where);
	}	
	
	//Insert tournament
	function advance_team($teamId, $position){
		$data = array
		(
			'team_id' => $teamId, 
			'bracket_id' => $this->input->post('bracketId'), 
			'position'=> $position,
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
		if($this->delete('team__attend__bracket', $where)){
			return $bracketId;
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
		return $this->update('team__attend__bracket', $data, $where);
		
		if($this->db->query($sql)){
			return $bracketId; 
		}
		return false;
	}
	
	
	//Tournament select
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
		return $this->join('team.*', 'team__attend__bracket', $join, $where, 0);
	}
	
	//Tournament update
	function update_team_position($bracketId, $teamId, $position){
		$data = array
		(
			'team_id' => $teamId,
			'position' => $position,
		);
		$where = array
		(
			'bracket_id' => $bracketId,
			'position' => $position
		);
		return $this->update('team__attend__bracket', $data, $where);
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
	
	/******************** USER ACTIONS TOURNAMENT ********************/
	function sign_up_team_to_bracket(){
		$data = array
		(
			'team_id' =>  $this->input->post('tourTeam'),
			'bracket_id' =>  $this->input->post('tourBracketId'),
			'position' => 0,
		);
		
		if ($this->select('team__attend__bracket', $data)) {
			$result = $this->insert('team__attend__bracket', $data);
			return $this->result_check($result);
		}
		return false;
	}
	
	function sign_up_player_to_bracket(){
		$join = array
		(
			'team' => 'team.id = team__attend__bracket.team_id',
		);
		$where = array
		(
			'bracket_id' => $this->input->post('tourBracketId'),
			'team_id' => $this->session_manager('team'),
		);
		$result = $join->select('*', 'team__attend__bracket', $join, $where);
		
		if(!$this->result_contain($result)){
			$result = $this->insert('team__attend__bracket', $where);
			return $this->result_check($result);
		}
		return false;
	}
	
	/******************** TOURNAMENT ********************/
	//TournamentSupervise select
	function get_arena_by_bracket_id($bracketId){
		$where = array
		(
			'id' => $bracketId,
		);
		$bracket = $this->select('bracket', $where, true);
		return $bracket[0]['arena'];
	}
	
	function get_applied_teams($bracketId){
		$join = array
		(
			'team' => 'team.id = team__attend__bracket.team_id',
			'bracket' => 'bracket.id = team__attend__bracket.bracket_id',
		);
		$where = array
		(
			'bracket.id' => $bracketId,
			'team__attend__bracket.position' => 0, 
		);
		$this->join('team.*', 'team__attend__bracket', $join, $where, 1);
		$this->db->order_by('created', 'ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $this->result_contain($result);
	}
		
	function get_bracket_by_id($bracketId){
		$result = $this->get_item_by_id('bracket', $bracketId);
		return result_contain($result);
	}
	
	function get_team_by_bracket_id($bracketId){ 
		$join = array
		(
			'bracket' => 'bracket.id = team__attend__bracket.bracket_id',
			'team' => 'team.id = team__attend__bracket.team_id',
		);
		$where = array
		(
			'bracket.id' => $bracketId,
			'team__attend__bracket.position >' => 0,
		);
		
		$this->join('team.*, team__attend__bracket.*', 'team__attend__bracket', $join, $where, 1);
		$this->db->order_by('team__attend__bracket.position', 'ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $this->result_contain($result);
	}
	
	function get_active_brackets(){
		$this->load->helper('date');
		$where = array
		(
			'start_time <'=> mdate('%Y-%m-%d %H:%m:%i',now()),
			'end_time >' => mdate('%Y-%m-%d %H:%m:%i', now()),
		);
		
		$this->select('bracket', $where, 1);
		$this->db->order_by('arena', 'ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $this->result_contain($result);	
		
	}
	
	function get_game_name(){
		$sql = 
		'
			SELECT game.name, bracket.arena
			FROM game JOIN
			(
				SELECT * 
				FROM (`bracket`) 
				WHERE `start_time` < NOW() 
					AND `end_time` > NOW() 
				ORDER BY `arena` ASC
			) AS bracket
			ON game.id = bracket.game_id
			ORDER BY arena ASC
		';
		
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $this->result_contain($result);
	}
	
	/******************** TEAM ACTIONS ********************/
	//Insert action team
	function create_team($teamName){
		$data = array
		(
			'name' => $teamName,
		);
		if($this->insert('team', $data)){
			$teamId = $this->db->insert_id();
			$data = array
			(
				'team_id' => $teamId,
				'user_id' => $this->session_manager('id'),
				'officer' => 1,
			);
			return $this->insert('user__register__team', $data);
		}
	}
	
	function get_team_member($id){
		$join = array
		(
			'user' => 'user.id = user__register__team.user_id',
		);
		$where = array
		(
			'active' => 1,
			'team_id' => $id,
		); 
		return $this->join('user.*', 'user', $join, $where, 0);
	}
	
	//Delete action team
	function leave_team($teamId){
		$where = array
		(
			'team_id' => $teamId,
			'user_id' => $this->session_manager('id'),
		);
		return $this->delete('user__register__team', $where);
	}
	
	//Insert action team
	function apply_to_team($teamId){
		$data = array
		(
			'user_id' => $this->session_manager('id'),
			'team_id' => $teamId,
			'active' => 0,
		);
		return $this->insert('user__register__team', $data);
	}
	
	//Update action team
	function accept_team_applicant($userId, $teamId){
		$data = array
		(
			'active' => 1,
		);
		$where = array
		(
			'team_id' => $teamId,
			'user_id' => $userId,
		);
		$this->update('user__register__team', $data, $where);
	}

	//Delete action team
	function decline_team_applicant($userId, $teamId){
		$where = array
		(
			'user_id' => $userId,
			'team_id' => $teamId,
			'active' =>  0,
		);
		$this->delete('user__register__team', $userId, $teamId);
	 }
	
	//Delete action team
	function decline_invite_from_team($teamId){
		$data = array
		(
			'active' => 1,
		);
		$where = array
		(
			'user_id' => $this->session_manager('id'),
			'team_id' => $teamId,
			'active' => 2,
		); 
		return $this->update('user__register__team', $data, $where);
	}
	
	//Update action team
	function accept_invite_from_team($teamId){
		$data = array
		(
			'active' => 1,
		);
		$where = array
		(
			'team_id' => $teamId,
			'active' => 2,
		);
		return $this->update('user__register__team', $data, $where);
	}
	
	//Select team
	function is_officer($teamId){
		$where = array
		(
			'user_id' => $this->session_manager('id'),
			'team_id' => $teamId,
			'officer' => 1,
		);
		return $this->select('user__register__team', $where, true);
	}
	
	/******************** INVITES/REQUESTS ********************/
	//Profile select
	function get_team_invites(){
		$where = array
		(
			'user_id' => $this->session_manager('id'),
			'active' => 2, 
		);
		$join = array
		(
			'user__register__team' => 'user__register__team.team_id = team.id',
		);
		return $this->join('team.*', 'team', $join, $where, 0);
	}
	
	//Profile select
	function get_applicants_for_team($teamId){
		$join = array
		(
			'team' => 'user__register__team.team_id = team.id'
		);
		$where = array
		(
			'team_id' => $teamId,
			'active' => 0,
		);
		return $this->join('*', 'user__register__team', $join, $where, 0);
	}
	
	/******************** MISC USER ********************/
	//Subquery FML
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
		$join = array
		(
			'user' => 'user.id = user__register__team.user_id',
			'team' => 'team.id = user__register__team.team_id',
		);
		$where = array
		(
			'user_id' => $this->session_manager('id'),
			'active' => 1,
		);
		return $this->join('team.*', 'team', $join, $where, 0);
	}
	
	function get_officer_teams(){
		$join = array
		(
			'team' => 'team.id = user__register__team.team_id',
			'user' => 'user.id = user__register__team.user_id',
		);
		$where = array
		(
			'user.id' => $this->session_manager('id'),
			'active' => 1,
			'officer' => 1,
		);
		
		return $this->join('team.*', 'user__register__team', $join, $where, 0);
	}
		
	/******************** MISC/GENERAL ********************/	
	function get_item_by_id($item, $id){
		$where = array
		(
			$item.'.id' => $id, 
		);
		return $this->select($item, $where, 0);
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
		
	//General functions
	function insert($table, $data){
		$result = $this->db->insert($table, $data);
		return $this->result_check($result);
	}
	
	function update($table, $data, $where){
		$this->db->where($where);
		$result = $this->db->update($table, $data); 
		return $this->result_check($result);
	} 
	function delete($table, $where){
		$result = $this->db->delete($table, $where);
		return $this->result_check($result);
	}
	
	function select($table, $where, $switch = 0){
		if($switch === 0){
			$query = $this->db->get_where($table, $where);
			$result = $query->result_array();
			if($result){
				return $result;
			}
		}
		if($switch === 1){
			$this->db->from($table);
			$query = $this->db->where($where);
			return;
		}
		$query = $this->db->get($table); 
		$result = $query->result_array();
		return $this->result_contain($result);
	}
	
	//select from join where whereSwitch
	function join($select, $from, $join, $where, $switch = 0){
		$this->db->select($select);
		$this->db->from($from);
		foreach($join as $index => $condition){
			$this->db->join($index, $condition);
		}
		if($switch === 0){
			$this->db->where($where);
		}
		if($switch === 1){
			$this->db->where($where);
			return;
		}
		if($switch === 2){
			return;
		}
		$query = $this->db->get(); 
		$result = $query->result_array();
		return $this->result_contain($result);
	}
	
	function result_contain($result){
		if($result){
			return $result; 
		}
		return false;
	}
	
	function result_check($result){
		if($result){
			return true; 
		}
		return false;
	}

	function session_manager($data = ''){
		if($data == '') {
			$userdata = $this->session->all_userdata();
		} else {
			$userdata = $this->session->userdata($data);
		}
		return $this->result_contain($userdata);
	}
}
