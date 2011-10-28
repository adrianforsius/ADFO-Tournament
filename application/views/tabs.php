
	<div id="tabs">
		<ul class="tabNavigation">
			<?php
				foreach($gamename as $nameindex => $name){
					echo '<li value="'.$name['arena'].'"><a>'.$name['name'].'</a></li>';
				}
			?>
		</ul>
	</div>
