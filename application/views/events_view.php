<div id="canvas-grid">

	
	<!-- FRIENDS -->
	<?php

	if(sizeof($today_events)>0){
		echo "<div class='event-date'>";
		echo "<span class='date'>".date("M, d")."</span>";
		echo "</div>";
		foreach ($today_events as $value) {

			$url= "friends/friend_profile/".$value->id;

			if(isset($_SESSION['greeting_resource'])){
				$url= "friends/customize_greeting_card/".$value->id;
			}

			echo "<div class='friend'>";
				echo "<a href='".$url."'>";
				echo "<div class='friend-photo friend-birthday'>";
					echo "<div class='birthday-date'>".substr($value->birthday, 3, 5)."</div>";
					echo "<div class='friend-name'><h3>".$value->first_name."</h3></div>";
					echo "<div class='friend-birthday-icon'><img src='images/icon-birthday.png' alt='Birthday icon'/></div>";
					echo "<img src='".$value->picture->data->url."' alt='Jason' class='img-responsive'>";
					echo "</div>";


				echo "</a>";
			echo "</div>";
			
		}
		echo "<div class='clear'></div>";
	}

	

	if(sizeof($week_events)>0){
		echo "<div class='event-date'>";
		echo "<span class='date'>This Week</span>";
		echo "</div>";
		foreach ($week_events as $value) {

			$url= "friends/friend_profile/".$value->id;

			if(isset($_SESSION['greeting_resource'])){
				$url= "friends/customize_greeting_card/".$value->id;
			}

			echo "<div class='friend'>";
				echo "<a href='".$url."'>";
				echo "<div class='friend-photo friend-birthday'>";
					echo "<div class='birthday-date'>".substr($value->birthday, 3, 5)."</div>";
					echo "<div class='friend-name'><h3>".$value->first_name."</h3></div>";
					echo "<div class='friend-birthday-icon'><img src='images/icon-birthday.png' alt='Birthday icon'/></div>";
					echo "<img src='".$value->picture->data->url."' alt='Jason' class='img-responsive'>";
					echo "</div>";


				echo "</a>";
			echo "</div>";
			
		}
		echo "<div class='clear'></div>";
	}

	if(sizeof($month_events)>0){
		echo "<div class='event-date'>";
		echo "<span class='date'>This month</span>";
		echo "</div>";
		foreach ($month_events as $value) {

			$url= "friends/friend_profile/".$value->id;

			if(isset($_SESSION['greeting_resource'])){
				$url= "friends/customize_greeting_card/".$value->id;
			}

			echo "<div class='friend'>";
				echo "<a href='".$url."'>";
				echo "<div class='friend-photo friend-birthday'>";
					echo "<div class='birthday-date'>".substr($value->birthday,3, 5)."</div>";
					echo "<div class='friend-name'><h3>".$value->first_name."</h3></div>";
					echo "<div class='friend-birthday-icon'><img src='images/icon-birthday.png' alt='Birthday icon'/></div>";
					echo "<img src='".$value->picture->data->url."' alt='Jason' class='img-responsive'>";
					echo "</div>";


				echo "</a>";
			echo "</div>";
			
		}
		echo "<div class='clear'></div>";
	}



	

	?>
	
	<!-- CLEARFIX -->
	<div class="clear"></div>
</div>