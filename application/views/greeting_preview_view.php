<div id="compose-friend">
	<h2><?php echo $_SESSION['greeting_song'];?></h2>
	<h3><?php echo $_SESSION['greeting_artist'];?></h3>
</div>
<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">
	<div id="album-background">
		<img src="images/background/<?php echo $_SESSION['greeting_background'];?>" alt="Album">
		<div id="album-cover">
			<img src="<?php echo $_SESSION['greeting_cover'];?>" alt="Cover Image">
			
			<audio controls preload="none" style="width:230px;">
			<source src="<?php echo $_SESSION['greeting_preview']; ?>" type="audio/mp4" />
			<source src="path-to-oga.oga" type="audio/ogg" />

			</audio>
		</div>
		
	</div>
	<div id="card-preview-photo">
		<img src="<?php echo $_SESSION['user_picture'];?>" alt="Profile Photo" class="img-responsive rounded">
	</div>
	<div id="card-preview-text">
		<p><?php echo $_SESSION['greeting_message'];?></p>
		<span class="sign"><?php echo $_SESSION['user_name'];?></span>
	</div>
	<div id="profile-button">
		<a href="friends/pop_song">Pop Song to <?php echo $_SESSION['friend_name'];?></a>
	</div>
</div>