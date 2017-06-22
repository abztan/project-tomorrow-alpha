<?php
  include_once "_select_functions.php";
  $base_id = 1;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../tokyo.css"/>
  </head>
  <body>
    <div>International Care Ministries</div>
    <div><a href="index.php">Return</a></div>
    <div>
      <br/>
      <div><?= get_base_string($base_id); ?></div>
      2016<br/>
      <a href="schedule_view.php?month=June">June</a><br/>
      <a href="schedule_view.php?month=July">July</a><br/>
      <a href="schedule_view.php?month=August">August</a><br/>
      <div></div>
    </div>
  </body>
</html>
