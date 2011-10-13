<?php
$this->load->view('tabs');
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<pre>';
print_r($tournament);
echo '</pre>';

foreach($tournament as $tourindex => $bracketSection){
	/*
	if
	(
		$bracket[0]['size'] == 2 ||
		$bracket[0]['size'] == 4 || 
		$bracket[0]['size'] == 8 || 
		$bracket[0]['size'] == 16 ||   
		$bracket[0]['size'] == 32  
	){
		
		echo '<div id="bracketHolder">';
		echo '<div id="tournament">';
		
		$colo = log($bracket[0]['size'],2)+1;
		$base = 0;
		for($i = 0; $i < $colo; $i++){
			echo '<div class="colo'.' c'.($i+1).'">';
			for($e = 1; $e <= $bracket[0]['size']/pow(2,$i);$e++){
			
				echo '<div class="player" id="mn'.$e.'">'; // style="margin-top:'.(($box+10)*$i).'px
				echo '<div class="playerinfo">';
				//loop tru all the teams and check if positions i matching
				//alternative is to have boxarray with exact index, will try this method later
				foreach($bracket[1] as $index => $team){
					if(!empty($bracket[1][$index]) && $bracket[1][$index]['position'] == ($base+$e)){
						echo 
						'
							<p id="'.($base+$e).'">'.$bracket[1][$index]['name'].'</p>';
						if(!empty($bracket[1][$index]['points'])){
							echo 
							'
								<p id="points'.($base+$e).'"> Points: '.$bracket[1][$base+$e-1]['points'].'</p>
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
				if($e == $bracket[0]['size']/pow(2,$i)){
					$base+=$e;
				}
			}
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		
	}
	echo
	'
		<div id="tourFooter">
	';

	if($this->session->userdata('logged_in')){
		echo
		'
				<a>Ansök till turneringen</a>
		';
		if($userdata['authority'] == 5){
			echo '<a class="supervise" href="'.base_url().'tour_controller/supervise_tournament/'.$bracket[0]['arena'].'">Administrera turnering</a>';
		}
	}
	echo
	'
		</div>
		<div id="teamApply" style="clear: both">
		
	';
	if(!empty($appliedteam)){
		foreach($appliedteam as $index => $teamInfo){
			echo '<p>'.$teamInfo['name'].'</p>';
			echo '<br>';
		}
	}
	echo '</div>';
	*/
}
