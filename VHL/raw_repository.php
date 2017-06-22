<?php
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');
  include "../_css/bareringtonbear.css";
?>

<html>
<header>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>
<br/>
<br/>
<br/>
  &nbsp;&nbsp;&nbsp;Year <input placeholder="Year: 2015,2016" type="text" name="year" id="year">
  Batch <input placeholder="Batch: 1,2,3" type="text" name="batch" id="batch">
  Table <select id="type">
    <option value="1">list_hbf_patient</option>
    <option value="2">log_hbf_weekly</option>
    <option value="3">hbf_combined</option>
  </select>
  <button onclick="generate_raw_table()">Search</button><br/>

  <div id="report_landing" onclick="selectText('report_landing')"></div>
  <img src='../_media/301.gif' hidden id='loader'>
  <script>
    function generate_raw_table() {
      document.getElementById("report_landing").style.display="none";
      $('#loader').show();
      var year = document.getElementById("year").value;
      var batch = document.getElementById("batch").value;
      var type = document.getElementById("type").value;

      if (year == "" || batch == "" || type == "") {
        alert("Kindly provide a value for year and batch.");
      }
      else {
        var xmlhttp = null;
      	if(typeof XMLHttpRequest != 'udefined'){
      			xmlhttp = new XMLHttpRequest();

      	}else if(typeof ActiveXObject != 'undefined'){
      			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
      	}else
      			throw new Error('You browser doesn\'t support ajax');

        xmlhttp.open('GET', '_action.php?command=list_raw&year='+year+
        '&batch='+batch+
        '&type='+type, true);
      	xmlhttp.onreadystatechange = function (){
            if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      		window.returnResponse(xmlhttp);
        };
        xmlhttp.send(null);
      }
    }

    function returnResponse(xhr){
        if(xhr.status == 200){
          $('#loader').hide();
          document.getElementById("report_landing").style.display="";
          var result = document.getElementById('report_landing').innerHTML = xhr.responseText;
        }else
            throw new Error('Server has encountered an error\n'+
                'Error code = '+xhr.status);
    }

    function selectText(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
        }
    }
  </script>
</html>
