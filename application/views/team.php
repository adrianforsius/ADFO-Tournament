<?php
	echo 
	'
		<p>Team name: '.$teamInfo['name'].'</p>
		<p>Team Id: '.$teamInfo['id'].'</p>
		<p>Team image:</p>
		<p>Members:</p>
	';
	
	if(!empty($member)){
		foreach($member as $index => $value){
			if($value['officer'] == 1){
				echo '<a href="'.base_url().'home/load_id/user/'.$value['id'].'" class="officer" title="officer">'.$value['username'].'</a>';
			}else{
				echo '<a href="'.base_url().'home/load_id/user/'.$value['id'].'">'.$value['username'].'</a>';
			}
		}
	}
	if(!$isMember){
		echo 
		'
			<a href="'.base_url().'home/apply_to_team/'.$teamInfo['id'].'">apply to team</a>
		';
	}
	echo '<br>';
	echo '<br>';
	if(!empty($teamRequest) && $isMember){
		echo '<h2>Ansökande</h2>';
		foreach($teamRequest[0][0] as $index => $value){
			echo
			'
				<a href="'.base_url().'home/user/'.$value['id'].'">'.$value['username'].'</a>
			';
			if($officer){
				echo
				'
					<a href="'.base_url().'home/accept_team_applicant/'.$value['id'].'" class="accept"></a>
					<a href="'.base_url().'home/decline_team_applicant/'.$value['id'].'" class="cross"></a>
				';
			}
		}
	}
	
