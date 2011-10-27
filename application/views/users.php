<?php

//ajax live sökning vore fucking coolt
/*echo
'
	<form action="'.base_url().'home/search" method="get">
		<input type="text" name="usersearch" size="8" />
		<input type="submit" name="usersubmit" value="Sök lag" />
	</form>

';
* */


echo
'
	<div class="pageHolder">
	<div class="page">
';
$count = 0;
foreach($data as $record => $user){
	if($count%11 == 10){
		echo
		'
			</div>
			<div class="page">
		';
	}
	echo 
	'
		<a href="'.base_url().'home/user/'.$user['id'].'">'.$user['username'].'</a>
		<br>	
	';
	$count++;
}
echo
'
	</div>
	</div>
';	
echo '<div class="pagination">'.$pagination.'</div>';
