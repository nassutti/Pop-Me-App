<div id="canvas-grid">
	<!-- FRIENDS -->
	<?php

	foreach ($_SESSION['friends'] as $value) {

		$url= "friends/friend_profile/".$value->id;

		if(isset($_SESSION['greeting_resource'])){
			$url= "friends/customize_greeting_card/".$value->id;
		}

		echo "<div class='friend'>";
			echo "<a href='".$url."'>";
			echo "<div class='friend-photo'>";
				echo "<div class='friend-name'><h3>".$value->first_name."</h3></div>";
					echo "<img src='".$value->picture->data->url."' alt='".$value->first_name."' class='img-responsive'>";
				echo "</div>";
			echo "</a>";
		echo "</div>";
		
	}


	

	?>
	
	<!-- CLEARFIX -->
	<div class="clear"></div>
</div>