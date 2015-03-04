<div id="compose-friend">
	<h2><?php echo $greeting_song;?></h2>
	<h3><?php echo $greeting_artist;?></h3>
</div>
<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">

</p>
	<div id="album-background">
		<img src="images/background/<?php echo $greeting_background;?>" alt="Album">
		<div id="album-cover">
			
			<?php 
			
					echo "<img src='".$greeting_cover."' alt='Cover name' class='img-responsive'/>";
					if($greeting_preview != ""){
						echo "<audio controls preload='none' style='width:230px;'>";
						echo "<source src='".$greeting_preview."' type='audio/mp4' />";
						echo "<source src='path-to-oga.oga' type='audio/ogg' />	";
					}
				

			?>
			

			</audio>
		</div>
	

	</div>
	<div id="card-preview-photo">
		<img src="<?php echo $user_picture;?>" alt="Profile Photo" class="img-responsive rounded" />
	</div>
	<div id="card-preview-text">
		<p><?php echo $greeting_message;?></p>
		<span class="sign"><?php echo $user_name;?></span>
	</div>

</div>

