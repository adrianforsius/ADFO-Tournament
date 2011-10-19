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
