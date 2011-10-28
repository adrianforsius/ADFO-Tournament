<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simplelogin Class
 *
 * Makes authentication simple
 *
 * Simplelogin is released to the public domain
 * (use it however you want to)
 * 
 * Simplelogin expects this database setup
 * (if you are not using this setup you may
 * need to do some tweaking)
 * 

	#This is for a MySQL table
	CREATE TABLE `user` (
	`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`username` VARCHAR( 64 ) NOT NULL ,
	`password` VARCHAR( 64 ) NOT NULL ,
	UNIQUE (
	`username`
	)
	);

 * 
 */
class Simplelogin{
	var $CI;
	var $user_table = 'user';
	//encryption salt
	

	function Simplelogin(){
		// get_instance does not work well in PHP 4
		// you end up with two instances
		// of the CI object and missing data
		// when you call get_instance in the constructor
		//$this->CI =& get_instance();
	}

	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function create($userinfo, $auto_login = true) {
		
		//Put here for PHP 4 user
		$this->CI =& get_instance();		

		//Make sure account info was sent
		if($userinfo['username'] == '' OR $userinfo['password'] == '') {
			return false;
		}
		
		//Check against user table
		//optional
		$this->CI->db->where('username', $userinfo['username']); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) {
			//username already exists
			return false;
			
		} else {
			//Encrypt password
			$userinfo['password'] = $this->CI->encrypt->encode($userinfo['password']);
			//Insert account into the database
			$data = array(
						'username' => $userinfo['username'],
						'password' => $userinfo['password'],
						'name' => $userinfo['name'],
						'lastname' => $userinfo['lastname'],
						'email' => $userinfo['email'],
					);
			$this->CI->db->set($data); 
			if(!$this->CI->db->insert($this->user_table)) {
				//There was a problem!
				return false;						
			}
			$user_id = $this->CI->db->insert_id();
			
			$sql =
			'
				INSERT INTO team (name)
				VALUES( "'.$userinfo['username'].'")
			';
			
			$this->CI->db->query($sql);
			$team_id = $this->CI->db->insert_id();
			
			$sql =
			'
				INSERT INTO user__register__team (user_id, team_id, player)
				VALUES ("'.$user_id.'", "'.$team_id.'", 1)
			';
			$this->CI->db->query($sql);
			
			//Automatically login to created account
			if($auto_login) {		
				//Destroy old session
				$this->CI->session->sess_destroy();
				
				//Create a fresh, brand new session
				$this->CI->session->sess_create();
				
				//Set session data
				$this->CI->session->set_userdata(array('id' => $user_id,'username' => $userinfo['username'], 'authority' => 0, 'team' => $team_id));
				
				//Set logged_in to true
				$this->CI->session->set_userdata(array('logged_in' => true));			
			
			}
			
			//Login was successful			
			return true;
		}

	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id) {
		//Put here for PHP 4 user
		$this->CI =& get_instance();
		
		if(!is_numeric($user_id)) {
			//There was a problem
			return false;			
		}

		if($this->CI->db->delete($this->user_table, array('id' => $user_id))) {
			//Database call was successful, user is deleted
			return true;
		} else {
			//There was a problem
			return false;
		}
	}


	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($user = '', $password = '') {
		
		//Put here for PHP 4 user
		$this->CI =& get_instance();	
		

		//Make sure login info was sent
		if($user == '' OR $password == '') {
			return false;
		}

		//Check if already logged in
		if($this->CI->session->userdata('logged_in') == true) {
			//User is already logged in.
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('username', $user); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) {
			$row = $query->row_array(); 
			
			//Check against password
			if($password != $this->CI->encrypt->decode($row['password'])) {
				return false;
			}
			
			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();
			
			//Remove the password field
			unset($row['password']);
			
			//Set session data
			$this->CI->session->set_userdata($row);
			
			$sql = 
			'
				SELECT team.name
				FROM team, user__register__team, user
				WHERE team.id = user__register__team.team_id
					AND user__register__team.user_id = user.id
					AND user.id = '.$this->CI->session->userdata('id').'
					AND player = 1
			';
			
			$query = $this->CI->db->query($sql);
			$teamName = $query->result_array();
			$this->CI->session->set_userdata(array('team' => $team));
			
			//Set logged_in to true
			$this->CI->session->set_userdata(array('logged_in' => true));			
			
			//Login was successful			
			return true;
		} else {
			//No database result found
			return false;
		}	

	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		//Put here for PHP 4 user
		$this->CI =& get_instance();		

		//Destroy session
		$this->CI->session->sess_destroy();
	}
}
?>
