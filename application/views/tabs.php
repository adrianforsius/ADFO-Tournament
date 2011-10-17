<div id="tabs">
	<ul class="tabNavigation">
		<?php
			foreach($gamename as $nameindex => $name){
				echo '<li><a title="'.$name[0]['arena'].'" >'.$name[0]['name'].'</a></li>';
			}
		?>
	</ul>
	<div id="tournamentinfo">
		<table class="tabTable">
			<tr>
				<td>Name: </td>
				<td class="bigmac bracketName"></td>
			</tr>
			<tr>
				<td>Size: </td>
				<td class="bigmac bracketSize"></td>
			</tr>
			<tr>
				<td>Team size: </td>
				<td class="bigmac bracketTeamSize"></td>
			</tr>
			<tr>
				<td>Type: </td>
				<td class="bigmac bracketType"></td>
			</tr>
			<tr>
				<td>Start: </td>
				<td class="bigmac bracketStart"></td>
			</tr>
			<tr>
				<td>End: </td>
				<td class="bigmac bracketEnd"></td>
			</tr>
		</table>
	</div>
</div>
