
	<div id="tabs">
		<ul class="tabNavigation">
			<?php
				if(!empty($gamename)){
					foreach($gamename as $nameindex => $name){
						echo '<li value="'.$name['arena'].'"><a>'.$name['name'].'</a></li>';
					}
				}
			?>
		</ul>
	</div>
