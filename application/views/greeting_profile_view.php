<div id="profile-photo">
	<img src="<?php echo $_SESSION['friend_picture']; ?>" alt="<?php echo $_SESSION['friend_name']; ?>" class="img-responsive">
</div>
<!-- CLEARFIX -->
<div class="clear"></div>

<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">
	<div id="profile-artists">
		<h2>Favourite Artists</h2>
		<ul>
			<?php
			foreach ($_SESSION['friend_artist_list'] as $value) {
				$param = str_replace(" ", "-", mb_strtolower($value->name));
				$param = str_replace("&", "and", $param);
				echo "<li><a class='genre-link' href='friends/choose_a_song/false/".$param."'>".$value->name."</a></li>";
			}

			?>
			
		</ul>
	</div>
	<div id="profile-genres">
		<h2>Favourite Genres</h2>
		<ul>
			<?php
			foreach ($_SESSION['friend_genres_list'] as $value) {
				$param = str_replace(" ", "-", mb_strtolower($value->name));
				$param = str_replace("&", "and", $param);
				echo "<li><a class='genre-link' href='friends/choose_a_song/false/".$param."'>".$value->name."</a></li>";
			}

			?>
			
		</ul>
	</div>

				
	<!-- CLEARFIX -->
	<div class="clear"></div>
		<div id="profile-button">
		<a href="friends/choose_a_song">Pop Song to <?php echo $_SESSION['friend_name'];?></a>
	</div>
	<div class="clear"></div>
</div>