<div id="profileContainer">
	<div id="profilesidebar">
	<img src="../img/profiles/123.jpg" class="profileImg" />
	</div>
	<div id="profilemain">
		<h1><?php echo '<a href="'.base_url().'home/edit_profile/'.$userdata['id'].'">Editera profil</a>'; ?></h1>
		<p>Username: <?php echo $userdata['username'] ?></p>
		<p>Email: <?php //echo $userdata['email'] ?></p>
		<p>Name: <?php echo $userdata['name'] ?></p>
		<p>Teams:
		<?php 
		if($teams){
			foreach($teams as $index => $team) {
				echo '<a href="'.base_url().'home/team/'.$team['id'].'">'.$team['name'].'</a><a class="cross delete" href="'.base_url().'home/leave_team/'.$team['id'].'"></a>';
			}
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
		<div id="requestBox">
			<?php
				if(!empty($teamRequest)){
					foreach($teamRequest as $i => $team){
						echo '<h4>'.$team['name'].'</h4>';
						foreach($team[0] as $e => $player){
							echo 
							'
								<a href="'.base_url().'home/user/'.$player['id'].'">'.$player['username'].'</a>
								<a href="'.base_url().'home/deny_team_applicant/'.$player['id'].'/'.$team['id'].'" class="cross"></a>
								<a href="'.base_url().'home/accept_team_applicant/'.$player['id'].'/'.$team['id'].'" class="accept"></a>
							';
						}
					}
				}
			?>
		</div>
		<div id="inviteBox">
			<?php
				if(!empty($teamInvite)){
					echo '<h2>Team invites</h2>';
					foreach($teamInvite as $i => $value){
						echo
						'
							<a href="'.base_url().'home/team/'.$value['id'].'">'.$value['name'].'</a>
							<a href="'.base_url().'home/accept_invite_from_team/'.$userdata['id'].'/'.$value['id'].'" class="accept"></a>
							<a href="'.base_url().'home/decline_invite_from_team/'.$userdata['id'].'/'.$value['id'].'" class="cross"></a>
							
						';
					}
				}
			?>
		</div>
	</div>
</div>
