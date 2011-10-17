<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_controller extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
	}
	
	public function index(){
		redirect('tour_controller/load_tournament', 'refresh');
	}
	
	//Load view improve to clash load tournament and load profile to a smaller functions?
	public function load_view($view){
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	//checka
	public function load_id($item, $id){
		$data['view'] = $item;
		$data['data'] = $this->Tour_model->get_item_by_id($item, $id);
		$this->load->view('index', $data);
	}
	public function load_team($id){
		$data['view'] = 'team';
		$data['teamInfo'] = $this->Tour_model->get_item_by_id('team', $id);
		$data['member'] = $this->Tour_model->get_team_member($id);
		$this->load->view('index', $data);
	}
	
	public function load_all($item, $view){
		$data['view'] = $view;
		$data['data'] = $this->Tour_model->get_all($item);
		$this->load->view('index', $data);
	}
	
	public function load_apply_to_tournament($arena){
		$bracket = $this->Tour_model->get_active_bracket($arena);
		$data['teams'] = $this->Tour_model->get_teams_by_user_and_size($bracket[0]['team_size']);
		$data['bracket'] = $bracket;
		$data['userInfo'] = $this->Tour_model->session();
		$data['view'] = 'apply_to_tournament';
		$this->load->view('index', $data);
	}
	
	//Print admin view of a tournament
	public function load_supervise_tournament($arena){
		if($this->Tour_model->session('authority') == 5 && $this->Tour_model->session('logged_in')){
			$data['bracket'] = $this->fetch_bracket(0,$arena);
			$data['appliedteam'] = $this->Tour_model->fetch_applied_team($arena);
			$data['view'] = 'supervise_tournament';
			$this->load->view('index', $data);
		}else{
			redirect('tour_controller/load_tournament', 'refresh');
		}
	}
	//backup just in case
	//Loads the tournament view with needed data
	/*public function load_tournament(){
		$data['bracket'] = $this->fetch_bracket(false);
		$data['gamename'] = $this->Tour_model->fetch_game_name();
		$data['userdata'] = $this->Tour_model->session();
		$data['appliedteam'] = $this->Tour_model->fetch_applied_team();
		$data['view'] = 'tournament';
		if(!empty($data)){
			$this->load->view('index', $data);
		}else{
			redirect('tour_controller/load_tournament', 'refresh');
		}
	}*/
	
	public function load_tournament(){
		$data['tournament'] = $this->Tour_model->get_bracket_set();
		$data['gamename'] = $this->Tour_model->fetch_game_name();
		$data['userdata'] = $this->Tour_model->session();
		$data['view'] = 'tournament';
		if(!empty($data)){
			$this->load->view('index', $data);
		}else{
			redirect('tour_controller/load_tournament', 'refresh');
		}
	}
	
	public function load_profile(){
		$data['userdata'] = $this->Tour_model->session();
		$data['teams'] = $this->Tour_model->get_teams_by_user();
		$data['view'] = 'profile';
		$this->load->view('index', $data);
	}
	
	//Fetch bracket from db, AJAX
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
	
	//Insert team in between hand for model
	public function sign_up_to_bracket(){
		if($this->input->post('teamType') == 0){
			if($this->Tour_model->sign_up_team_to_bracket()){
				redirect('tour_controller/load_tournament', 'refresh');
			}
		}else if($this->input->post('teamType') == 1){
			if($this->Tour_model->sign_up_player_to_bracket()){
				redirect('tour_controller/load_tournament', 'refresh');
			}
		}
		
		redirect('tour_controller/load_tournament', 'refresh');
	}

	//Calculate match stats for all matches on men arena
	public function match_stats(){
		$arena = $this->Tour_model->match_stats();
		redirect('tour_controller/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Delete team from tournament position
	public function  delete_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->delete_team_position($matchId, $bracketId);
		redirect('tour_controller/supervise_tournament/'.$arena, 'refresh');
	}
	
	public function undo_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->undo_team_position($matchId, $bracketId);
		redirect('tour_controller/supervise_tournament/'.$arena, 'refresh');
	}
	
	public function place_team(){
		$arena = $this->Tour_model->place_team();
		redirect('tour_controller/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Random teams in the beginning of the tournament to make it fair
	public function random_teams($bracketId){
		$this->Tour_model->random_teams($bracketId);
	}
	
	//Create a team from   form
	public function create_team(){
		$this->form_validation->set_rules
										( 	
											'teamName',
											'Teamname',
											'trim|required|min_length[3]|max_length[50]|xss_clean'
										);
		if ($this->form_validation->run() == false) {
			redirect('tour_controller/load_view/register_team');
		} else {
			$teamName = $this->input->post('teamName');
			if($this->Tour_model->create_team($teamName)){
				redirect('tour_controller/load_tournament', 'refresh');
			}
			redirect('tour_controller/load_view/register_team', 'refresh');
		}
	}
	
	
	
	
	//Create new user
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
			redirect('tour_controller/load_view/register');			
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
				redirect('tour_controller/load_tournament');	
			} else {
				echo 'det gick inte att skapa en användare';			
			}			
		}
	}

	//Delete user by id
	function delete($user_id) {
		if($this->simplelogin->delete($user_id)) {
			redirect('/example/');	
		} else {
			redirect('/example/');			
		}			
		
	}
	
	//Login or logout
	function loginsubmit(){
		if($this->input->post('loginsubmit')){
			$this->login();
		}else if($this->input->post('logoutsubmit')){
			$this->logout();
		}
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
			redirect('tour_controller/load_tournament');			
		} else {
			//Create account
			if($this->simplelogin->login($this->input->post('username'), $this->input->post('password'))) {
				redirect('tour_controller/load_tournament', 'refresh');
			} else {
				redirect('tour_controller/load_tournament', 'refresh');	
			}			
		}
	}

	//Logout
	function logout() {
		//Logout
		$this->simplelogin->logout();
		redirect('tour_controller/load_tournament', 'refresh');
	}
}
