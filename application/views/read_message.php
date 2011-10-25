<?php

if($this->session->userdata('username') == $message[0]['receiver']){
	echo 'From: '.$message[0]['sender'];
	echo br(2);

	echo $message[0]['message'];
}else{
	echo 'Och vad håller du på med?';
}
