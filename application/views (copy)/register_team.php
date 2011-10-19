<?php
	echo 
	'
		<div id="regTeamForm">
			<h3>Registerar nytt lag</h3>
			<form form action="'.base_url().'tour_controller/create_team" method="post" />
			<label>Team name: </label>
			<br>
			<input name="teamName" type="text" />
			<br>
			<input type="hidden" value="'.$this->session->userdata('id').'" />
			<input name="submitRegisterTeam" type="submit" />
			</form>
		</div>
	';
