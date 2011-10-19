<?php
//$this->load->view('tabs');
echo 
'
	<div>
		<div id="tournament" class="superviseTournament"
';
	if(!empty($bracket[0]['special_image'])){
		echo 'style="background: url(\''.base_url().'img/'.$bracket[0]['special_image'].'\')"';
	}
	echo 
	'
	"> <!--start tournament -->
	';
	$colo = log($bracket[0]['size'],2)+1;
	$base = 0;
	
	//Coloumn loop
	for($i = 1; $i <= $colo; $i++){
		echo '<div class="colo'.' c'.$i.'" style="width: '.(960/$colo).'px">';
		//Player loop (ROWS)
		for($e = 1; $e <= $bracket[0]['size']/pow(2,($i-1));$e++){
			
			
			//Dynamic sizes compromized to an understandable unit
			$playerMargin = ((610-($bracket[0]['size']*(240/pow(2,($colo-2)))))/$bracket[0]['size']);
			$playerHeight = (240/pow(2,($colo-2)));
			
			echo 
			'
				<div 
					class="player" 
					id="mn'.$e.'"
					style="
							font-size: '.(48/$colo).'px;
							width: '.((960/$colo)-30).'px;
							height: '.$playerHeight.'px; 
							margin-top: ';
							
				//If first coloumn
				if($i == 0){
					//echo one margin unit
					echo $playerMargin;
				}else{
					//If first player (ROW)
					if($e == 1){
						echo (($playerMargin*(pow(2,($i-2))+0.5))+($playerHeight*((pow(2, ($i-2))-0.5))));
					}else{
						echo (($playerMargin*(pow(2,($i-1))-0.8))+($playerHeight*(pow(2, ($i-1))-0.8)));
					}
				}
				//Start player div
				echo 'px;">';
				
				foreach($bracket[1] as $index => $value){
					if($value['position'] == ($base+$e)){
						echo
						'	
							<div style="width: 70px; overflow: hidden; height: '.$playerHeight.'px; float: left">
								<p style="font: '.(48/$colo).'px Helvetica, Arial, sans-serif" id="'.($base+$e).'">'.$bracket[1][$index]['name'].' '.($base+$e).'</p>
							</div>
							<div style="float: right">
						';
						
						if($i == 1){
							echo
							'
									<a style="font: '.(48/$colo).'px Helvetica, Arial, sans-serif; float: right" href="'.base_url().'tour_controller/undo_team_position/'.$bracket[1][$index]['match_id'].'/'.$bracket[0]['id'].'">Undo</a>
							';
							
						}else{
							echo
							'
									<a style="font: '.(48/$colo).'px Helvetica, Arial, sans-serif; float: right" href="'.base_url().'tour_controller/delete_team_position/'.$bracket[1][$index]['match_id'].'/'.$bracket[0]['id'].'">Delete</a>
							';
						}
						
						echo
						'
									<input type="hidden" name="matchId_'.($base+$e).'" value="'.$bracket[1][$index]['match_id'].'" />
									<input type="text" name="team_'.($base+$e).'" size="'.(32/$bracket[0]['size']).'" style="height:'.(48/($colo+1)).'px; margin: -3px" />
							</div>
						';
					}
				}
				//Update base
				if($e == $bracket[0]['size']/pow(2,($i-1))){
					$base+=$e;
				}
				echo 
				'
					</div> <!-- end player div -->
				';
				
		}
		echo '</div> <!-- colo -->';
	}
echo
'
		<input type="hidden" name="bracketId" value="'.$bracket[0]['id'].'" />
		<input type="submit" value="updatera poäng" />
		</form>
		<a style="float:right" href="'.base_url().'tour_controller/random_teams/'.$bracket[0]['id'].'">Random teams</a>
	</div> <!--end tournament -->
';

echo
'
	<div id="teamApply" style="clear: both">
';
if(!empty($appliedteam)){
	echo
	'
		<h4>Ansökande lag:</h4	>
		<form action="'.base_url().'tour_controller/place_team" method="post">
	';
	foreach($appliedteam as $index => $teamInfo){
		echo 
			$teamInfo['name'].'
			<br>
			<select name="position_'.$index.'">
		';
		for($i = 0; $i <= $bracket[0]['size']; $i++){
			echo '<option>'.$i.'</option>';
		}
		echo
		'	
			</select>
			<input type="hidden" name="teamId_'.$index.'" value="'.$teamInfo['id'].'" />
			
			<br>
		';
	}
	echo
	'
			<input type="hidden" name="bracketId" value="'.$bracket[0]['id'].'" />
			<input type="submit" value="placera" name="submitTeamPlace" />
		</form>
		</div>
	';
}
echo
'
	</div> <!-- end bracketHolder -->
';
