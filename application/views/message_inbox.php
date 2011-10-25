<?php

echo anchor('home/new_message', 'Nytt meddelande');

echo br(2);
if(!empty($messages)){
	foreach($messages as $message){
		echo '<div id="">';
		echo $message['sender'];
		echo nbs(5);
		echo anchor('home/read_message/'.$message['id'], substr($message['message'], 0, 40));
		echo br(1);
		echo '</div>';
	}
}else{
	echo 'Inga meddelanden att visa.';
}
