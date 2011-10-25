<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
	}
	
	public function index(){
		redirect('home/tournament', 'refresh');
	}
	
	/****************** LOAD VIEWS ******************/
	
	public function tournament(){
		$data['tournament'] = $this->Tour_model->get_bracket_set();
		$data['gamename'] = $this->Tour_model->fetch_game_name();
		$data['userdata'] = $this->Tour_model->session();
		$data['view'] = 'tournament';
		$this->load->view('index', $data);
	}
 
	public function page($view){
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	public function actions($view){
		$this->logged_in();
		$data['userdata'] = $this->Tour_model->session();
		$data['view'] = $view;
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
	
	public function profile(){
		$data['userdata'] = $this->Tour_model->session();
		$data['teams'] = $this->Tour_model->get_teams_by_user();
		$data['view'] = 'profile';
		$this->load->view('index', $data);
	}
	
	

	/****************** USER LOGGED IN FUNCS ******************/
	public function apply_to_tournament($arena){
		$this->logged_in();
		$bracket = $this->Tour_model->get_active_bracket($arena);
		$data['teams'] = $this->Tour_model->get_teams_by_user_and_size($bracket[0]['team_size']);
		$data['bracket'] = $bracket;
		$data['userInfo'] = $this->Tour_model->session();
		$data['view'] = 'apply_to_tournament';
		$this->load->view('index', $data);
	}
	
	public function sign_up_to_bracket(){
		$this->logged_in();
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
		$this->logged_in();
		$this->Tour_model->apply_to_team($teamId);
		$this->index();
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
	
	
	/****************** USER FUNCS ******************/ 
	//Create new user
	private function logged_in(){
		if($this->Tour_model->session('logged_in')){
			return;
		}
		$this->page('error');
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
				$this->page('error');			
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
	
	public function new_message(){
		$data['view'] = 'new_message';
		$this->load->view('index', $data);
	}
	
	public function message_inbox(){
		$data['messages'] = $this->Tour_model->message_inbox();
		$data['view'] = 'message_inbox';
		$this->load->view('index', $data);
	}
	
	function read_message(){
		$message_to_be_read = $this->uri->segment(3);
		$data['message'] = $this->Tour_model->read_message($message_to_be_read);
		$data['view'] = 'read_message';
		$this->load->view('index', $data);
	}
	
	//Send messages
	public function send_message(){
		$message = array(
						 'sender' => $this->session->userdata('username'),
						 'receiver' => $this->input->post('receiver'),
						 'message' => $this->input->post('message')
						 );
		$this->Tour_model->send_message($message);
		$this->index();
	}
}
