<style>
body {font-size: 18px;}
td {
  padding: 4 4 4 4;

}
table {
  border-collapse: collapse;
  border-spacing: 0;
	padding: 10px 10px 10px 10px;

  font-family: 'Hind', sans-serif;
	font-size: 17px
}
@media print {
    body {font-size: 8px;}
    table { page-break-inside:avoid;
    transform: scale(1,1);}
    td {font-size: 8pt;}
    .no-print, .no-print *
    {
        display: none !important;
    }
    @page { margin: 10 10 10 10; }
    tr    { page-break-inside:auto; page-break-after:auto };
}

</style>
<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";
	include "../_reportFunctions.php";

	//defaults
	$count = 1;
	$selected_base = "0";
	$option_a = "selected";
	$option_b = "";
	$option_c = "";
	$option_d = "";
	$option_e = "";
	$option_f = "";
	$option_g = "";
	$option_h = "";
	$option_i = "";
	$option_j = "";
	$option_k = "";
	$is_hidden = "yes";
	$selected_batch = date("Y").getBatch("current","");

	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "application_id";

	//selected community
	if(isset($_POST['batch_display'])) {
		$selected_batch = $_POST['batch_display'];
		$list_year = substr($selected_batch,2,4);
		$list_batch = substr($selected_batch,-1);
	}

?>
<div class='no-print'>
<br/>
<br/></div>
<?php
	$result = base_hhid("08","community_id","20153");
	$x = 1;
	while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$application_pk = $row['id'];
    $pastor_name = $row['pastor_first_name']." ".$row['pastor_last_name'];
		echo "
			<table border='1' width='100%'>
				<tr align='center'><td colspan='12'>$x. Community: ".$row['community_id']." - $pastor_name</td></tr>
				<tr align='center'>
					<td width='5%'>#</td>
					<td width='15%'>HHID</td>
					<td width='30%'>Name</td>
					<td width='5%'>Class</td>
					<td width='5%'>Gender</td>
          <td width='10%'>Tag</td>
					<td width='10%'>BIB Loan</td>
					<td width='10%'>BIB Sale</td>
					<td width='10%'>100% Repayment</td>
				</tr>";

		$participant = base_participants($application_pk);
		$i = 1;
		while($person = pg_fetch_array($participant,NULL,PGSQL_BOTH)) {
      $participat_id = $person['participant_id'];
      $participant_pk = $person['id'];
      $category = $person['category'];
      $gender = $person['gender'];
      $tag = $person['tag'];
      if($tag == "6")
        $status = "Graduated";
      else if($tag == "9")
        $status = "Dropped";
      else if($tag == "5")
        $status = "Active";

      if($category >= 1 && $category <=6)
        $class = "Participant";
      if($category >= 20 && $category <= 22)
        $class = "Counselor";

      //count kits taken
      $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
    	$db = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
    	$q = "select count(*)
                from list_bib_participant
                where fk_participant_pk = '$participant_pk'";
    	$ex = pg_query($db, $q);
      $r = pg_fetch_array($ex,NULL,PGSQL_BOTH);
    	$kits_taken = $r['count'];
      //end kits taken

      //first sale
      $q = "select *
            from list_bib_participant
            left join log_bib_payment
            on list_bib_participant.id = log_bib_payment.fk_bib_participant_pk
            where fk_participant_pk = '$participant_pk'
            order by week";
      $ex = pg_query($db, $q);
      $sale = 0;
      $week = "x";
      while($r = pg_fetch_array($ex,NULL,PGSQL_BOTH)) {
        if($r['sale'] == "t")
          $sale++;
        //$week = $r['week'];
      }

      //repayment
      $q = "select week, balance, sale,fk_participant_pk
        from list_bib_participant
        left join log_bib_payment
        on list_bib_participant.id = log_bib_payment.fk_bib_participant_pk
        where fk_participant_pk = '$participant_pk'
        group by week, fk_participant_pk, balance, sale
        order by week";
      $ex = pg_query($db, $q);
      $repayment = 0;
      $week = "x";
      while($r = pg_fetch_array($ex,NULL,PGSQL_BOTH)) {
        if($r['week'] != $week && $r['balance'] <= 0)
          $repayment++;
        $week = $r['week'];
      }


			echo "
					<tr>
						<td align='center'>$i</td>
						<td align='center'>$participat_id</td>
						<td>".$person['last_name'].", ".$person['first_name']." ".$person['middle_name']."</td>
            <td align='center'>$class</td>
            <td align='center'>$gender</td>
            <td align='center'>$status</td>
						<td align='center'>$kits_taken</td>
						<td align='center'>$sale</td>
						<td align='center'>$repayment</td>";
					$i++;
		}
			echo "</table><br/><br/>";
			$x++;
	}
?>
