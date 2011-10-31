<?php
echo 
'
	<h2>Invite '.$userInfo['name'].' AKA '.$userInfo['username'].' to team</h2>
	
	<from action="'.base_url().'home/invite_user" method="post">
		<option>
';
	foreach($teams as $index => $team){
		echo '<select value="'.$team['id'].'">'.$team['name'].'</select>';
	}	
echo 
'
		</option>
		<input type="submit" name="inviteSubmit" value="invite"/>
	</from>
';
