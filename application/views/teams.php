<?php
echo
'
	<table>
		
	
';

foreach($data as $index => $team){
	echo
	'
		<tr>
			<td><a href="'.base_url().'home/team/'.$team['id'].'">'.$team['name'].'</a></td>
		</tr>
	';
}
echo
'
	</table>
';
