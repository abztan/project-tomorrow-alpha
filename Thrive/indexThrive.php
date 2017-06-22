<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/login.php?a=2');

	include "_ptrFunctions.php";
	include "../dbconnect.php";
	include "../_parentFunctions.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Project Tomorrow</title>
<!-- Material Light Design. -->
<link rel="stylesheet" href="/ICM/_css/material.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>

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

<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="material.min.css">
<link rel="stylesheet" href="styles.css">
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
      <a href="#scroll-tab-1" class="mdl-layout__tab is-active">Dashboard</a>
      <a href="#scroll-tab-2" class="mdl-layout__tab">Districts</a>
      <a href="#scroll-tab-3" class="mdl-layout__tab">Pastors</a>
    </div>
  </header>

  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title"><img src="/ICM/_media/ic_person_black_48dp_1x.png"/></span>
    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="">Home</a>
      <a class="mdl-navigation__link" href="">Transform</a>
      <a class="mdl-navigation__link" href="">Thrive</a>
    </nav>
  </div>

  <main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active" id="scroll-tab-1">
      <div class="page-content">
        <?php //include "dashboardThrive.php"; ?>
      </div>
    </section>

    <section class="mdl-layout__tab-panel" id="scroll-tab-2">
      <div class="page-content" style="margin:0px;padding:0px;overflow:hidden">
				<iframe src="listDistrict.php" frameborder="0" style="position: absolute; height: 100%; border: none"></iframe>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="scroll-tab-3">
      <div class="page-content"><!-- Your content goes here --></div>
    </section>
  </main>
</div>

</html>
