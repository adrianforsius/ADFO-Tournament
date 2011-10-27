<?php
echo
'
	<div class="pageHolder">
	<div class="page">
';
$count = 0;
foreach($data as $record => $team){
	if($count%11 == 10){
		echo
		'
			</div>
			<div class="page">
		';
	}
	echo
	'
		<a href="'.base_url().'home/team/'.$team['id'].'">'.$team['name'].'</a>
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
