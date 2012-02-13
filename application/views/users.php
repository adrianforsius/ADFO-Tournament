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
<div id="main"> 
	<div class="pageHolder">
		<div class="page">
';
$count = 0;
foreach($data as $record => $user){
	if($count%10 == 0){
		echo
		'
			</div>
			<div class="page">
		';
	}
	echo 
	'
		<a href="'.base_url().'home/spelare/'.$user['id'].'">'.$user['username'].'</a>
		<br>	
	';
	$count++;
}
echo
'
	</div>
	</div>
';	
echo '<div class="pagination">'.$pagination.'</div>
</div>';
