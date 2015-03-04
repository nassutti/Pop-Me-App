
<!DOCTYPE html>
<html lang="en">
<?php $ref = $this->input->server('HTTP_REFERER', TRUE);?>
<head>
	<base href="<?php echo $this->config->site_url(); ?>">
	<meta charset="UTF-8">
	<title>PopMeApp - <?php echo $title;?></title>

	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/style.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="css/colorbox.css" />
	<script src="js/jquery.colorbox.js"></script>

	<link rel="stylesheet" href="css/tinycarousel.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="js/jquery.tinycarousel.js"></script>



</head>
<body>	
	
<!-- WRAPPER -->
<div id="wrapper">

	<!-- TOP BAR -->
	<div id="top-bar">
		<div id="menu">
			<span id="menu-expand" ><img src="images/icon-expand.png" alt="Expand Menu" title="Menu"></span>
			<?php if(!isset($back)){

				echo "<a id='menu-close'  href='".$ref."'><img src='images/icon-back.png' alt='Back' title='Back'></a>";
			}?>
		</div>
		<div id="messages">
			<?php 

				if((isset($messages))&&(sizeof($messages)>0)){
					echo "<a href='friends/activities/received'>";
						echo "<span class='nav-icon'><img src='images/icon-settings-activities-white.png' alt='Messages'></span>";
						echo sizeof($messages)." Unread messages";
					echo "</a>";
				}
			?>
			
		</div>
		<h1><?php echo $title;?></h1>
		
	</div>