<?php
foreach($data as $index => $team){
	echo '<a href="'.base_url().'tour_controller/load_team/'.$team['id'].'">'.$team['name'].'</a>';
	echo '<br>';
}
