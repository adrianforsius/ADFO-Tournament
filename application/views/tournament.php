<?php
$this->load->view('tabs');


echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<pre>';
//print_r($tournament[2]);
echo '</pre>';

foreach($tournament[0] as $tourindex => $bracketInfo){
	if
	(
		$bracketInfo['size'] == 2 ||
		$bracketInfo['size'] == 4 || 
		$bracketInfo['size'] == 8 || 
		$bracketInfo['size'] == 16 ||   
		$bracketInfo['size'] == 32  
	){
		
		
		if($tourindex != 0){
			echo '<div class="bracketHolder arena'.$bracketInfo['arena'].'">';
		}else{
			echo '<div class="bracketHolder currentArena arena'.$bracketInfo['arena'].'">';
		}
		echo '<div id="tournament">';
		
		$colo = log($bracketInfo['size'],2)+1;
		$base = 0;
		for($i = 0; $i < $colo; $i++){
			echo '<div class="colo'.' c'.($i+1).'">';
			for($e = 1; $e <= $bracketInfo['size']/pow(2,$i);$e++){
			
				echo '<div class="player" id="mn'.$e.'">'; // style="margin-top:'.(($box+10)*$i).'px
				echo '<div class="playerinfo">';
				//loop tru all the teams and check if positions i matching
				//alternative is to have boxarray with exact index, will try this method later
				foreach($tournament[1][$tourindex] as $index => $team){
					//print_r($tournament[1][$tourindex][$index]);
					if(!empty($tournament[1][$tourindex]) && $tournament[1][$tourindex][$index]['position'] == ($base+$e)){
						echo 
						'
							<p id="">'.$tournament[1][$tourindex][$index]['name'].'</p>
						';
						
						if(!empty($tournament[1][$tourindex][$index]['points'])){
							echo 
							'
								<p id="points'.($base+$e).'"> Points: '.$tournament[1][$tourindex][$index]['points'].'</p>
							';
						}
					
					}
				}
				echo
				'
					</div>
					<div class="frame">
						<img src="" />
					</div>
				</div>';
				if($e == $bracketInfo['size']/pow(2,$i)){
					$base+=$e;
				}
			}
			echo '</div>';
		}
		echo '</div>';
		
	}
	echo
	'
		<div id="tourFooter">
	';

	if($userdata['logged_in'] == true){
		echo
		'
			<a href="'.base_url().'tour_controller/load_apply_to_tournament/'.$bracketInfo['arena'].'">Ansök till turneringen</a>
		';
		if($userdata['authority'] == 5){
			echo '<a class="supervise" href="'.base_url().'tour_controller/load_supervise_tournament/'.$bracketInfo['arena'].'">Administrera turnering</a>';
		}
	}
	echo
	'
		</div>
		<div id="teamApply" style="clear: both">
		
	';
	if(!empty($tournament[2][$tourindex])){
		echo '<h4>Ansökande lag</h4>';
		foreach($tournament[2][$tourindex] as $teamInfoIndex => $teamInfo){
			echo '<p>'.$teamInfo['name'].'</p>';
			echo '<br>';
		}
	}
	echo '</div>';
	echo '</div>';
}
