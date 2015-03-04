

<div id="compose-friend">
	<h2><?php echo $song->results[0]->trackName;?></h2>
	<h3><?php echo $song->results[0]->artistName;?></h3>
</div>
<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">
	<div id="album-background">
		<img src="images/bg-card.jpg" alt="Album">
		<div id="album-cover">
			<img src="<?php echo str_replace("100x100", "600x600", $song->results[0]->artworkUrl100);?>" alt="Cover Image">
			
			<audio controls preload="none" style="width:230px;">
			<?php 
				$url = str_replace('http', 'https', $song->results[0]->previewUrl);
			?>
			<source src="<?php echo $song->results[0]->previewUrl; ?>" type="audio/mp4" />
			<source src="path-to-oga.oga" type="audio/ogg" />

			</audio>
		</div>
		
	</div>
	
	<div id="profile-button">
		<?php 
		$url = "friends/customize_greeting_card/".$song->results[0]->trackId;
			if(!isset($_SESSION['friend_id'])){
				$url = "friends/greet_a_friend/false/".$song->results[0]->trackId;
			}
		?>
		<a href="<?php echo $url; ?>">Continue</a>
	</div>
</div>