<div id="canvas-grid">
	<!-- NAV BAR -->
			<div id="nav-genres">
				<ul>
					<?php 
					if((isset($_SESSION['friend_genres_list']))&&(sizeof($_SESSION['friend_genres_list'])>0)){
						foreach ($_SESSION['friend_genres_list'] as $value) {
							echo "<li>";
								echo "<a href='friends/choose_a_song/false/".str_replace(" ", "-", mb_strtolower($value->name))."'>";									
									echo $value->name;
								echo "</a>";
							echo "</li>";
						}
					}else{
						echo "<li>";
							echo "<a href='friends/choose_a_song/'>";									
								echo "iTunes Top 40";
							echo "</a>";
						echo "</li>";
						echo "<li>";
							echo "<a href='friends/choose_a_song/false/rock'>";									
								echo "Rock";
							echo "</a>";
						echo "</li>";

						echo "<li>";
							echo "<a href='friends/choose_a_song/false/pop'>";									
								echo "Pop";
							echo "</a>";
						echo "</li>";

						echo "<li>";
							echo "<a href='friends/choose_a_song/false/classic'>";									
								echo "Classic";
							echo "</a>";
						echo "</li>";

						echo "<li>";
							echo "<a href='friends/choose_a_song/false/soul'>";									
								echo "Soul";
							echo "</a>";
						echo "</li>";

						echo "<li>";
							echo "<a href='friends/choose_a_song/false/r-and-b'>";									
								echo "R&B";
							echo "</a>";
						echo "</li>";

						echo "<li>";
							echo "<a href='friends/choose_a_song/false/ethnic'>";									
								echo "Ethnic";
							echo "</a>";
						echo "</li>";
						echo "<li>";
							echo "<a href='friends/choose_a_song/false/electronic'>";									
								echo "Electronic";
							echo "</a>";
						echo "</li>";
					}

					?>
					

					
				</ul>
			</div>

	<!-- ALBUMS -->
	<?php
	

	if(!$search){
		foreach ($albums->feed->entry as $value) {

			$url = "friends/song_preview/".$value->id->attributes->{'im:id'};
		

			echo "<div class='album'>";
				echo "<a href='".$url."'>";
			echo "<div class='album-photo'>";
				echo "<div class='album-name'><h3>".$value->{'im:artist'}->label."</h3>".$value->{'im:name'}->label."</div>";
					echo "<img src='".$value->{'im:image'}[2]->label."' alt='".$value->{'im:name'}->label."' class='img-responsive'>";
				echo "</div>";
				echo "</a>";
			echo "</div>";
		}
	}else{
		foreach ($albums->results as $value) {

			$url = "friends/song_preview/".$value->trackId;
			

			echo "<div class='album'>";
				echo "<a href='".$url."'>";
			echo "<div class='album-photo'>";
				echo "<div class='album-name'><h3>".$value->artistName."</h3>".$value->trackName."</div>";
					echo "<img src='".str_replace("100x100", "600x600", $value->artworkUrl100)."' alt='".$value->collectionName."' class='img-responsive'>";
				echo "</div>";
				echo "</a>";
			echo "</div>";
		}
	}

	

	


	

	?>
	
	<!-- CLEARFIX -->
	<div class="clear"></div>
</div>