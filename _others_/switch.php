
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>pop test</title>
  
  <script type='text/javascript' src='//code.jquery.com/jquery-1.8.3.js'></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>



<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
$( "#dialog" ).hide();
$( "#target" ).click(function() {
      $( "#dialog" ).show();
    $( "#dialog" ).dialog();
});

});//]]>  

function myFunction() {
   window.location.assign("http://localhost/ICM/VHL/headApprovalView.php?a=<?php echo "271";?>");
}
</script>


</head>
<body>
 <div id="dialog">
 <select  onchange="myFunction()">
	<?php
		include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";
	
			$query = getReplacementParticipants_byScore(275);
			$result = pg_query($dbconn, $query);
			
		$participant_pk = $row['id'];
		
		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))                                                                                                                                                
		{
			echo "<option value=$participant_pk >".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</option>";
		}
?>

</select>
</div>
<input type="button" id="target" value="click"/>
  
</body>


</html>

