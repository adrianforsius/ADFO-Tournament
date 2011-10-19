<?php
	echo 
	'
		<p>Team name: '.$teamInfo[0]['name'].'</p>
		<p>Team Id: '.$teamInfo[0]['id'].'</p>
		<p>Team image:</p>
		<p>Members:</p>
	';
	
	$memberCheck = 0;
	if(!empty($member)){
		foreach($member as $index => $value){
			if($value['officer'] == 1){
				echo '<a href="'.base_url().'home/load_id/user/'.$value['id'].'" class="officer" title="officer">'.$value['username'].'</a>';
			}else{
				echo '<a href="'.base_url().'home/load_id/user/'.$value['id'].'">'.$value['username'].'</a>';
			}
			if(!empty($userInfo['id']) && $value['id'] == $userInfo['id']){
				$memberCheck = 1;
			}
		}
	}
	if($memberCheck == 0){
		echo 
		'
			<a href="'.base_url().'home/apply_to_team/'.$teamInfo[0]['id'].'">apply to team</a>
		';
	}
