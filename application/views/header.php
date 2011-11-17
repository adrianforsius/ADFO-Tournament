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
	if($this->session->userdata('logged_in')){
				echo 
				'
					<table class="stats">
						<tr>
							<td>Du är inloggad som: </td>
							<td>'.$this->session->userdata('username').'</td>
						</tr>
						<tr>
							<td>Vunna matcher: </td>
							<td>';
								if(!empty($matchWins)){
									echo $matchWins.' vunna matcher';
								}
								
							echo '</td>
						</tr>';
						/*
						<tr>
							<td>Vunna turneringar: </td>
							<td>';
								if(!empty($tourWins)){
									echo $tourWins.' vunna matcher';
								}
							echo '</td>
						</tr>*/
						echo '
					</table>
					<table class="stats">
						<tr>
							<td>Ansökanden: </td>
							<td>';
							if(!empty($applys)){
								echo $applys.'st obesvarade ansökningar';
							}
							echo
							'</td>
						</tr>
						<tr>
							<td>Förfrågningar: </td>
							<td>';
							if(!empty($teamRequest)){
								$teams = count($teamRequest[0][0]);
								echo 
								'
									<a href="'.base_url().'home/profile">'.($teams/2).' lag och '.$teams.' förfrågningar </a>
								';
							}
							
							echo'
							</td>
						</tr>
						<tr>
							<td>Invites: </td>
							<td>';
								if(!empty($invites)){
									echo $invites.'st osvarde invites';
								}
								
							echo ' </td>
						</tr>
					</table>
				';
				echo
				'
					<form action="'.base_url().'home/loginsubmit" method="post" id="logoutForm">
						<input type="submit" name="logoutsubmit" class="turnOff" value ="logout" />
					</form>
				';
		
	}else{
		echo
		'
			<form id="loginForm" method="post" action="'.base_url().'home/loginsubmit">
				<input class="loginBtn" type="submit" name="loginsubmit" value="login" />
				<label class="loginBox" for="username">Username:</label>
				<br>
				<input class="loginBox" type="text" name="username" />		
				<br>
				<label class="loginBox" for="password">Password:</label>
				<br>
				<input class="loginBox" type="password" name="password" />
			</form>
		';

	}
	
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
		<a href="'.base_url().'home/tournament">Turneringar</a>
		<a href="'.base_url().'home/teams">Lag</a>
		<a href="'.base_url().'home/users">Spelare</a>
		<a href="'.base_url().'home/events">Events</a>
		
	
	';
	if($this->session->userdata('logged_in')){
		if($this->session->userdata('authority') == 5){
			/*
			echo 
			'
					<a href="'.base_url().'admin/page/create_tournament">Skapa ny turnering</a>
			';
			*/ 
		}
		/*echo 
		'
				<a href="'.base_url().'home/actions/register_team">Skapa nytt lag</a>';
		*/
		echo '
				<a href="'.base_url().'home/profile">Min profil</a>
			</div>
		';
		//ska modellen hämta informationen via controllen eller ska den skickas med direkt till kontrollen som ovanför?, user fråga, säkerhetsfråga	
	}else{
		echo 
		'
				<a href="'.base_url().'home/page/register">Registrera</a>
			</div>
		';	
	}
	echo '
		</div>
		<div id="content">
	';
		
