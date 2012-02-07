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
<title>Lanfabrikens Tournaments!</title>
</head>
<body>
<div id="loginBox">
		<div id="loginFormBox">
	<?php	
	echo 
	'
		</div>
		<div id="headerImg">
			<h1 id="headerText">';
			if(!empty($lan[0]['name'])){ 
				echo $lan[0]['name'];
			}
		echo '</h1>
		</div>
		 
	
	<div id="nav">
		<a href="'.base_url().'home/tournament">Hem</a>
		<a href="'.base_url().'home/users">Spelare</a>
		<a href="'.base_url().'home/events">Andra LAN</a>
		
	
	';
	if($this->session->userdata('logged_in')){
		if($this->session->userdata('authority') == 5){
			/*
			echo 
			'
					<a href="'.base_url().'admin/page/create_tournament">Skapa ny turnering</a>
			';
			*/
			/*echo 
			'
					<a href="'.base_url().'admin/control_panel">Kontroll panel</a>
			';*/
		}
		/*echo 
		'
				<a href="'.base_url().'home/actions/register_team">Skapa nytt lag</a>';
		*/
		echo '
				<a href="'.base_url().'home/profile">Min profil</a>
		';
		//ska modellen hämta informationen via controllen eller ska den skickas med direkt till kontrollen som ovanför?, user fråga, säkerhetsfråga	
	}else{
		echo 
		'
				<a href="'.base_url().'home/page/register">Registrera</a>
		';	
	}
	if($this->session->userdata('logged_in')){
		echo
		'
			<form action="'.base_url().'home/loginsubmit" method="post" id="loginForm">
				<input type="submit" name="logoutsubmit" class="lineBtn" value ="logout" />
			</form>
			<table class="lineTable">
				<tr>
					<td>Du är inloggad som: </td>
					<td>'.$this->session->userdata('username').'</td>
				</tr>
			</table>
			
		';
	}else{
		echo
		'
			<form id="loginForm" method="post" action="'.base_url().'home/loginsubmit">
				<input class="lineBtn" type="submit" name="loginsubmit" value="login" />
				<label for="username">Username:</label>
				<input type="text" name="username" />		
				<label for="password">Password:</label>
				<input type="password" name="password" />
			</form>
		';
	}
	echo '
	</div>
		</div>
		<div id="content">
	';
		
