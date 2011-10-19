<div id="profileContainer">
	<div id="profilesidebar">
	<img src="../img/profiles/123.jpg" class="profileImg" />
	</div>
	<div id="profilemain">
		
		<h1><?php //echo $userdata; ?></h1>
		<p>Username: <?php echo $userdata['username'] ?></p>
		<p>Email: <?php echo $userdata['email'] ?></p>
		<p>Name: <?php echo $userdata['name'] ?></p>
		<p>Teams:
		<?php 
		foreach($teams as $index => $team) {
			echo '<a href="'.base_url().'tour_controller/load_team/'.$team['id'].'">'.$team['name'].'</a><a class="cross delete" href="'.base_url().'tour_controller/leave_team"></a>';
		}
		echo '<a href="#">'.$userdata['username'].'</a>';
		?> 
		</p>
		<p>Born: <?php echo ''; ?></p>
		<?//optionalJ query uploadify with server space, such as forsius.se?>
		<?//twitter API upload, check if picture exist if so delete that picture and add new
		//filetype validation ".jpg", filesize validation
		//check picture: $_SESSION['username']."_profile.jpg" == exist, remove
		//else upload $_SESSION['username']."_profile.jpg"
		//twitter API profile resize if possible, the more the marrier
		
		?>
		
	</div>
</div>
