<div id="compose-friend">
	<h2><?php echo $_SESSION['greeting_song'];?></h2>
	<h3><?php echo $_SESSION['greeting_artist'];?></h3>
<p style="font-weight:bold; font-size:16px; margin-left:10px; margin-bottom:10px;">Select Background for <?php echo $_SESSION['friend_name']?>'s song</p>
</div>

<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">
	<div id="album-background">
		<div id="background">
			<?php 
				$_SESSION['greeting_background'] = $background[0]->background_image;
				$_SESSION['greeting_background_id'] = $background[0]->background_id;
			?>
			<img src="images/background/<?php echo $background[0]->background_image; ?>" alt="Album" />
		</div>
		<div id="album-cover">
			<img src="<?php echo $_SESSION['greeting_cover'];?>" alt="Cover Image">
			<audio controls preload="none" style="width:230px;">
			<source src="<?php echo $_SESSION['greeting_preview']; ?>" type="audio/mp4" />
			<source src="path-to-oga.oga" type="audio/ogg" />

			</audio>
		</div>
		
	</div>
	<div id="album-background-choose">
		
		<div id="slider1">
			<a class="buttons prev" href="#"><img src="images/prev.png" alt="Prev"></a>
			<div class="viewport">
			<ul class="overview">
				<?php 
				
				foreach ($background as $value) {
						echo "<li data-image='".$value->background_image."' data-id='".$value->background_id."'><img src='images/background/".$value->background_image."' alt='".$value->background_name."'  class='img-responsive'></li>";
					}
				?>
			
			
			</ul>
			</div>
			<a class="buttons next" href="#"><img src="images/next.png" alt="Next"></a>
		</div>
	</div>
	<div id="profile-button">
		<a href="friends/preview_greeting_card" class="small">Preview Pop</a>
	</div>
</div>