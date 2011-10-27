<?php
echo
'
<form action="'.base_url().'home/update_tournament/'.$bracket['id'].'" method="post">
	<label for="editTourName">Tournament name:</label>
	<br>
	<input type="text" name="editTourName" value="'.$bracket['name'].'" />
	<br>
	<label for="editTourSize">Tournament size:</label>
	<br>
	<input type="text" name="editTourSize" value="'.$bracket['size'].'" />
	<br>
	<label for="editTourTeamSize">Tournament team size</label>
	<br>
	<input type="text" name="editTourTeamSize" value="'.$bracket['team_size'].'" />
	<br>
	<label for="editTourType">Tournament type:</label>
	<br>
	<input type="text" name="editTourType" value="'.$bracket['type'].'" />
	<br>
	<label for="editTourStart">Start: </label>
	<br>
	<input type="text" name="editTourStart" value="'.$bracket['start_time'].'" />
	<br>
	<label for="editTourEnd">End:</label>
	<br>
	<input type="text" name="editTourEnd" value="'.$bracket['end_time'].'" />
	<br>
	<input type="submit" name="editTourSubmit" value="uppdatera"/>
</form>


';
