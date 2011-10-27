<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
		$this->is_admin();
	}
	
	/****************** ADMIN LOAD VIEWS ******************/
	public function edit_tournament($arena){
		$this->is_admin();
		$data['view'] = 'edit_tournament';
		$data['bracket'] = $this->Tour_model->get_active_bracket($arena);
		$this->load->view('index', $data);
	}
	
	public function supervise_tournament($arena){
		$this->is_admin();
		$data['bracket'] = $this->fetch_bracket(0,$arena);
		$data['appliedteam'] = $this->Tour_model->fetch_applied_team($arena);
		$data['view'] = 'supervise_tournament';
		$this->load->view('index', $data);
	}
	
	public function page($view){
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	/****************** ADMIN GENERAL ******************/
	function update_tournament($bracketId){
		$arena = $this->Tour_model->edit_tournament($bracketId);
		//$arena = $this->Tour_model->get_arena($bracketId);
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}

	function delete_tournament($bracketId){
		$this->Tour_model->delete_tournament($bracketId);
		$this->index();
	}
		
	private function is_admin(){
		if($this->Tour_model->session_manager('logged_in') && $this->Tour_model->session_manager('authority') == 5){
			return;
		}
		$this->page('not_allowed');
	}
	public function fetch_bracket($ajax, $arena = 1){
		$bracket = $this->Tour_model->fetch_bracket($arena);
		$appliedteam = $this->Tour_model->fetch_applied_team($arena);
		$result = array($bracket, $appliedteam);
		if($ajax){
			echo json_encode($result);
		}else{
			return $bracket;
		}
	}
	
	/****************** ADMIN AJAX ******************/
	//Calculate match stats for all matches on men arena
	/*public function match_stats(){
		$arena = $this->Tour_model->match_stats();
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}*/
	
	//Delete team from tournament position
	public function  delete_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->delete_team_position($matchId, $bracketId);
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Remove team from the tournament to applying status
	public function undo_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->undo_team_position($matchId, $bracketId);
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Approve team applicant
	public function place_team(){
		$arena = $this->Tour_model->place_team();
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}
	
	/*
	//Random teams in the beginning of the tournament to make it fair
	public function random_teams($bracketId){
		$arena = $this->Tour_model->random_teams($bracketId);
		redirect('admin/supervise_tournament/'.$arena, 'refresh');
	}
	*/
	
	public function random_teams($bracketId){
		$teams = $this->Tour_model->get_verfied_teams($bracketId);
		print_r($teams);
	}
	
	function match_stats($bracketId, $base, $current = 1){
		$this->is_admin();
		
		$team1Points = $this->input->post('team_'.$current);
		$team2Points = $this->input->post('team_'.($current+1));
	
		$team1Id = $this->input->post('teamId_'.$current);
		$team2Id = $this->input->post('teamId_'.($current+1));
		
		//the jump
		$position = (($base-(floor($current/2)))+$current);
		
		if(!empty($team1Points) && !empty($team2Points) && $team2Points != $team1Points){
				
			if($team1Points > $team2Points){
				$teamId = $team1Id;
			}else{
				$teamId = $team2Id;
			}
			
			$this->Tour_model->advance_team($teamId, $position); 
			$this->Tour_model->update_points($team1Points, $team1Id);
			$this->Tour_model->update_points($team2Points, $team2Id);
		}
		$current+=2;
		if($current < ($base*2)){
			$this->match_stats($bracketId, $base, $current);
		}else{
			redirect('admin/supervise_tournament/1', 'refresh');
		}
	} 
}
