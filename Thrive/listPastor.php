<?php
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="listPastor.php";


	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');

	include "_ptrFunctions.php";
	include "../_parentFunctions.php";
	include "../_css/bareringtonbear.css";
	//php
	$search="";
	$searchitem="";

	if(!isset($_POST['search']))
		$_POST['search']="";

	if($_SESSION['previouspage']!=$_SESSION['currentpage'])
		unset($_SESSION['searchitem']);

	if(isset($_POST['search']) && $_POST['search'] != "")
	{
		$_SESSION['searchitem']=$_POST['search'];
		$query = searchPastor($_POST['search']);
		$rows = countSearchedPastor($_POST['search']);
		$search = $_SESSION['searchitem'];
		//echo "A";
	}

	else  if(isset($_SESSION['searchitem']))
	{
		if($_SESSION['searchitem']!="")
		{
			$query = searchPastor($_SESSION['searchitem']);
			$rows = countSearchedPastor($_SESSION['searchitem']);
			$search = $_SESSION['searchitem'];
			//echo "b";
		}
	}

	else
	{
		$search = "";
		$query = getListPastor();
		$rows = countListPastor("Total Pastor");
		unset($_SESSION['searchitem']);
		//echo "c";
	}

	if(isset($_POST['reset']))
	{
		$search = "";
		$query = getListPastor();
		$rows = countListPastor("Total Pastor");
		unset($_SESSION['searchitem']);
		//echo "d";
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php  include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">

	<legend>ICM Pastors</legend> <input type="text" placeholder="Search by Name" name="search" onchange="form.submit()" value="<?php echo $search;?>">
	<input type="submit" class="btn btn-embossed btn-warning" style = "line-height: 0.4; padding: 7px  5px;" name="reset" value = "x" >
	<br/>
	<?php echo $rows," results";?>
	<table id="listtable" border ="0">
	<tr>
	  <th>ID</th>
	  <th style="text-align: center; width: 25%">Name</th>
	  <th style="text-align: center; width: 10%">Status</th>
	  <th style="text-align: center; width: 20%">Base</th>
	  <th style="text-align: center; width: 5%">Thrive</th>
	  <th style="text-align: center; width: 40%">Church</th>
	</tr>

	<?php

	$page_rows = 10;
	$last = ceil($rows/$page_rows);
	if($last < 1){
		$last = 1;
	}
	$pagenum = 1;
	if(isset($_GET['pn'])){
		$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
	}
	if ($pagenum < 1) {
		$pagenum = 1;
	} else if ($pagenum > $last) {
		$pagenum = $last;
	}

	$limit = 'LIMIT '.$page_rows.' OFFSET '.($pagenum - 1)*$page_rows;

	$query = $query." ".$limit;

	$result = pg_query($dbconn, $query);

	$paginationCtrls = '';

	$last;

	if($last != 1){

		if ($pagenum > 1) {
			$previous = $pagenum - 1;
			$paginationCtrls .= '<a href="listPastor.php?pn='.$previous.'&search='.$_POST['search'].'">Previous</a> &nbsp; &nbsp; ';

			for($i = $pagenum-2; $i < $pagenum; $i++){
				if($i > 0){
					$paginationCtrls .= '<a href="listPastor.php?pn='.$i.'&search='.$_POST['search'].'">'.$i.'</a> &nbsp; ';
				}
			}
		}

		$paginationCtrls .= ''.$pagenum.' &nbsp; ';

		for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<a href="listPastor.php?pn='.$i.'&search='.$_POST['search'].'">'.$i.'</a> &nbsp; ';
			if($i >= $pagenum+2){
				break;
			}
		}

		if ($pagenum != $last) {
			$next = $pagenum + 1;
			$paginationCtrls .= ' &nbsp; &nbsp; <a href="listPastor.php?pn='.$next.'&search='.$_POST['search'].'">Next</a> ';
		}
	}

	$list = '';

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){

		if($row['member'] == "t")
		$member = "*";
		else
		$member = "";

		if($row['active'] == "f")
		$active = "Inactive";
		else
		$active = "Active";

		$church_id = $row['churchid'];

		if($church_id != "") {
			$church = getChurch_details($church_id);
			$church_name = $church['churchname'];
		}

		 echo "<tr>
			  <td>".$row['id']."</td>
			  <td><a href = 'viewPastor.php?a=".$row['id']."'>".$row['lastname'].", ".$row['firstname'],$member."</a></td>
			  <td align = 'center'>".$active."</td>
			  <td align = 'center'>".getBaseName($row['baseid'])."</td>
			  <td align = 'center'>".$row['thriveid']."</td>
			  <td align = 'center'>".$church_name."</td></tr>";
	}

	pg_close($dbconn);
	?>
	</table>
	<br/>
	<div style="text-align: center;"><?php echo $paginationCtrls; ?></div>
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>
