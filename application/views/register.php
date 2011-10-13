<?php

echo '<div id="regform">';
echo '<h3>Registerar ny användare</h3>';
$options = array 
				(
					'id' => 'regForm'
				);

echo form_open('tour_controller/create_login', $options);
echo '<p>* Obligatoriskt fält</p>';
echo	form_label('Username: ', 'regusername');
echo '<br>';
echo	form_input('regusername');
echo '<br>';
echo	form_label('Password: ', 'regpassword');
echo '<br>';
echo	form_password('regpassword');
echo '<br>';
echo	form_label('Password Confirmation: ', 'passconf');
echo '<br>';
echo	form_password('passconf');
echo '<br>';
echo	form_label('Name: ', 'regname');
echo '<br>';
echo	form_input('regname');
echo '<br>';
echo	form_label('Lastname: ', 'reglastname');
echo '<br>';
echo	form_input('reglastname');
echo '<br>';
echo	form_label('Email: ', 'regemail');
echo '<br>';
echo	form_input('regemail');
echo '<br>';
echo	form_submit('submitregisterlogin', 'Registrera!');
echo	form_close();
echo '</div>';

/*
if($this->session->userdata('logged_in')) {
				echo 'User logged in as ' . $this->session->userdata('username');
			} else {
				echo 'User not logged in';
			}
		
		//BOF Create user
		echo '<div id="create">';
			echo '<h3>Create A User</h3>';
			echo '<form action="' . site_url('/example/create/') . '" method="post">';
				
				echo '<label for="create_username">Username:</label>';
				echo '<input type="text" id="create_username" name="create_username" value="" /><br />';

				echo '<label for="create_password">Password:</label>';
				echo '<input type="password" id="create_password" name="create_password" value="" /><br />';

				echo '<input type="submit" id="create" name="create" value="Create" />';

			echo '</form>';
		echo '</div>';
		echo '<hr />';
		//EOF Create user

		
		//BOF Login user
		if(!$this->session->userdata('logged_in')) {
			echo '<div id="login">';
				echo '<h3>Login</h3>';
				echo '<form action="' . site_url('/example/login/') . '" method="post">';
					
					echo '<label for="login_username">Username:</label>';
					echo '<input type="text" id="login_username" name="login_username" value="" /><br />';
	
					echo '<label for="login_password">Password:</label>';
					echo '<input type="password" id="login_password" name="login_password" value="" /><br />';
	
					echo '<input type="submit" id="login" name="login" value="Login" />';
	
				echo '</form>';
			echo '</div>';
			echo '<hr />';
		} else {
			echo '<div id="logut">';
				echo '<h3>Logut</h3>';
				echo '<a href="' . site_url('/example/logout/') . '">Click here to logout.</a>';
			echo '</div>';
			echo '<hr />';
			
		}
		//EOF Login user
		
		//BOF User table
		if($this->session->userdata('logged_in')) {
			//Grab user data from database
			$query = $this->db->select('id, username');
			$query = $this->db->get($user_table);
			$user_array = $query->result_array();
			
			if(count($user_array) > 0) {
				echo '<div id="user_table">';
					echo '<h3>User Table</h3>';
					echo '<table>';
						echo '<tr>';
							echo '<th>';
								echo 'ID';
							echo '</th>';
							echo '<th>';
								echo 'Username';
							echo '</th>';
							echo '<th>';
								echo 'Delete';
							echo '</th>';
						echo '</tr>';
						foreach($user_array as $ua) {
							echo '<tr>';
								echo '<td>';
									echo $ua['id'];
								echo '</td>';
								echo '<td>';
									echo $ua['username'];
								echo '</td>';
								echo '<td>';
									echo '<a href="' . site_url('/example/delete/' . $ua['id']) . '" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
								echo '</td>';
							echo '</tr>';
						}
					echo '</table>';
				echo '</div>';
				echo '<hr />';
			}
		}
		//EOF User table
*/
