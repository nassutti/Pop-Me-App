<div id="profile-photo">
	<img src="<?php echo $_SESSION['friend_picture']; ?>" alt="<?php echo $_SESSION['friend_name']; ?>">
</div>
<!-- CLEARFIX -->
<div class="clear"></div>
<!-- CANVAS GRID CONTAINER -->
<div id="canvas-grid">
	<div id="profile-message">
		<?php echo $message;?>
	</div>
	<div id="profile-button">
		<a href="friends/choose_a_song/true">Discover more Music</a>
		<br/>
		<a href="friends/greet_a_friend/true">Pop an other Friend</a>
	</div>
</div>