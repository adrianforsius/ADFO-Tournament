<div id="">
	<?php
		echo form_open('home/send_message');
		echo form_label('To:', 'receiver');
		echo form_input('receiver', '');
		echo br(1);
		echo form_textarea('message', '', 50, 70);
		echo form_submit('submit', 'Send!');
		echo form_close();
	?>
</div>