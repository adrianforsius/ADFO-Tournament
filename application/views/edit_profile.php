<?php
//print_r($userdata);
echo
'
	<form>
		<label for="email">Email: </label>
		<br>
		<input type="text" name="email" value="'.$userdata['email'].'" />
		<br>
		<label for="firstname">Firstname: </label>
		<br>
		<input type="text" name="firstname" value="'.$userdata['name'].'" />
		<br>
		<label for="lastname">Lastname: </label>
		<br>
		<input type="text" name="lastname" value="'.$userdata['lastname'].'" />
		<br>
		<label for="born">Born: </label>
		<br>
		<input type="text" name="born" value="1989-03-31	" />
		<br>
		<input type="submit" value="Ändra profil" name="editProfileSubmit" />
	</form>



'; 
