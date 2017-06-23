<?php
  session_start();

  include($_SERVER["DOCUMENT_ROOT"]."/code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
  $admin = false;

  if ($user_name == "") {
    // user is not logged in
    return;
  } else {
    if ($user_name == "admin")
      $admin = true;
    echo('<script type="text/javascript"> user_name = "'.$user_name.'"; </script>'."\n");
    echo('<script type="text/javascript"> admin = '.($admin?"true":"false").'; </script>'."\n");
  }
  
  $permissions = list_permissions_for_user( $user_name );

  // find the first permission that corresponds to a site
  // Assumption here is that a user can only add assessment for the first site he has permissions for!
  $sites = "";
  foreach ($permissions as $per) {
     $a = explode("Site", $per); // permissions should be structured as "Site<site name>"

     if (count($a) > 1 && $a[1] != "" && !in_array($a[1], $sites)) {
        $sites[] = $a[1];
     }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Tick-Tock</title>

  <!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/select2.min.css">

</head>

<body>

  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Tick-Tock of ABCD</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/index.php" title="Back to report page">Report</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" class="connection-status" id="connection-status">Connection Status</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="session-active">User</span> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="closeSession();">Close Session</a></li>
            <li><a href="#" onclick="logout();">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

  <!-- start session button -->
  <section id="admin-top" class="bg-light-gray" style="background: rgba(255,255,255,0.2);">
    <div class="container">
      <div class="row" style="margin-bottom: 20px;"></div>
      <div class="row start-page">
	<div class="col-md-12">
	  <div class="date">Adolescent Brain Cognitive Development</div>
	  <div style='position: relative;'>
	    <h1>Study Observation</h1>
	    <div class='date2'>May 2017</div>
	  </div>
	</div>
      </div>
  </section>
  <!-- <svg id="graph" style="width: 100%; height: 60%;"></svg> -->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <p>ABCD events along a time-line illustrate the progress and the pattern of data collection. The graphs below show the temporal sequence for a number of key project indicators. Each time there is a <i>tick</i> in ABCD this page will show the <i>tock</i>.</p>
        <div id="graph"></div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="row">
         <hr>
      <i><p>A service provided by the Data Analysis and Informatics Core of the ABCD study.</p></i>
      </div>
    </div>
  </section>
      
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src='js/moment.min.js'></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>

  <script src="js/select2.full.min.js"></script>


  <script src="https://d3js.org/d3.v4.min.js"></script>
  <script type="text/javascript" src="js/colorbrewer.js"></script>
  <script type="text/javascript" src="js/all.js"></script>

</body>

</html>
