

<div id="compose-friend">
	<img src="<?php echo $_SESSION['friend_picture'];?>" alt="<?php echo $_SESSION['friend_name'];?>">
	<span><h2><?php echo $_SESSION['friend_name'];?></h2></span>
</div>

<div id="canvas-grid">
	<div id="compose-song">
		<div id="compose-song-album">
			<img src="<?php echo $_SESSION['greeting_cover'];?>" alt="<?php echo $_SESSION['greeting_album']?>">
		</div>
		<div id="compose-song-info">
			<p class="song-title"><?php echo $_SESSION['greeting_song'];?></p>
			<p class="song-album"><?php echo $_SESSION['greeting_artist'];?></p>
		</div>
	</div>
	<div id="compose-form">
		<div id="compose-form-photo"> 
			<img src=" <?php echo $_SESSION['user_picture'];?>" alt="My Picture>">
		</div>
		<div id="compose-form-text">
			<form id="compose" method="post" action="friends/customize_greeting_card/<?php echo $_SESSION['friend_id'].'/'.$_SESSION['greeting_resource'];?>">
			<textarea id="textarea" rows="8" cols="30" maxlength="200" name="message" placeholder="Write your personal message here..." value="<?php set_value('nombre')?>"></textarea>
			<div id="compose-form-characters"></div>
			<div id="compose-form-sign"><?php echo $_SESSION['user_name']?></div>
			<?php echo form_error('message', '<div class="alert-danger">', '</div>'); ?>
		</div>
	</div>

	<div class="clear"></div>
	<div id="profile-button">
		<button type="submit" id="continue">Continue</button>
	</div>
	</form>
</div>