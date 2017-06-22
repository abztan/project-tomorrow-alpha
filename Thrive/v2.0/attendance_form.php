<?php
  $form = "data_card_add.php";
  $option_a = "";
  $option_b = "";
  
  if(isset($_POST['attendance_type'])) {
    $form = $_POST['attendance_type'];
    if($form == "data_card_add.php")
      $option_a = "selected";
    else if($form == "sd_attendance_add.php")
      $option_b = "selected";
  }
?>
<link href='http://fonts.googleapis.com/css?family=Quicksand|Crimson+Text|Hind|Roboto+Mono' rel='stylesheet' type='text/css'>
<style>
#sup {
	font-family: 'Quicksand', sans-serif;
	min-height: 50px;
	font-size: 20px;
	font-style: strong;
	font-weight: 800;
	text-align:right;
    background: 0;
    color:#494949;
	display: none;
    border:0px solid #FFFFFF;
    display: inline-block;
	outline-color: #FFFFFF;
	-webkit-appearance:none;
}
</style>

<form name = "form" action = "" method = "POST">
  <div style="padding: 20 0 0 20">FORM:
  <select id='sup' name='attendance_type' onchange = 'form.submit()'>
    <option value = "#" selected disabled>(Select One)</option>
    <option <?php echo $option_a; ?> value="data_card_add.php">Thrive Data Card</option>
    <option <?php echo $option_b; ?> value="sd_attendance_add.php">2nd Day Attendance</option>
  </select>
  </div>
</form>
<?php include_once $form;?>
</html>
