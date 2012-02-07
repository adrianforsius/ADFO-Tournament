<?php
echo
'<div id="main"> 
<div id="tourApply">
		<table>
			<tr>
				<td>Name: </td>
				<td class="bigmac bracketName">'.$bracket['name'].'</td>
			</tr>
			<tr>
				<td>Size: </td>
				<td class="bigmac bracketSize">'.$bracket['size'].'</td>
			</tr>
			<tr>
				<td>Team size: </td>
				<td class="bigmac bracketTeamSize">'.$bracket['team_size'].'</td>
			</tr>
			<tr>
				<td>Type: </td>
				<td class="bigmac bracketType">'.$bracket['type'].'</td>
			</tr>
			<tr>
				<td>Start: </td>
				<td class="bigmac bracketStart">'.$bracket['start_time'].'</td>
			</tr>
			<tr>
				<td>End: </td>
				<td class="bigmac bracketEnd">'.$bracket['end_time'].'</td>
			</tr>
		</table>
	</div>
<form id="tourApplyForm" action="'.base_url().'home/sign_up_to_bracket" method="post">
';
if(empty($userInfo['logged_in'])){
	echo
	'
		<input type="text" name="guestName" value="Guest">
		<input type="hidden" name="teamType" value="2">
	';
	
}else{
	if($bracket['team_size'] > 1){
		echo '<select name="tourTeam">';
		foreach($teams as $index => $team) {
			echo '<option value="'.$team['id'].'">'.$team['name'].'</option>';
		}
		echo 
		'
		</select>
		<input type="hidden" name="teamType" value="0">
		';
	}else{
		echo 
		'
		<select name="tourTeam">
			<option value="'.$userInfo['id'].'">'.$userInfo['username'].'</option>
		</select>
		<input type="hidden" name="teamType" value="1">
		';
	}
}
echo
'	
	<input type="hidden" name="tourBracketId" value="'.$bracket['id'].'" />
	<input type="submit" name="submitTourApply" value="Anmäl" />
</form>
';
if(empty($userInfo['logged_in'])){
	
	echo '
	<br>
	<p class="warning">Logga in för att få turneringspoäng</p>';
}
echo '
</div>
';

