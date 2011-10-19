<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
		
	}
	//osv?
	public function edit_tournament($arena){
		if($this->is_admin()){
			$data['view'] = 'edit_tournament';
			$data['bracket'] = $this->Tour_model->get_active_bracket($arena);
			$this->load->view('index', $data);
		}
	}
		
	private function is_admin(){
		if($this->Tour_model->session('logged_in') && $this->Tour_model->session('authority') == 5){
			return true;
		}
		redirect('home/page/not_allowed', 'refresh');
	}
}
