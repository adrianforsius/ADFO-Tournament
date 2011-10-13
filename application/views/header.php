<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="<?php echo base_url(); ?>js/jquery-1.5.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery-form.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/script.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/custom-carousel.js" type="text/javascript" language="javascript"></script>
<meta http-equiv="Content-Type" content="text/html" charset="uft-8">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Welcome to CodeIgniter</title>
</head>
<body>
<div id="content">
	<div id="loginBox">
		<div id="loginFormBox">
	<?php
	$options = array 
				(
					'id' => 'loginForm'
				);
	echo form_open('tour_controller/loginsubmit', $options);		
	echo form_label('Username: ', 'username');
	echo form_input('username');
	echo form_label('Password: ', 'password');
	echo form_password('password');
	echo form_submit('loginsubmit', 'login');
	echo form_close();
		
	echo 
	'
	<div id="nav">
		<a href="'.base_url().'tour_controller/load_tournament">tournaments</a>
		<a href="'.base_url().'tour_controller/load_view/register_team">Skapa nytt lag</a>
		<a href="'.base_url().'tour_controller/load_all/team/teams">Lag</a>
		<a href="'.base_url().'tour_controller/load_all/user/users">Spelare</a>
		
	
	';
	if($this->session->userdata('logged_in')){
		if($this->session->userdata('authority') == 5){
			echo 
			'
					<a href="'.base_url().'tour_controller/load_view/edit_tournament">Editera turnering</a>
					<a href="'.base_url().'tour_controller/load_view/create_tournament">Skapa ny turnering</a>
					<a href="'.base_url().'tour_controller/load_profile">Min profil</a>
				</div>
			';
		}else{
			echo 
			'
					<a href="'.base_url().'tour_controller/load_profile">Min profil</a>
				</div>
			';
		}
		//ska modellen hämta informationen via controllen eller ska den skickas med direkt till kontrollen som ovanför?, user fråga, säkerhetsfråga	
	}else{
		echo 
		'
				<a href="'.base_url().'tour_controller/load_view/register">registrera</a>
			</div>
		';	
	}

	echo 
	'
		</div>
			<div id="loginUserBox">
	';
	
	if($this->session->userdata('logged_in')){
				echo 
				'
					<p>Du är inloggad som: '.$this->session->userdata('username').'</p>
					<p>Vunna matcher: </p>
					<p>Vunna turneringar: </p>
				';
				echo form_open('tour_controller/loginsubmit', array('id' => 'logoutForm'));
				echo form_submit('logoutsubmit', 'logout');
				echo form_close();
	}
	echo 
	'
		</div>
	';
		
	echo 
	'
		</div>
	';
		
