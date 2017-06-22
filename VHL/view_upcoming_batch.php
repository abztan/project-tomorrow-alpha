<?php
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="viewCommunity.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$entry_id = $_GET['a'];
	$sort_by1 = "last_name, first_name";
	$sort_order = "ASC";
	if(isset($_GET['b']))
	{
		if($_GET['b']==1)
		{
			$sort_by1 = "last_name, first_name";
		}
		else if($_GET['b']==2)
		{
			$sort_by1 = "participant_id";
		}
		else if($_GET['b']==3)
		{
			$sort_by1 = "variable2";
			$sort_order = "DESC";
		}
	}
	$notice="";
	$count="1";
	$hidden="";

	$row = getApplicationDetails($entry_id);
	$base_id = $row['base_id'];
	$application_tag = $row['tag'];
	$application_id = $row['application_id'];
	$community_id = $row['community_id'];
	$location = $row['application_province'].", ".$row['application_city'].", ".$row['application_barangay'];
	$base = getBaseName($base_id);

	if($base_id == '1')
		$ishidden="";
	else
		$ishidden="hidden";

	//if admin - extra view
	if($accesslevel == "5")
	{
		$hidden = "hidden";
	}
	else
	{
		$hidden = "";
	}

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/list_upcoming_batch.php');
	}

	if(isset($_POST['gen']))
	{
			$query1 = getParticipantTag($entry_id,2,'last_name','ASC');
			$result1 = pg_query($dbconn, $query1);

			while($row1=pg_fetch_array($result1,NULL,PGSQL_BOTH)) {
				$participant_tag = $row1['tag'];
				$participant_pk = $row1['id'];
				$participant_id = $row1['participant_id'];

				if($participant_id == "") {
					$row2 = getApplicationDetails($entry_id);
					$comm_id = $row2['community_id'];
					$participant_id = generateParticipantID($entry_id,$participant_pk,$comm_id);
					updateApplication_ParticipantID($participant_pk,$participant_id,$username);
				}
			}
			$notice =  "Generating IDs, refreshing page in... <span id='counter'>3</span>";
			$address = "view_upcoming_batch.php?a=$entry_id";
	}

	include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>
<header>
  <h2>Community View</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>


<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>
<?php if($accesslevel == 99 || $accesslevel == 6)?>
<table>
	<th colspan = "2" style="text-align: left;">Community Information</th>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	<tr><td>Pastor</td><td>
		<?php
		if($row['pastor_id'] != 0)
		{
			echo "<a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a>";
		}

		else
		{
			echo $row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial'];
		}
		?></td></tr>
	<tr><td>Community ID</td><td><?= $community_id?></td></tr>
	<tr><td>Application ID</td><td><?= $application_id?></td></tr>
	<tr><td>Location</td><td><?= $location?></td></tr>
	<tr><td>Note</td><td><textarea style='font-size: 15px;width: 100%;'id='comm_note'><?= $row['location_note'];?></textarea></td></tr>
	<tr><td colspan="2" align="right"><span id="message"></span>&nbsp;&nbsp;<button id='update_note' onclick='update_comm_note()'>Update</button></td></tr>
</table>

<br/>
<a href="addPeopleUpcoming.php?a=<?php echo $entry_id; ?>">+People</a>
<br/>
<table border="1">
	<th colspan = "1" style="text-align: center;"><a href="view_upcoming_batch.php?a=<?php echo $entry_id;?>&b=1">Community Participants<a/></th>
	<th colspan = "1" style="text-align: center;"><a href="view_upcoming_batch.php?a=<?php echo $entry_id;?>&b=2">Participant ID / HHID<a/></th>
	<th colspan = "1" style="text-align: center;"><a href="view_upcoming_batch.php?a=<?php echo $entry_id;?>&b=3">Poverty Score<a/></th>
	<th colspan = "1" style="text-align: center;">Action</th>

	<?php
			//$query = getParticipantTag($entry_id,5,$sort_by1,$sort_order);
			$query = getApplicationParticipants($entry_id);
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];
			$participant_id = $row['participant_id'];
			$score = round($row['variable2'],0 );

			echo "<tr>
					<td><a href='viewParticipantPsc.php?a=$entry_id&b=$participant_pk'>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'>$participant_id</td>
					<td align = 'right'>$score</td>
					<td align = 'center'><a onclick='delete_participant($participant_pk)'>Delete</a></td>
					";

				  echo "<!--<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag)'>Dropout</a></td>--></tr>";

			$count++;
		}
		?>

	</table>

<table <?php echo $ishidden;?>>
	<th colspan = "4" style="text-align: left;">Replacement Participants</th>

	<?php
			$query = getParticipantTag($entry_id,2,'last_name, first_name','ASC');
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];
			$participant_id = $row['participant_id'];

			echo "<tr>
					<td><a href='viewParticipantPsc.php?a=$entry_id&b=$participant_pk'>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'>$participant_id</td>";

				  echo "<!--<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag)'>Dropout</a></td>--></tr>";

			$count++;
		}
		?>

	</table>
<br/>
<form name='form1' action='' method='POST'>
<button class="btn btn-embossed btn-primary" name='back'>Back</button>
<button hidden class="btn btn-embossed btn-primary" name='gen' >Generate</button>	</form>
</section>

<section id="col2">
	<!--<table>
	<tr>
	<th colspan = "4" >Dropout Participants</th>
	</tr>

	<?php
			$query = getParticipantTag($entry_id,6);
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			$score = $row['variable2'];
			$score1 = getParticipantScoreNullIncome($participant_pk);

			echo "<tr>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>
					<td align = 'center'><strong><font color ='".scoreHue($score1)."'>$score1</font></strong></td>";

				  echo "<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag)'>Dropout</a></td></tr>";

			$count++;
		}
		?>

	</table>-->

</section>
</article>

<script>
function update_comm_note() {
  var username = '<?php echo $username;?>';
  var application_pk = '<?php echo $entry_id;?>';
	var note = document.getElementById("comm_note").value;
  var xmlhttp = null;

  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

	//window.location.href = '_insertvalues.php?command=update_comm_note&application_pk='+application_pk+'&note='+note+'&username='+username;
	xmlhttp.open('GET', '_insertvalues.php?command=update_comm_note&application_pk='+application_pk+'&note='+note+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
     window.returnResponse(xmlhttp);
  };
  xmlhttp.send(null);
}

function returnResponse(xhr){
    if(xhr.status == 200){
      var result = document.getElementById('message').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function delete_participant(participant_pk) {
	//window.location.href = '_delete_participant.php?a='+participant_pk;

	var message = confirm("Are you sure you want to delete this participant?");
	if(message == true) {
		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_insertvalues.php?command=delete_participant&participant_pk='+participant_pk, true);
	  xmlhttp.onreadystatechange = function (){
	      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
	     location.reload();
	  };
	  xmlhttp.send(null);
	}
}

function overrideStatus(entry_id,participant_pk,tag) {
  var reason = prompt("Please state the reason for participant dropout:");

	if (reason == null)	{
		document.getElementById("notice").innerHTML = "";
	}

  else if (reason != "") {
		 window.location.href = '_applicationaction.php?a=6&b='+entry_id+'&c='+participant_pk+'&d='+reason+'&e='+tag;
  }

	else {
		document.getElementById("notice").innerHTML = "A reason must be provided for participant dropout.";
	}
}
</script>
<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=1) {
        location.href = '<?php echo $address;?>';
    }
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>
</body>

</html>
