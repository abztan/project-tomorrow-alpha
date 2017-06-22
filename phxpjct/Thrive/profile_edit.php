<?php
  include_once "_select_functions.php";
  $pastor_pk = $_GET['pastor_pk'];
  $district_pk = $_GET['district'];
  $count = 1;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../tokyo.css"/>
  </head>

  <body>
    <div>International Care Ministries</div>
    <ul id="menu">
      <li><a href="district_view.php?district=<?=$district_pk;?>">Return</a></li>
      <li><a href="#" onclick="show_profile('view')">View</a></li>
      <li><a href="#" onclick="show_profile('edit')">Edit</a></li>
    </ul>
    <br/>
    <div id="pastor_profile"></div>
  </body>
  <script>
    location.onload(show_profile('view'));

    function show_profile(condition) {
      var pastor_pk = "<?= $pastor_pk;?>";
      var xmlhttp = null;

    	if (typeof XMLHttpRequest != 'udefined'){
    			xmlhttp = new XMLHttpRequest();
    	}else if (typeof ActiveXObject != 'undefined'){
    			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    	}else
    			throw new Error('You browser doesn\'t support ajax');
      //window.location.href = '_action.php?action=get_pastor_profile&pastor_pk='+pastor_pk+'&condition='+condition;
      xmlhttp.open('GET', '_action.php?action=get_pastor_profile&pastor_pk='+pastor_pk+'&condition='+condition, true);
    	xmlhttp.onreadystatechange = function (){
          if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    		window.return_profile(xmlhttp);
      };
      xmlhttp.send(null);
    }

    function return_profile(xhr){
        if (xhr.status == 200){
          document.getElementById('pastor_profile').innerHTML = xhr.responseText;
        }else
            throw new Error('Server has encountered an error\n'+
                'Error code = '+xhr.status);
    }
  </script>
</html>
