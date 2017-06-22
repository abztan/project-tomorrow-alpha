<?php
/*
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];
	include_once "_ptrFunctions.php";*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Add</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>

</head>

<style type="text/css">
#search_results {
	padding: 10 10 10 10;
	font-family: 'Roboto Condensed', sans-serif;
	font-size: 14px;
	font-weight: normal;
}
</style>

<body>
<form name='form' action='' method='POST'>
<br/>
<div class="mdl-grid mdl-cell--3-col mdl-cell--stretch mdl-cell" style="background:#E7E4DF;">
	<div class="mdl-textfield mdl-js-textfield" style="background:;">
		<input class="mdl-textfield__input" type="text" id="search" name='search' value="" onkeyup="goSearch()" />
		<label class="mdl-textfield__label" for="search">Enter ID or Names</label>
	</div>
	<div>
	<!--<a onClick="goSearch()" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="search"><i class="material-icons">search</i></a>-->
	</div>
</div>
<div class="mdl-cell mdl-cell--7-col" style="background:#E7E4DF;" id="search_results">
</div>
</form>

<script type="text/javascript">
	document.getElementById('search_results').style.display = "none";

	function goSearch()
	{
		var value = document.getElementById("search").value;
		var base_id = "<?php echo $account_base;?>";
		var xmlhttp = null;

		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_insertvalues.php?command=search_pastor&value='+value+'&base_id='+base_id, true);
		xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.loadSearch(xmlhttp);
		};
		xmlhttp.send(null);
	}

	function loadSearch(xhr){
	    if(xhr.status == 200){
					document.getElementById('search_results').style.display = "";
	        document.getElementById('search_results').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
</body>
</html>

<script type="text/javascript">
</script>
</body>
</html>
