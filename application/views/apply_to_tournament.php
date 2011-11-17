<?php 
echo
'
<div id="tourApply">
<div id="tournamentinfo">
		<table class="tabTable">
			<tr>
				<td>Name: </td>
				<td class="bigmac bracketName">'.$bracket[0]['name'].'</td>
			</tr>
			<tr>
				<td>Size: </td>
				<td class="bigmac bracketSize">'.$bracket[0]['size'].'</td>
			</tr>
			<tr>
				<td>Team size: </td>
				<td class="bigmac bracketTeamSize">'.$bracket[0]['team_size'].'</td>
			</tr>
			<tr>
				<td>Type: </td>
				<td class="bigmac bracketType">'.$bracket[0]['type'].'</td>
			</tr>
			<tr>
				<td>Start: </td>
				<td class="bigmac bracketStart">'.$bracket[0]['start_time'].'</td>
			</tr>
			<tr>
				<td>End: </td>
				<td class="bigmac bracketEnd">'.$bracket[0]['end_time'].'</td>
			</tr>
		</table>
	</div>
<form id="tourApplyForm" action="'.base_url().'home/sign_up_to_bracket" method="post">
	<select name="tourTeam">
';
if($bracket[0]['team_size'] > 1){
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
		<option value="'.$userInfo['id'].'">'.$userInfo['username'].'</option>
	</select>
	<input type="hidden" name="teamType" value="1">
	';
}
echo
'	
	<input type="hidden" name="tourBracketId" value="'.$bracket[0]['id'].'" />
	<input type="submit" name="submitTourApply" value="Anmäl" />
</form>
</div>
';

