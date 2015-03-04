<!DOCTYPE html>
<html>
<head>
<title>PopMeApp Landing</title>
<base href="<?php echo $this->config->site_url(); ?>">
<meta charset="UTF-8">
<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
<style type="text/css">
  body{
    background-color: #2b2f3e;
    color: #6d6d6d;
    font-family: "Lato",sans-serif;
    font-size: 22px;
  }

  #status img{
    display: block;
    margin: 0 auto;
    margin-top: 100px;
  }

  #status p{
    color:#fff;
    font-weight: 300;
    text-align: center
  }

  #status p a:link,#status p a:hover{
    color:#fd454e;
    text-decoration: none;
  }
</style>
</head>
<body>

<div id="status">
  <img src="images/logo.png" alt="Logo">

  <p>Your account is disabled for inappropriate use!<br/> Contact us to activate it again<br/><a href="mailto:accounts@popmeapp.com">accounts@popmeapp.com</a> </p>
</div>

</body>
</html>