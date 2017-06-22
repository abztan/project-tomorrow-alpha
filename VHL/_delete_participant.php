<?php
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

$participant_pk = $_GET['a'];

	if(isset($_POST['yes']))	{
		deleteParticipant($participant_pk);
		echo "DELETED click <a href = 'checkid.php'>here</a> to go back.<br/>";
	}
?>

<html>
Are you sure you would like to delete this participant? <br/>
<br/>
Warning: Clicking 'Yes' will permanently remove the  participant from the database.<br/>
<br/>
<form name='form1' action='' method='POST'>
<button class="btn btn-embossed btn-primary" name='yes'>Yes</button>
</form>

</html>
