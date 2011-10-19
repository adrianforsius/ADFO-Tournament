<?php
echo
'
<form action="'.base_url().'home/edit_tournament/'.$bracket[0]['id'].'" method="post">
	<label for="editTourName">Tournament name:</label>
	<br>
	<input type="text" name="editTourName" value="'.$bracket[0]['name'].'" />
	<br>
	<label for="editTourSize">Tournament size:</label>
	<br>
	<input type="text" name="editTourSize" value="'.$bracket[0]['size'].'" />
	<br>
	<label for="editTourTeamSize">Tournament team size</label>
	<br>
	<input type="text" name="editTourTeamSize" value="'.$bracket[0]['team_size'].'" />
	<br>
	<label for="editTourType">Tournament type:</label>
	<br>
	<input type="text" name="editTourType" value="'.$bracket[0]['type'].'" />
	<br>
	<label for="editTourStart">Start: </label>
	<br>
	<input type="text" name="editTourStart" value="'.$bracket[0]['start_time'].'" />
	<br>
	<label for="editTourEnd">End:</label>
	<br>
	<input type="text" name="editTourEnd" value="'.$bracket[0]['end_time'].'" />
	<br>
	<input type="submit" name="editTourSubmit" value="uppdatera"/>
</form>


';
