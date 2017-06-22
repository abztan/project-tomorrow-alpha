<?php
include "../dbconnect.php";
include_once "_tnsFunctions.php";

  if($_GET['command']=="update_community_bib") {
    $application_pk = $_GET['b'];
    $week = "week_".$_GET['c'];
    $bib_community_pk = $_GET['d'];
    $username = $_GET['e'];
    updateCommunity_BIB($application_pk,$week,$bib_community_pk,$username);
  }

  else if($_GET['command']=="update_community_capital") {
    $application_pk = $_GET['b'];
    $week = $_GET['c'];
    $capital_value = $_GET['d'];
    $username = $_GET['e'];
    updateCommunity_BIB_capital($application_pk,$week,$capital_value,$username);
  }

  else if($_GET['command']=="add_bib_person") {
    $person_id = $_GET['a'];
    $participant_pk = substr($person_id,0,-1);
    $class = substr($person_id,-1);
    $type = $_GET['b'];
    $kit_count = $_GET['c'];
    $bib_class = $_GET['d'];
    $bib_community_pk = $_GET['e'];
    $week = $_GET['f'];
    $kit_capital = $_GET['g'];
    $username = $_GET['h'];
    $capital_total = $kit_capital*$kit_count;
    $dispersal_total = sumBIB_community_dispersal($week,$bib_community_pk);
    $dispersal_max = getBIB_dispersal_max($bib_class);
    if(checkDuplicate_BIB_person($participant_pk,$week,$bib_community_pk) != 1) {
      if($dispersal_total + $kit_count <= $dispersal_max) {
        insertCommunity_BIB_person($participant_pk,$class,$type,$kit_count,$bib_class,$bib_community_pk,$week,$capital_total,$username);
				echo "SUCCESS: Participant Added!";
      }
      else {
        if($dispersal_total >= $dispersal_max)
          echo "FAILED: The maximum dispersal for this kit has been reached.";
        else {
          $diff = $dispersal_max - $dispersal_total;
          echo "FAILED: Only $diff remaining kit/s available to disperse.";
        }
      }
    }
    else {
      echo "FAILED: This person already has an existing entry.";
    }
  }

  else if($_GET['command']=="add_bib_payment") {
    $bib_community_pk = $_GET['a'];
    $bib_participant_pk = $_GET['b'];
    $week_entry = $_GET['c'];
    $pay_sale = $_GET['d'];
    $pay_cash = $_GET['e'];
    $pay_noncash = $_GET['f'];
    $username = $_GET['g'];
    $week_id = $_GET['h'];
    $class = $_GET['i'];

    $total_payment = $pay_cash + $pay_noncash;

    if(checkDuplicate_BIB_payment($bib_participant_pk,$week_entry,$class) != 1) {
      insertCommunity_BIB_payment($bib_community_pk,$bib_participant_pk,$week_entry,$pay_sale,$pay_cash,$pay_noncash,$username,$week_id,$class);
      $pay_total = $pay_noncash + $pay_cash;
      updateBIB_participant_balance($bib_participant_pk,$pay_total,$username,$class);
      echo "SUCCESS: Payment Added! Reloading page in <span id='counter'>5</span> seconds.</span>";
    }
    else
      echo "An entry for this participant on week $week_entry already exists.";
  }

  else if($_GET['command']=="check_dup_payment") {
    $bib_participant_pk = $_GET['a'];
    $week_entry = $_GET['b'];
    $class = $_GET['c'];

    if(checkDuplicate_BIB_payment($bib_participant_pk,$week_entry,$class) == 1)
      echo "An entry for this participant on week $week_entry already exists.";
  }
?>
