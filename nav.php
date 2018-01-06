<?PHP if(!defined("CONFIG")) exit() ?>
<?PHP if(defined("USER_MUST_LOGIN") && !isset($login)) return; ?>

<!-- Navbar -->
<nav>
<div class="w3-top w3-opacity w3-hover-opacity-off">
<ul class="w3-navbar w3-black w3-card-2 w3-left-align w3-topnav">
  <li class="w3-hide-medium w3-hide-large w3-opennav w3-left">
    <a class="w3-padding-large" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
  </li>
   <li><a href="?page=main" class="w3-hover-none w3-hover-red w3-padding-large">HOME</a></li>

<li class="w3-hide-small w3-dropdown-hover">
    <a href="?page=main" class="w3-hover-red w3-padding-large" title="RESULTS">REGISTRATION <i class="fa fa-caret-down"></i></a>
    <div class="w3-dropdown-content w3-white w3-card-4">
       <a href="?page=main" class="w3-hover-none w3-hover-red w3-padding-large">..change this at nav.php..</a>
       <a href="?page=main" class="w3-hover-none w3-hover-red w3-padding-large">external link</a>
    </div>
</li>

  <li><a href="?page=show_circuits" class="w3-hover-none w3-hover-red w3-padding-large">SCHEDULE</a></li>


<li class="w3-hide-small w3-dropdown-hover">
    <a href="?page=main" class="w3-hover-red w3-padding-large" title="RESULTS">SERIES <i class="fa fa-caret-down"></i></a>
    <div class="w3-dropdown-content w3-white w3-card-4">
       <a href="?page=result_season&season=1" class="w3-hover-red">..change this at nav.php..</a>
       <a href="?page=result_season&season=2" class="w3-hover-red">Display Season id 1</a>
       <a href="?page=result_season&season=3" class="w3-hover-red">Display Season id 2</a>
    </div>
</li>

<li class="w3-hide-small w3-dropdown-hover">
    <a href="?page=main" class="w3-hover-red w3-padding-large" title="REGULATIONS">REGULATIONS <i class="fa fa-caret-down"></i></a>
    <div class="w3-dropdown-content w3-white w3-card-4">
       <a href="?page=show_rules&id=1" class="w3-hover-red">..to be created (admin panel)</a>
       <a href="?page=show_rules&id=2" class="w3-hover-red">..to be created (admin panel)</a>
    </div>
</li>

<li><a href="?page=show_drivers" class="w3-hover-none w3-hover-red w3-padding-large">HALL OF FAME</a></li>
<li><a href="?page=show_videos" class="w3-hover-none w3-hover-red w3-padding-large">VIDEOS</a></li>

<?PHP if(defined("USE_MYSQL") && defined("USE_LOGIN")) { ?>
<?PHP if(!isset($login)) { ?>
    <li class="w3-hide-small"><a href="?page=login" class="w3-padding-large"><img src="images/admin.png" alt="Admin Login" /></a></li>
<?PHP } else { ?>
  <li class="w3-hide-small w3-dropdown-hover">
    <a href="javascript:void(0)" class="w3-hover-red w3-padding-large" title="Login Admin">Admin <i class="fa fa-caret-down"></i></a>
    <div class="w3-dropdown-content w3-white w3-card-4">
    <a href="?page=divisions" class="w3-hover-red">Divisions</a>
    <a href="?page=points" class="w3-hover-red">Rulesets</a>
    <a href="?page=seasons" class="w3-hover-red">Seasons</a>
    <a href="?page=races" class="w3-hover-red">Races</a>
    <a href="?page=drivers" class="w3-hover-red">Drivers</a>
    <a href="?page=teams" class="w3-hover-red">Teams</a>
    <a href="?page=cars" class="w3-hover-red">Cars</a>
    <a href="?page=show_rules_edit" class="w3-hover-red">Edit rules</a>
    <a href="?page=sim_results_add" class="w3-hover-red">Send Simresults url</a>
    <a href="?page=send_video_url" class="w3-hover-red">Send video url</a>
    <a href="?page=main_news" class="w3-hover-red">News mainpage</a>
    <a href="?page=blocks" class="w3-hover-red">Blocks_setup</a>
    <a href="?page=upload" class="w3-hover-red">Upload_file</a>
    <a href="?page=users" class="w3-hover-red">Admins</a>
    <a href="?page=logout" class="w3-hover-red">Logout</a></li>
    </div>
  </li>
</ul>
</div>
<?PHP } ?>
<?PHP } ?>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
  <ul class="w3-navbar w3-left-align w3-black">
    <li><a class="w3-padding-large" href="?page=main">HOME</a></li>
    <li><a class="w3-padding-large" href="?page=results">Results</a></li>
    <li><a class="w3-padding-large" href="?page=sim_results">Sim_Results</a></li>
    <li><a class="w3-padding-large" href="?page=show_circuits">Circuit calendar</a></li>
    <li><a class="w3-padding-large" href="?page=show_drivers">Drivers</a></li>
    <li><a class="w3-padding-large" href="?page=show_teams">Teams</a></li>
    <li><a class="w3-padding-large" href="?page=show_rules">Rules</a></li>
    <li><a class="w3-padding-large" href="?page=show_videos">Videos</a></li>
    <li><a class="w3-padding-large" href="?page=driver_add_user">New_Driver</a>
    <li><a class="w3-padding-large" href="your_forum_url">Forum</a>

<?PHP if(defined("USE_MYSQL") && defined("USE_LOGIN")) { ?>
<?PHP if(!isset($login)) { ?>
    <li><a class="w3-padding-large" href="?page=login">Login_Admin</a></li>
<?PHP } else { ?>
    <li><a class="w3-padding-large" href="?page=drivers">Edit drivers</a></li>
    <li><a class="w3-padding-large" href="?page=teams">Edit teams</a></li>
    <li><a class="w3-padding-large" href="?page=main_news">News mainpage</a></li>
    <li><a class="w3-padding-large" href="?page=sim_results_add">Send Simresults url</a></li>
    <li><a class="w3-padding-large" href="?page=users">Admins</a></li>
    <li><a class="w3-padding-large" href="?page=logout">Logout</a></li>

  </ul>
</div>
<?PHP } ?>
<?PHP } ?>
</nav>
