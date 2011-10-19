<?php
echo
'
	<table>
		
	
';

foreach($data as $index => $team){
	echo
	'
		<tr>
			<td><a href="'.base_url().'tour_controller/load_team/'.$team['id'].'">'.$team['name'].'</a></td>
		</tr>
	';
}
echo
'
	</table>
';
