 <div id="options-bar">
 	<?php $param= $this->uri->segment(3);?>
	<a href="friends/activities/sent" <?php if($param == "sent"){echo "class='active'";}?> >SENT</a>
	<a href="friends/activities/received" <?php if($param == "received"){echo "class='active'";}?> >RECEIVED</a>
</div>


 <div id="canvas-grid">
 	<?php 
 		if(sizeof($history) >0){
			foreach ($history as $value) {
				if(($param == "received") && ($value->msg_status == "Unread")){
					echo "<a href='friends/view_activity/".$value->msg_id."/".$param."'><div class='activity-item unread'>";
				}else{
					echo "<a href='friends/view_activity/".$value->msg_id."/".$param."'><div class='activity-item'>";
				}
	 			
				echo "<span class='activity-picture'><img src='".$value->msg_picture."' alt='User name' class='rounded img-responsive' title='".$value->msg_datetime."'/></span>";
				echo "<span class='activity-name'>".$value->msg_friend."</span>";
				echo "<span class='activity-message'>".substr($value->msg_text, 0, 100)."</span>";
				echo "<span class='activity-cover'>";
				if($value->msg_cover != ""){
					echo "<img src='".$value->msg_cover."' alt='Cover name' class='img-responsive'/>";	
				}else{
					echo "<img src='images/cover_thumb.png' alt='Cover name' class='img-responsive'/>";
				}
				
	 			echo "</span></div></a>";
	 		}
 		}else{
 			echo "<span class='notification'>There are no messages to show.</span>";
 		}
	 ?>

 </div>