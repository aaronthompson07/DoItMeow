<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr"> 
<head profile="http://gmpg.org/xfn/11"> 
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="expires" content="-1">
	
	<title><?php if (!empty($title)) echo $title; else echo "ArrowChat Moderation Panel"; ?></title> 

	<link rel="stylesheet" type="text/css" href="includes/css/style.css" /> 
	<link rel="stylesheet" href="includes/css/menu/core.css" type="text/css" media="screen">
	<link rel="stylesheet" href="includes/css/menu/styles/sblue.css" type="text/css" media="screen">
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	
	<!--[if (gt IE 9)|!(IE)]><!-->
		<link rel="stylesheet" href="includes/css/menu/effects/slide.css" type="text/css" media="screen">
	<!--<![endif]-->

	<!-- This piece of code, makes the CSS3 effects available for IE -->
	<!--[if lte IE 9]>
		<script src="includes/js/menu.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$(function() {
				$("#menu").menu({ 'effect' : 'slide' });
			});
		</script>
	<![endif]-->
	<!-- responsive -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="includes/css/responsive.css">
	<link rel="stylesheet" href="includes/css/slicknav.css">
	<script src="includes/js/modernizr.min.js"></script>
	<script src="includes/js/jquery.slicknav.js"></script>

	<script type="text/javascript">
	jQuery(document).ready(function(){
	    jQuery('#menu').slicknav();
	});
	</script>
	<!-- end -->
</head> 
<body>
<div id="wrapper">
	<div id="topnav">
		<div id="topnavcontent">
			<div style="float: left; padding-top:8px; padding-left:20px;">
				<img id="logo" style="width: 206px; height: 28px;" src="images/img-logo.png" height="28" width="206" border="0" alt="" />
			</div>
			<div style="float: left; position: relative; top: 17px; padding-left: 20px;">
				<a href="../../">Visit Site &#187;</a>
			</div>
		</div>
	</div>
	<div id="subnavwrapper">
		<div id="subnav">
			<ul class="menu sblue" id="menu">
			  <li><a href="./">Reports</a>
			  </li>
			  <?php
				  if (ARROWCHAT_EDITION != "lite")
				  {
			  ?>
			  <li><a href="./manage.php?do=chatroomsettings">Manage</a>
				<ul>
					<li><a href="./manage.php?do=chatroomsettings">Chat Rooms</a></li>
				</ul>
			  </li>
			  <?php
				}
			  ?>
			  <li><a href="./users.php?do=<?php if ($is_admin == 1) echo "manageusers"; else echo "banusernames"; ?>">Users</a>
				<ul>
					<?php if ($is_admin == 1) { ?><li><a href="./users.php?do=manageusers">Manage Users</a></li>
					<li><a href="./users.php?do=manageadmins">Manage Mods/Admins</a></li><?php } ?>
					<li><a href="./users.php?do=banusernames">Ban Usernames</a></li>
					<li><a href="./users.php?do=banip">Ban IP Addresses</a></li>
				</ul>
			  </li>
			<ul>
		</div>
	</div>
	<div class="breadcrumbs">
		<div class="breadcrumbs_container">
			<?php if (empty($_GET['do']) OR $_GET['do'] == '/' OR $_GET['do'] == 'delete_history') { ?>Reports<?php } ?>
			<?php if ($_GET['do'] == 'chatroomsettings' OR $_GET['do'] == 'chatroomedit' OR $_GET['do'] == 'chatroomlogs') { ?>Manage<?php } ?>
			<?php if ($_GET['do'] == 'manageusers' OR $_GET['do'] == 'logs' OR $_GET['do'] == 'view') { ?>Manage Users<?php } ?>
			<?php if ($_GET['do'] == 'manageadmins' OR $_GET['do'] == 'actions') { ?>Manage Mods/Admins<?php } ?>
			<?php if ($_GET['do'] == 'banusernames') { ?>Ban Usernames<?php } ?>
			<?php if ($_GET['do'] == 'banip') { ?>Ban IP Addresses<?php } ?>
		</div>
	</div>
	<div id="content">
		<div id="leftcontent">
				<?php 
					if (empty($_GET['do']) OR $_GET['do'] == '/' OR $_GET['do'] == 'chatfeatures' OR $_GET['do'] == 'chatsettings' OR $_GET['do'] == 'delete_history')
					{
				?>
				<div class="admin_title_bg"> 
					<ul id ="menu-general"> 
						<li class="navHead">Home</li>
						<li <?php if (empty($_GET['do']) OR $_GET['do'] == '/' OR $_GET['do'] == 'delete_history') { ?>class="active_nav"<?php } ?>><a href="./">Reports</a></li> 
					</ul> 
				</div>
				<?php
					}
				?>
				<?php 
					if ($_GET['do'] == 'chatroomsettings' OR $_GET['do'] == 'chatroomedit' OR $_GET['do'] == 'chatroomlogs')
					{
				?>
				<div class="admin_title_bg">
					<ul id ="menu-manage">
						<li class="navHead">Manage</li>
						<li <?php if ($_GET['do'] == 'chatroomsettings' OR $_GET['do'] == 'chatroomedit' OR $_GET['do'] == 'chatroomlogs') { ?>class="active_nav"<?php } ?>><a href="manage.php?do=chatroomsettings">Chat Rooms</a></li> 
					</ul>
					<?php
						if (!empty($feature_disabled))
						{
					?>
						<div class="feature-disabled">
							<b><?php echo $feature_disabled; ?> Disabled</b><br />This feature is disabled and will not display in the bar regardless of these settings.  You can enable it under general features.
						</div>
					<?php
						}
					?>
				</div>
				<?php
					}
				?>
				<?php 
					if ($_GET['do'] == 'banip' OR $_GET['do'] == 'banusernames' OR $_GET['do'] == 'manageusers' OR $_GET['do'] == 'manageadmins' OR $_GET['do'] == 'logs' OR $_GET['do'] == 'view' OR $_GET['do'] == 'actions')
					{
				?>
				<div class="admin_title_bg"> 
					<ul id ="menu-users"> 
						<?php if ($is_admin == 1) { ?><li class="navHead">Users</li>
						<li <?php if ($_GET['do'] == 'manageusers' OR $_GET['do'] == 'logs' OR $_GET['do'] == 'view') { ?>class="active_nav"<?php } ?>><a href="users.php?do=manageusers">Manage Users</a></li>
						<li <?php if ($_GET['do'] == 'manageadmins' OR $_GET['do'] == 'actions') { ?>class="active_nav"<?php } ?>><a href="users.php?do=manageadmins">Manage Mods/Admins</a></li><?php } ?>
						<li <?php if ($_GET['do'] == 'banusernames') { ?>class="active_nav"<?php } ?>><a href="users.php?do=banusernames">Ban Usernames</a></li>
						<li <?php if ($_GET['do'] == 'banip') { ?>class="active_nav"<?php } ?>><a href="users.php?do=banip">Ban IP Addresses</a></li>
					</ul> 
				</div>
				<?php
					}
				?>
		</div>
		<div id="rightcontent">
			<?php
				if (!empty($error))
				{
			?>
			<div class="error-msg-wrapper">
				<div class="error-msg">
					<?php echo $error; ?>
				</div>
			</div>
			<?php
				}
			?>
			<?php
				if (!empty($msg))
				{
			?>
			<div class="success-msg-wrapper">
				<div class="success-msg">
					<?php echo $msg; ?>
				</div>
			</div>
			<?php
				}
			?>