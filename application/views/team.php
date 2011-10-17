<?php
	echo 
	'
		<p>Team name: '.$teamInfo[0]['name'].'</p>
		<p>Team Id: '.$teamInfo[0]['id'].'</p>
		<p>Team image</p>
		<p>Members:</p>
	';
	foreach($member as $index => $value){
		if($value['officer'] == 1){
			echo '<a href="'.base_url().'tour_controller/load_id/user/'.$value['id'].'" class="officer">'.$value['name'].'</a>';
		}else{
			echo '<a href="'.base_url().'tour_controller/load_id/user/'.$value['id'].'">'.$value['name'].'</a>';
		}
	}
	
	echo 
	'
		<a>apply to team</a>
	';
