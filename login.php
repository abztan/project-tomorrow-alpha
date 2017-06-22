<?php

	include "_parentFunctions.php";
	include "/_css/bareringtonbear.css";


	if(!isset($_GET['a']))
	  $value = "";
	else if($_GET['a'] == "1")
	  $value = "Invalid user name or password";
	  else if($_GET['a'] == "2")
	  $value = "Session expired, please re-login";
	else
	  $value = "I don't exactly know what to say. Please contact the administrator.";
?>

<?php

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link href="/ICM/_css/flat-ui.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body align ="center">
<img src="_media/icm_logo.png" height="356" width="700"><br/>
<input autofocus class="form-control input-sm" type="text" name="usme" placeholder="Username" id="usme"/>
<input class="form-control input-sm" type="password" name="pard"  placeholder="Password" id="pard" onchange="verify()" /><br/><br/>
<?php echo $value;?>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<font size="2px">This system is currently designed to be compatible with <a href = "http://www.google.com/chrome/">Google Chrome</a> browser, using other browsers may ruin the usability and interface.</font>
</body>

<script src='parent.js'></script>
</html>
