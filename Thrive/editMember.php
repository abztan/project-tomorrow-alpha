<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');

include "_ptrFunctions.php";
	
	$notice = "";
	$pid=$_GET['a'];
	$row=getPastorDetails($pid);
	$fname=$row['firstname'];
	$lname=$row['lastname'];
	$mname=$row['middlename'];
	$member=$row['member'];
	$mdate=$row['membershipdate'];
	
	if(isset($_POST['update']))
		{
			$membership=$_POST['member'];
			$membershipdate=$_POST['mdate'];
						
			updatePastorMembership($pid, $membership, $membershipdate, $_SESSION['username']);
			
			$notice =  "You have successfully updated this entry, refreshing page. <span id='counter'>3</span>";
			
			//header('Refresh: 3;url=listPastorBase.php');
		}
	
	if(isset($_POST['back']))
	{		
		header('location: listPastorBase.php');
	}
?>

<html>
<form name='form1' action='' method='POST'>
Pastor Name <br/><?php echo $lname.", ".$fname." ".$mname;  ?>
<br/><br/>
<label>Member</label>
	  <?php
	  $b="";
	  $c="";

	  if($member == "t")
		$b = "selected"; 
	  else if($member == "f")
		$c = "selected"; 
	  else
	    $a = "selected";
	  ?>
<br/>
<select id="member" name="member">
<option disabled>Please Choose</option>
<option <?php echo $b;?> value="t">Member</option>
<option <?php echo $c;?> value="f">Non-Member</option>
</select>
	 <br/>
	
<label>Membership Date</label><br/>
	  <input class="form-control input-sm" type="date" name="mdate" value="<?php echo $mdate;?>">
	  
	 <br/>
	 <br/>
<button class="btn btn-embossed btn-primary" name = "update">Update</button>
<button class="btn btn-embossed btn-primary" name = "back">Back</button>
<br/>
<br/>
<?php echo $notice;?>
</form>

<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=1) {
        location.href = 'listPastorBase.php';
    }
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>

</html>