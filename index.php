<? 
require_once('include/request.php')

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
	<link type="text/css" rel="stylesheet" href="/css/style.css">
    <script  type="text/javascript" src="/js/jquery.min.js"></script>
	<script  type="text/javascript" src="/js/functions.js"></script>
	
	<script  type="text/javascript" >
	  $(document).ready(function () {
		 $("#myformurl").on("submit", request_data);
	  });
	</script>
	
</head>
<body>

 
	
	<div class="rform">
	   <form method="post" action="" id="myformurl">
		    <label class="labellink">введите ссылку: <label/>
		   <input name="link" type="text"  class="inputlink" placeholder = "введите ссылку"/><br>
		   <input type="submit" value="получить ссылку" class="btn"/><br>
		   <span class="messenger"><span/>
	   </form>
	</div>
	 <br>
	<div id ="show" >
	  <? require_once('include/show.php') ?>
	</div>
	<br>
</body>
</html>
