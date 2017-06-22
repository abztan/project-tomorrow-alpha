<link href="/ICM/_css/flat-ui.css" rel="stylesheet">
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
<!--Google Material Design
<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.teal-red.min.css" />
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<?php
$accesslevel = $_SESSION['accesslevel'];
$username = $_SESSION['username'];

$a="0";
$b="0";
$b1="hidden";
$b2="hidden";
$b3="hidden";
$b4="hidden";
$b5="hidden";
$b6="hidden";
$pastor="0";
$c="0";
$d="0";
$pin="0";

$pastor1="";
$pastor2="";
$pastor3="";
$pastor4="hidden";
$pin1="";
$pin2="";

if($accesslevel == "99") //admin
{
	$a="1";
	$b="1";
	$b1="";
	$b2="";
	$b3="";
	$b4="";
	$b5="";
	$b6="";
	$pastor="1";
	$pastor1="";
	$pastor2="";
	$pastor3="";
	$c="1";
	$pin="1";
	$d="1";
}

else if($accesslevel == "1") //power user
{
	$a="1";
	$b="1";
	$b1="";
	$b2="";
	$b3="";
	$b4="";
	$b5="";
	$b6="";
	$pastor3="";
	$pastor="1";
	$pastor4="";
	$c="1";
	$pin="1";
}

else if($accesslevel == "2") //head access
{
	$a="0";
	$b="1";
	$pastor="1";
	$pastor3="hidden";
}

else if($accesslevel == "3") //pastor profile encoder
{
	$b="1";
	$b4="";
	$pastor="1";
	$pastor1="";
}

else if($accesslevel== "4")
{
	$pastor="1";
	$pin="1";
	$pastor3="hidden";
}

else if($accesslevel == "5") //transform encoder
{
	$b="1";
	$b1="";
	$b2="";
	$b3="";
	$b5="";
	$b6="";
	$pastor3="hidden";
}

else if($accesslevel == "13")
{
	$b="1";
	$b5="";
	$pastor3="hidden";
}

else if($accesslevel == "6") //area head
{
	$a="0";
	$b="1";
	$b1="hidden";
	$b2="hidden";
	$b3="hidden";
	$b4="";
	$b5="";
	$b6="";
	$pastor="0";
	$pastor3="hidden";
	$pastor="1";
	$pastor1="";
	$pastor2="hidden";
	$c="0";
	$d="0";
	$pin="0";
}

else if($accesslevel == "7") //thrive leader
{
	$pastor="1";
	$pastor1="1";
	$pastor2="";
	$pastor3="";
	$pastor4="";
}

else if($accesslevel == "8") //Thrive Admin (Jojo, Mae, Jasher)
{
	$b="1";
	$b5="";
	$b6="";
	$pastor="1";
	$pastor1="";
	$pastor2="";
	$pastor3="";
	$pastor4="";
}

else if($accesslevel == "9") //network head
{
	$pastor="1";
	$pastor1="";
	$pastor2="";
	$pastor3="";
	$pastor4="";
}

else if($accesslevel == "10") //external and boards
{
	$pastor="1";
	$pastor1="";
	$pastor2="hidden";
	$pastor3="hidden";
}

else if($accesslevel == "11") //external and boards
{
	$b="1";
	$b5="";
	$b6="";
	$pastor="1";
	$pastor1="";
	$pastor2="hidden";
	$pastor3="hidden";
}

else if($accesslevel == "14") //external and boards
{
	$b="1";
	$b5="";
}

else if($accesslevel == "15") //junior editor
{
	$b="1";
	$pastor="1";
	$pastor1="";
}
//include "_css/bareringtonbear.css";

echo '
<ul id="nav">
  <li>
		<a href="/ICM/index.php">HOME</a>
  </li>';

/*if($a == "1")
echo '
  <li><a href="#">Communities</a>
		<ul>
		<li><a href="/ICM/Transform/View.php">List</a></li>
		<li><a href="/ICM/Transform/addCommunity.php">Add</a></li>
		</ul>
  </li>';*/

 if($b == "1")
echo '
  <li><a href="/ICM/VHL/indexTransform.php">VHL</a>
		<ul id="sublist">
		<li '.$b1.'><a href="/ICM/VHL/listApplication.php">Application</a></li>
		<li '.$b2.'><a href="/ICM/VHL/shortlisted.php">For Ocular Visit</a></li>
		<li '.$b3.'><a href="/ICM/VHL/listSelection.php">Selection List</a></li>
		<li '.$b6.'><a href="/ICM/VHL/list_upcoming_batch.php">Upcoming Batch</a></li>
		<li '.$b4.'><a href="/ICM/VHL/headApprovalList.php">Approval List</a></li>
		<li '.$b5.'><a href="/ICM/VHL/new_community_list.php">Communities</a></li>
		<li ><a href="/ICM/VHL/reports.php">Reports</a></li>
		</ul>
  </li>';

if($pastor == "1")
echo '
  <li>
    <a href ="/ICM/Thrive/indexPastor.php">THRIVE</a>
		<ul id="sublist">
		<li '.$pastor1.'><a href="/ICM/Thrive/v2.0/index.php">V2</a></li>
		</ul>
  </li>';

/*
<li '.$pastor2.'><a href="/ICM/Thrive/addPastor.php">Add</a></li>
<li '.$pastor3.'><a href="/ICM/Thrive/listPastorBase.php">Pastors</a></li>

echo '
  <li>
    <a href ="/ICM/PIN/pinIndex.php">PIN</a>
		<ul>
		<li '.$pin1.'><a href="/ICM/PIN/pinAssessment.php">Incident</a></li>
		<li '.$pin2.'><a href="/ICM/PIN/pinRegister.php">Register</a></li>
		</ul>
  </li>';

if($c == "1")
echo '
  <li>
	<a href="#">DATA</a>
		<ul id="sublist">
		</ul>
  </li>';*/

 if($d == "1")
echo '
  <li>
	<a href="/ICM/ADMIN/indexAdmin.php">ADMIN</a>
		<ul id="sublist">
		<li><a href="/ICM/ADMIN/adminUser.php">Users</a></li>
		<li><a href="/ICM/ADMIN/_fixid.php">FIX ID</a></li>
		<li><a href="/ICM/VHL/new_application_list.php">Applications</a></li>
		<li><a href="/ICM/VHL/new_community_list.php">Communities</a></li>
		<li><a href="/ICM/Thrive/v2.0/index.php">new</a></li>
		<li><a href="/ICM/ADMIN/linktransformpastors.php">FIX Link</a></li>
		</ul>
  </li>';

if($username == "lincoln.lau" || $username == "abztan" || $username == "krisha.lim" || $username == "harold.doroteo") {
	echo "<li>
					<a href='#'>METRICS</a>
					<ul id='sublist'>
						<li><a href='/ICM/VHL/raw_repository.php'>Data Repository</a></li>
					</ul>
				</li>";
}

echo '
  <li>
		<a href="/ICM/logout.php">LOGOUT</a>
  </li>
</ul>
';
?>
