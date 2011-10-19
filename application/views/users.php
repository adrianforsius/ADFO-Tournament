<?php

//ajax live sökning vore fucking coolt
echo
'
	<form action="'.base_url().'home/search" method="get">
		<input type="text" name="usersearch" size="8" />
		<input type="submit" name="usersubmit" value="Sök lag" />
	</form>

';
foreach($data as $index => $user){
	echo '<a href="'.base_url().'home/user/'.$user['id'].'">'.$user['username'].'</a>';
	echo '<br>';
}
