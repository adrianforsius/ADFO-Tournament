<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
	}
	
	public function index(){
		$data['lan'] = $this->Tour_model->get_active_lan();
		$data['view'] = 'home';
		$data['events'] = $this->Tour_model->get_all('lan');
		$data['crumbs'] = $this->crumbs();
		$data['ccrumbs'] = $this->breadcrumb->makeURL();
		//$this->tournament();
		$this->load->view('index', $data);
	}
	
	public function userRedirect($data){
		$data['userdata'] = $this->Tour_model->session_manager();
		$data['crumbs'] = $this->crumbs();
		$data['ccrumbs'] = $this->breadcrumb->makeURL();
		$this->load->view('index', $data);
	}
	
	public function error($message = null){
		$data['view'] = 'error';
		$data['message'] = $message;
		$data['crumbs'] = $this->crumbs();
		$data['ccrumbs'] = $this->breadcrumb->makeURL();
		$this->load->view('index', $data);
	}
	
	/*
	 * Function to call the breadcrumbs class included in the libraries.
	 * This is to locate where the user are at all times.
	 * General pages or subpages or non-nagivatable pages are removed. 
	 */
	public function crumbs(){
		foreach($this->uri->segment_array() as $index => $crumb){
			if($index != 1){	
				if($crumb == 'page' || $crumb == 'apply_to_tournament'){
					
				}else{
					$this->breadcrumb->addCrumb($crumb, $crumb);
				}
			}
		}
		return $this->breadcrumb->makeHTML();
	}
	
	/****************** LOAD VIEWS ******************/
	
	public function lan($lanId = null){
		if($lanId != null){
			$brackets = $this->Tour_model->get_brackets($lanId);
		
			$appliedTeams = array();
			$teams = array();
			
			//subquery solution optional
			if(!empty($brackets)){
				foreach($brackets as $index => $bracket){
					$teams[] = $this->Tour_model->get_team_by_bracket_id($bracket['id']);
					$appliedTeams[] = $this->Tour_model->get_applied_teams($bracket['id']);
				}
			}
			$data['tournament'] = array($brackets, $teams, $appliedTeams);
			$data['gamename'] = $this->Tour_model->get_game_name($lanId);
			$data['view'] = 'tournament';
		}else{
			$data['events'] = $this->Tour_model->get_all('lan');
			$data['view'] = 'events';
		}
		$this->userRedirect($data);
	}
 
	public function page($view){
		if($view == 'page'){
			redirect('home/tournament');
		}
		$data['view'] = $view;
		$data['ccrumbs'] = $this->breadcrumb->makeURL;
		$data['crumbs'] = $this->crumbs();
		$this->load->view('index', $data);
	}
	
	public function actions($view){
		$this->logged_in();
		$data['userdata'] = $this->Tour_model->session_manager();
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	public function spelare($id = null){
		if($id == null){
			$config['base_url'] = base_url().'home/uers';
			$config['total_rows'] = $this->db->count_all('user');
			$config['per_page'] = 40;
			$this->pagination->initialize($config);
			$data['data'] = $this->Tour_model->get_all_pagination('user', $config['per_page'], $this->uri->segment(3));
			$data['pagination'] = $this->pagination->create_links();
			$data['view'] = 'users';
			$this->userRedirect($data);
		}else if($this->Tour_model->session_manager('logged_in') && $this->Tour_model->session_manager('id') == $id){
			$this->profil();
		}else{
			$data['view'] = 'user';
			$data['data'] = $this->Tour_model->get_item_by_id('user', $id);
			$this->userRedirect($data);
		}
	}
	
	public function team($id){
		$data['view'] = 'team';
		$data['teamInfo'] = $this->Tour_model->get_item_by_id('team', $id);
		$data['member'] = $this->Tour_model->get_team_member($id);
		$data['officer'] = false;
		$data['isMember'] = false;
		$data['teamRequest'] = '';
		if(!empty($data['member'])){
			foreach($data['member'] as $i => $value){
				if($value['id'] == $this->Tour_model->session_manager('id')){
					$data['isMember'] = true;
				}
				if($value['id'] == $this->Tour_model->session_manager('id') && $value['officer'] == 1){
					$data['officer'] = true;
					$data['teamRequest'] = $this->Tour_model->get_applicants_for_team($id);
				}
			}
		}
		$this->userRedirect($data);
	}
	
	public function teams(){
		$config['base_url'] = base_url().'home/teams';
		$config['total_rows'] = $this->db->count_all('team');
		$config['per_page'] = 40;
		$this->pagination->initialize($config);
		$data['data'] = $this->Tour_model->get_all_pagination('team', $config['per_page'], $this->uri->segment(3));
		$data['pagination'] = $this->pagination->create_links();
		$data['view'] = 'teams';
		
		$this->userRedirect($data);
	}
	
	public function invite($userId){
		$data['userInfo'] = $this->Tour_model->get_item_by_id('user', $userId);
		$data['view'] = 'invite';
		$this->userRedirect($data);
	}
	
	public function profil(){
		$this->logged_in();
		$data['teamInvite'] = $this->Tour_model->get_team_invites();
		$data['userdata'] = $this->Tour_model->session_manager();
		$data['teams'] = $this->Tour_model->get_teams_by_user();
		$data['view'] = 'profile';
		$this->userRedirect($data);
	}
	
	public function edit_profile(){
		$data['view'] = 'edit_profile';
		$this->userRedirect($data);
	}
	
	
	/****************** USER LOGGED IN FUNCS ******************/
	public function apply_to_tournament($bracketId){
		$bracket = $this->Tour_model->get_bracket_by_id($bracketId);
		$data['teams'] = $this->Tour_model->get_teams_by_user_and_size($bracket['team_size']);
		$data['bracket'] = $bracket;
		$data['userInfo'] = $this->Tour_model->session_manager();
		$data['view'] = 'apply_to_tournament';
		$this->userRedirect($data);
	}
	
	public function sign_up_to_bracket(){
		if($this->input->post('teamType') == 0){
			if(!$this->Tour_model->sign_up_team_to_bracket($this->input->post('tourBracketId'))){
				$this->error('Unable to sign up team to bracket');
			}else{
				$this->index();
			}
		}else if($this->input->post('teamType') == 1){
			if(!$this->Tour_model->sign_up_player_to_bracket($this->input->post('tourBracketId'))){
				$this->error('Unable to sign up player to bracket');
			}else{
				$this->index();
			}
		}else if($this->input->post('teamType') == 2){
			$this->Tour_model->sign_up_guest_to_bracket($this->input->post('guestName'));
			$this->index();
		}
	}
	
	public function apply_to_team($teamId){
		$this->logged_in();
		$this->Tour_model->apply_to_team($teamId);
		$this->index();
	}
	
	public function leave_team($teamId){
		if($this->Tour_model->leave_team($teamId)){
			$this->profile();
		}
		$this->error('Unable to leave team');
	}
	
	public function create_team(){
		$this->logged_in();
		$this->form_validation->set_rules
										( 	
											'teamName',
											'Teamname',
											'trim|required|min_length[3]|max_length[50]|xss_clean'
										);
		if ($this->form_validation->run() == false) {
			$this->page('register_team');
		} else {
			$teamName = $this->input->post('teamName');
			if($this->Tour_model->create_team($teamName)){
				$this->index();
			}
			$this->page('register_team');
		}
	}
	
	/************ PROFILE FUNCS ***************/
	
	public function decline_invite_from_team($userId, $teamId){
		$this->logged_in();
		$this->Tour_model->decline_invite_from_team($userId, $teamId);
		$this->profile();
	}
	
	public function accept_invite_from_team($userId, $teamId){
		$this->logged_in($userId);
		$this->Tour_model->accept_invite_from_team($teamId);
		$this->profile();
	}
	
	//ajax function
	public function accept_team_applicant($userId, $teamId){
		$this->logged_in();
		$this->Tour_model->accept_team_applicant($userId, $teamId);
		$this->profile();
	}
	
	public function decline_team_applicant(){
		$this->logged_in();
		$this->Tour_model->decline_team_applicant();
		$this->profile();
	}
			
	
	/****************** MISC IOS ******************/ 
	//Fetch bracket from db, AJAX
	public function get_bracket($ajax, $id = 1){
		$bracket = $this->Tour_model->get_bracket_by_id($id);
		$appliedteam = $this->Tour_model->fetch_applied_team($id);
		$result = array($bracket, $appliedteam);
		if($ajax){
			echo json_encode($result);
		}else{
			return $bracket;
		}
	}
	
	
	
	/****************** USER FUNCS ******************/ 
	//Create new user
	private function logged_in($id = ''){
		if($id != '' && $this->Tour_model->session_manager('id') == $id){
			return;
		}else if($this->Tour_model->session_manager('logged_in')){
			return;
		}
		redirect('home/error', 'refresh');
	}
	
	
	function create_login() {
		$this->form_validation->set_rules
										( 	
											'regusername',
											'Username',
											'trim|required|min_length[3]|alpha_numeric|max_length[50]|xss_clean|alpha_dash'
										);
											
		$this->form_validation->set_rules
										( 	
											'regpassword',
											'Password',
											'trim|required|min_length[5]|alpha_numeric|matches[passconf]|alpha_dash'
										);
		$this->form_validation->set_rules
										(
											'passconf',
											'Password Confirmation',
											'trim|required'
										);
				
		$this->form_validation->set_rules
										(
											'regemail',
											'Email',
											'trim|required|valid_email'
										);
		
		if ($this->form_validation->run() == false) {
			$this->page('register');
		} else {
			//Create account
			$userInfo = array
			(
				'username' => $this->input->post('regusername'),
				'password' => $this->input->post('regpassword'),
				'name' => $this->input->post('regname'),
				'lastname' => $this->input->post('reglastname'),
				'email' => $this->input->post('regemail'),
			);
			
			if($this->simplelogin->create($userInfo)) {
				$this->index();	
			} else {
				$this->error('Unable to create user');			
			}			
		}
	}
	
	//Delete user by id
	function delete($user_id) {
		if($this->simplelogin->delete($user_id)) {
			$this->index();
		}		
		$this->index();
	}
	
	//Login or logout
	function loginsubmit(){
		if($this->input->post('loginsubmit')){
			$this->login();
		}else if($this->input->post('logoutsubmit')){
			$this->logout();
		}
		$crumbs = $this->input->post('crumbs');
		
		//print_r($crumbs);
		redirect($crumbs, 'refresh');
	}
	
	//Login with validation
	function login() {
		$this->form_validation->set_rules
										( 	
											'username',
											'Username',
											'required|max_length[50]|alpha_dash'
										);
											
		$this->form_validation->set_rules
										( 	
											'password',
											'Password',
											'required|max_length[50]|alpha_dash'
										);
				
		if ($this->form_validation->run() == false) {
			$this->index();
		} else {
			if($this->simplelogin->login($this->input->post('username'), $this->input->post('password'))) {
				$this->index();
			} else {
				$this->error('Unable to login');
			}
		}
	}
	
	//Logout
	function logout() {
		$this->simplelogin->logout();
	}
}
