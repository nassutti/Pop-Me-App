<div id="options-bar">
	<?php $param= $this->uri->segment(2);?>
	<a href="friends/greet_a_friend" <?php if($param == "greet_a_friend"){echo "class='active'";}?>>Friends</a>
	<a href="friends/events" <?php if($param == "events"){echo "class='active'";}?>>Events</a>
</div>
