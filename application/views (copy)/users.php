<?php

//ajax live sökning vore fucking coolt
echo
'
	<form action="'.base_url().'tour_controller/search" method="get">
		<input type="text" name="usersearch" size="8" />
		<input type="submit" name="usersubmit" value="Sök lag" />
	</form>

';
foreach($data as $index => $user){
	echo '<a href="'.base_url().'tour_controller/load_id/user/'.$user['id'].'">'.$user['name'].'</a>';
	echo '<br>';
}
