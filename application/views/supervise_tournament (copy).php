<?php
echo '<div style="float: left">';
//echo '<pre>';
//print_r($bracket);
//	echo '</pre>';
echo '<div id="tournament">
<form method="post" accept-charset="utf-8" action="'.base_url().'tour_controller/match_stats" />	';
$colo = log($bracket[0]['size'],2);
$base = 0;
	for($i = 0; $i <= $colo; $i++){
		echo '<div class="colo'.' c'.($i+1).'">';
		for($e = 1; $e <= $bracket[0]['size']/pow(2,$i);$e++){
			
				echo 
				'
					<div class="player" id="mn'.$e.'">
						<div class="playerinfo">';
						
				//For each player position go thru the bracket teams array and see if there is any team with that position, if it is insert it.
				foreach($bracket[1] as $index => $value){
					if($value['position'] == ($base+$e)){
						echo
						'
								<p id="'.($base+$e).'">'.$bracket[1][$index]['name'].' '.($base+$e).'</p>
						';
						
						if($i == 0){
							echo
							'
									<a href="'.base_url().'tour_controller/undo_team_position/'.$bracket[1][$index]['match_id'].'/'.$bracket[0]['id'].'">Undo</a>
							';
							
						}else{
							echo
							'
									<a href="'.base_url().'tour_controller/delete_team_position/'.$bracket[1][$index]['match_id'].'/'.$bracket[0]['id'].'">Delete</a>
							';
						}
						
						echo
						'
									<input type="hidden" name="matchId_'.($base+$e).'" value="'.$bracket[1][$index]['match_id'].'" />
									<input type="text" name="team_'.($base+$e).'" size="8" />
						';
					}
				}
				echo
				'				
						</div>
						<div class="frame">
							<img src="" />
						</div>
					</div>
				';
			if($e == $bracket[0]['size']/pow(2,$i)){
				$base+=$e;
			}
		}
		
		echo 
		'
				
			</div>
		';
	}
	
	echo 
	'
		<input type="hidden" name="bracketId" value="'.$bracket[0]['id'].'" />
		<input type="submit" value="updatera poäng" />
		</form>
		</div>
		<div style="float: left"></div>
	';
//echo '<input type="submit" value="updatera" />';
echo '<a style="float:right" href="'.base_url().'tour_controller/random_teams/'.$bracket[0]['id'].'">Random teams</a>';
echo '</div>';
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
	';
}
echo 
'
	</div>
';

