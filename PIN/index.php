<?php
	include_once "_ptrFunctions.php";
	include_once $root."/ICM/session.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Project Tomorrow</title>

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="yes">
<link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">
<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Material Design Lite">
<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">
<!-- Tile icon for Win8 (144x144 + tile color) -->
<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
<meta name="msapplication-TileColor" content="#3372DF">

<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
<style>
  #view-source {
    position: fixed;
    display: block;
    right: 0;
    bottom: 0;
    margin-right: 40px;
    margin-bottom: 40px;
    z-index: 900;
  }
</style>
</head>

<!-- Simple header with scrollable tabs. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title">Thrive</span>
    </div>
    <!-- Tabs -->
    <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
      <a href="#tab-1" class="mdl-layout__tab is-active">Dashboard</a>
      <a href="#tab-2" class="mdl-layout__tab">Districts</a>
      <a href="#tab-3" class="mdl-layout__tab">+Profile</a>
      <a href="#tab-4" class="mdl-layout__tab">+Data Card</a>
      <a href="#tab-5" class="mdl-layout__tab">Search</a>
      <a href="#tab-6" class="mdl-layout__tab">Report</a>
    </div>
  </header>

  <div class="mdl-layout__drawer">
    <span style="font-size:14px;" class="mdl-layout-title"><img src="/ICM/_media/ic_person_black_48dp_1x.png"/><?php echo $account_name;?></span>
    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="">Home</a>
      <a class="mdl-navigation__link" href="../../VHL/indexTransform.php">Transform</a>
      <a class="mdl-navigation__link" href="">Thrive</a>
    </nav>
  </div>

  <main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active" id="tab-1">
      <div class="page-content">
        <?php include_once "dashboard.php"; ?>
				<!--<iframe src="dashboard.php" frameborder="0" style="position: absolute; height: 100%; border: none; background:;"></iframe>-->
      </div>
    </section>

    <section class="mdl-layout__tab-panel" id="tab-2">
      <div class="page-content">
				<iframe src="district_list.php" frameborder="0" style="position: absolute; height: 100%; border: none; background:;"></iframe>
      </div>
    </section>

    <section class="mdl-layout__tab-panel" id="tab-3">
      <div class="page-content">
				<?php //include_once "profile_add.php";?>
				<iframe src="profile_add.php" frameborder="0" style="position: absolute; height: 100%; border: none; background:;"></iframe>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="tab-4">
      <div class="page-content" style="margin:0px;padding:0px;overflow:hidden">
				<?php //include_once "data_card_add.php";?>
				<iframe src="data_card_add.php" frameborder="0" style="position: absolute; height: 100%; border: none"></iframe>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="tab-5">
      <div class="page-content">
				<?php include_once "pastor_search.php";?>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="tab-6">
      <div class="page-content">
				<iframe src="r1.php" frameborder="0" style="position: absolute; height: 100%; border: none"></iframe>
      </div>
    </section>
  </main>
</div>
</html>
