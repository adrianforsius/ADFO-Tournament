<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
		/*$data['tournament'] = $this->Tour_model->get_bracket_set();
		$data['gamename'] = $this->Tour_model->fetch_game_name();
		$data['userdata'] = $this->Tour_model->session();
		$data['view'] = 'tournament';
		$this->load->view('index', $data)*/;
	}
	
	public function index(){
		redirect('home/tournament', 'refresh');
	}
	
	/****************** LOAD VIEWS ******************/ 
	public function page($view){
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	public function load_user_view($view){
		$data['view'] = $view;
		$data['userInfo'] = $this->Tour_model->session();
		$this->load->view('index', $data);
	}
	
	public function user($id){
		$data['view'] = 'user';
		if($this->Tour_model->session('logged_in') && $this->Tour_model->session('id') == $id){
				redirect('home/profile', 'refresh');
		}else{
			$data['data'] = $this->Tour_model->get_item_by_id('user', $id);
		}
		$this->load->view('index', $data);
	}
	
	public function users(){
		$data['view'] = 'users';
		$data['data'] = $this->Tour_model->get_all('user');
		$this->load->view('index', $data);
	}
	
	public function team($id){
		$data['view'] = 'team';
		$data['teamInfo'] = $this->Tour_model->get_item_by_id('team', $id);
		$data['member'] = $this->Tour_model->get_team_member($id);
		$data['userInfo'] = $this->Tour_model->session();
		$this->load->view('index', $data);
	}
	
	public function teams(){
		$data['view'] = 'teams';
		$data['data'] = $this->Tour_model->get_all('team');
		$this->load->view('index', $data);
	}

	
	public function edit_tournament($arena){
		$data['view'] = 'edit_tournament';
		$data['bracket'] = $this->Tour_model->get_active_bracket($arena);
		$this->load->view('index', $data);
	}
	
	public function apply_to_tournament($arena){
		$bracket = $this->Tour_model->get_active_bracket($arena);
		$data['teams'] = $this->Tour_model->get_teams_by_user_and_size($bracket[0]['team_size']);
		$data['bracket'] = $bracket;
		$data['userInfo'] = $this->Tour_model->session();
		$data['view'] = 'apply_to_tournament';
		$this->load->view('index', $data);
	}
	
	//Print admin view of a tournament
	public function supervise_tournament($arena){
		if($this->Tour_model->session('authority') == 5 && $this->Tour_model->session('logged_in')){
			$data['bracket'] = $this->fetch_bracket(0,$arena);
			$data['appliedteam'] = $this->Tour_model->fetch_applied_team($arena);
			$data['view'] = 'supervise_tournament';
			$this->load->view('index', $data);
		}else{
			$this->index();
		}
	}
	
	public function tournament(){
		$data['tournament'] = $this->Tour_model->get_bracket_set();
		$data['gamename'] = $this->Tour_model->fetch_game_name();
		$data['userdata'] = $this->Tour_model->session();
		$data['view'] = 'tournament';
		$this->load->view('index', $data);
	}
	
	public function profile(){
		$data['userdata'] = $this->Tour_model->session();
		$data['teams'] = $this->Tour_model->get_teams_by_user();
		$data['view'] = 'profile';
		$this->load->view('index', $data);
	}
	
	/****************** MISC IOS ******************/ 
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
	
	//AJAX MARKED FUNCTION
	public function sign_up_to_bracket(){
		//security check
		
		if($this->input->post('teamType') == 0){
			if(!$this->Tour_model->sign_up_team_to_bracket()){
				$this->page('error');
			}
		}else if($this->input->post('teamType') == 1){
			if(!$this->Tour_model->sign_up_player_to_bracket()){
				$this->page('error');
			}
		}
		$this->index();
	}
	
	
	public function apply_to_team($teamId){
		if($this->Tour_model->session('logged_in')){
			$this->Tour_model->apply_to_team($teamId);
		}	
		$this->index();
	}

	//Calculate match stats for all matches on men arena
	public function match_stats(){
		$arena = $this->Tour_model->match_stats();
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Delete team from tournament position
	public function  delete_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->delete_team_position($matchId, $bracketId);
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Remove team from the tournament to applying status
	public function undo_team_position($matchId, $bracketId){
		$arena = $this->Tour_model->undo_team_position($matchId, $bracketId);
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Approve team applicant
	public function place_team(){
		$arena = $this->Tour_model->place_team();
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}
	
	//Random teams in the beginning of the tournament to make it fair
	public function random_teams($bracketId){
		$arena = $this->Tour_model->random_teams($bracketId);
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}
	
	/*function edit_tournament($bracketId){
		$this->Tour_model->edit_tournament($bracketId);
		redirect('home/supervise_tournament/'.$arena, 'refresh');
	}*/
	
	function delete_tournament($bracketId){
		$this->Tour_model->delete_tournament($bracketId);
		$this->index();
	}
	
	//Create a team from form
	public function create_team(){
		$this->form_validation->set_rules
										( 	
											'teamName',
											'Teamname',
											'trim|required|min_length[3]|max_length[50]|xss_clean'
										);
		if ($this->form_validation->run() == false) {
			redirect('home/page/register_team');
		} else {
			$teamName = $this->input->post('teamName');
			if($this->Tour_model->create_team($teamName)){
				$this->index();
			}
			redirect('home/page/register_team', 'refresh');
		}
	}
	
	/****************** USER FUNCS ******************/ 
	//Create new user
	function logged_in(){
		if($this->Tour_model->session('logged_in')){
			return true;
		}
		redirect('home/page/error', 'refresh');
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
			redirect('home/page/register');			
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
			$this->index();			
		} else {
			//Create account
			if($this->simplelogin->login($this->input->post('username'), $this->input->post('password'))) {
				$this->index();
			} else {
				$this->index();	
			}			
		}
	}
	
	
	//Logout
	function logout() {
		$this->simplelogin->logout();
		$this->index();
	}
}
