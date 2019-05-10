
<?php

if ($auth->isLoggedIn()) {
    $loc='Home';
	$homeBrand = '<a class="navbar-brand" href="'. $domainRoot .'/index.php">'. $loc .' </a>';
	$menubar = '<li><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> '. $auth->getEmail().' <b class="caret"></b></a>
          <ul class="dropdown-menu">
                <a href="'. $domainRoot .'/auth/userprofile.php"><i class="fa fa-fw fa-user"></i> Profile </a><br>
                <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a><br>
                <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a><br>
           	<form action="" method="post" accept-charset="utf-8">
	<input type="hidden" name="action" value="logOut" />
	<i style="color:#337ab7;" class="fa fa-fw fa-power-off"></i> <button type="submit" class="buttonlinkclass"> Log out</button>
	</form>
          </ul>
        </li>';
	$menuItems = '      <ul class="nav navbar-nav">
        <li><a href="'. $domainRoot .'/admin/"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
		        <li><a href="?user"><i class="fa fa-fw fa-user"></i> Users</a></li>
          <!-- This is the Posts Menu Item -->
        <li><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-desktop"></i>Conversations<i class="fa fa-fw fa-caret-down"></i></a>
          <ul id="Conversations" class="dropdown-menu">
            <a href="./sms_conversations.php"><i class="fa fa-fw fa-user"></i>Create Conversation</a>
          </ul>
        </li>
      </ul>';
}
else {
	$homeBrand = '<label class="navbar-brand">'. $loc .' </label>';
    $menubar = '
		<li><a href="/rebuild/"><i class="fa fa-user"></i> Login </a></li>
	';
	$menuItems='';
}
?>
<!-- Navigation -->
<style>
.buttonlinkclass {
    border: none;
    outline: none;
    background: none;
    cursor: pointer;
    color: #337ab7;
    padding: 0;
    font-family: inherit;
    font-size: inherit;
}
.buttonlinkclass:hover {
text-decoration: underline;
}



</style>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div>
    <?php echo $homeBrand;?>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>

            <!-- Top Menu Items -->
    <!-- This is the TOP RIGHT MENU -->
    <div class="collapse navbar-collapse" id = "navbar-ex1-collapse">
      <ul class="nav navbar-right top-nav" >
        <?php echo $menubar;?>

      </ul>
      <!--Menu Items These collapse to the responsive navigation menu on small screens -->
		<?php echo $menuItems;?>
    </div>
  </div>
<!-- /.navbar-collapse -->
</nav>
