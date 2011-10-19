<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="<?php echo base_url(); ?>js/jquery-1.5.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery-form.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/script.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo base_url(); ?>js/custom-carousel.js" type="text/javascript" language="javascript"></script>
<meta http-equiv="Content-Type" content="text/html" charset="uft-8">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style-1.0.css" type="text/css" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Welcome to CodeIgniter</title>
</head>
<body>
<div id="loginBox">
		<div id="loginFormBox">
	<?php

	echo
	'
		<form id="loginForm" method="post" action="'.base_url().'home/loginsubmit">
			<label class="loginBox" for="username">Username:</label>
			<input class="loginBox" type="text" name="username" />		
			<label class="loginBox" for="password">Password:</label>
			<input class="loginBox" type="password" name="password" />
			<br>
			<input class="loginBtn" type="submit" name="loginsubmit" value="login" />
		</form>
	';

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
				echo form_open('home/loginsubmit', array('id' => 'logoutForm'));
				echo form_submit('logoutsubmit', 'logout');
				echo form_close();
	}
	echo 
	'
		</div>
		<img src="'.base_url().'img/lanfab.png" id="headerImg" />
		 
	
	<div id="nav">
		<a href="'.base_url().'home/tournament">tournaments</a>
		<a href="'.base_url().'home/teams">Lag</a>
		<a href="'.base_url().'home/users">Spelare</a>
		
	
	';
	if($this->session->userdata('logged_in')){
		if($this->session->userdata('authority') == 5){
			echo 
			'
					<a href="'.base_url().'home/page/create_tournament">Skapa ny turnering</a>
				
			';
		}
		echo 
		'
				<a href="'.base_url().'home/page/register_team">Skapa nytt lag</a>
				<a href="'.base_url().'home/profile">Min profil</a>
			</div>
		';
		//ska modellen hämta informationen via controllen eller ska den skickas med direkt till kontrollen som ovanför?, user fråga, säkerhetsfråga	
	}else{
		echo 
		'
				<a href="'.base_url().'home/page/register">registrera</a>
			</div>
		';	
	}
	echo '
		</div>
		<div id="content">
	';
		
