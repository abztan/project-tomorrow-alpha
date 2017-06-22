<?php
  include_once "_select_functions.php";
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
      <li><a href="district.php">Return</a></li>
      <li><a href="#" onclick="show_pastor('all','')">All</a></li>
      <li><a href="#" onclick="show_pastor('member','t')">Members</a></li>
      <li><a href="#" onclick="show_pastor('member','f')">Non Members</a></li>
    </ul>
    <div>
      <br/>
      <fieldset>
        <label>District</label> <?= $district_pk; ?>
        <br/>
        <label>Population</label> <?= count_thrive_district_pastor_by_condition($district_pk,"all",""); ?>
        <br/>
        <label>Members</label> <?= count_thrive_district_pastor_by_condition($district_pk,"member","t"); ?>
        <br/>
        <label>Active Pastors</label> <?= count_thrive_district_pastor_by_condition($district_pk,"active","t"); ?>
        <br/>
        <label>Current Program Holders</label> <?= count_thrive_district_pastor_by_condition($district_pk,"active","t"); ?>
      </fieldset>
        <br/>
      <fieldset>
        <div id="pastor_list"></div>
      </fieldset>
    </div>
  </body>
  <script>
    location.onload(show_pastor('all',''));
    
    function show_pastor(condition,value) {
      var district_pk = "<?= $district_pk;?>";
      var xmlhttp = null;

    	if (typeof XMLHttpRequest != 'udefined'){
    			xmlhttp = new XMLHttpRequest();
    	}else if (typeof ActiveXObject != 'undefined'){
    			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    	}else
    			throw new Error('You browser doesn\'t support ajax');
      //window.location.href = '_action.php?action=get_district_pastor&district_pk='+district_pk+'&condition='+condition+'&value='+value;
      xmlhttp.open('GET', '_action.php?action=get_district_pastor&district_pk='+district_pk+'&condition='+condition+'&value='+value, true);
    	xmlhttp.onreadystatechange = function (){
          if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    		window.return_pastor(xmlhttp);
      };
      xmlhttp.send(null);
    }

    function return_pastor(xhr){
        if (xhr.status == 200){
          document.getElementById('pastor_list').innerHTML = xhr.responseText;
        }else
            throw new Error('Server has encountered an error\n'+
                'Error code = '+xhr.status);
    }
  </script>
</html>
