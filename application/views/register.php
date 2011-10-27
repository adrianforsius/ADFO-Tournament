<?php

echo 
'
<div id="regform">
	<h3>Registerar ny användare</h3>
	<from action="'.base_url().'home/create_login" method="post" id="regForm">
		<label for="regusername">Username: </label>
		<br>
		<input type="text" name="regusername" /> 
		<br>
		<label for="regpassword">Pasword: </label>
		<br>
		<input type="password" name="regpassword" /> 
		<br>
		<label for="passconf">Confirm password: </label>
		<br>
		<input type="password" name="passconf" /> 
		<br>
		<label for="regname">Name: </label>
		<br>
		<input type="text" name="regname" /> 
		<br>
		<label for="reglastname">Lastname: </label>
		<br>
		<input type="text" name="reglastname" /> 
		<br>
		<label for="regemail">Email: </label>
		<br>
		<input type="text" name="regemail" /> 
		<br>
		<input type="submit" name="regloginregister" />
		<br>	
	</from>
</div>
';
