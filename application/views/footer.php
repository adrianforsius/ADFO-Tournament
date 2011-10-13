<?php

/*
echo'
	<div id="leftArrow" style="width: 20xp; height: 20px; float: right; clear: both; padding: 10px">
		<p>left</p>
	</div>
	<div style="width: 900px; height: 60px">
		<div id="sliderImg" style="width: 500px; height: 60px">
		</div>
	</div>
	<div id="rightArrow" style="width: 20xp; height: 20px; float: right; padding: 10px"> 
		<p>right</p>
	</div>
<br>';
*/

if($this->session->userdata('logged_in')){
	//$this->load->view('controlpanel');
}

echo '
</div><!--content-->
</body>
</html>
';
