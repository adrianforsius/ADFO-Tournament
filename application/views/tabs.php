
	<ul id="tabs">
			<?php
				if(!empty($gamename)){
					foreach($gamename as $nameindex => $name){
						echo '
						<li value="'.$name['arena'].'">'.$name['name'].'</li>
						<br>
						<br>
						';
					}
				}//$name['arena']
			?>
	</ul>
