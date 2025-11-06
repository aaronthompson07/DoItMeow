<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #    Copyright ©2010-2012 ArrowSuites LLC. All Rights Reserved.    # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/
	
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
	if (!defined('_JEXEC'))
	{
		session_start();
	}
	
	// ########################## INCLUDE BACK-END ###########################
	require_once(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . "bootstrap.php");
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "functions/functions.php");
	require_once(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "init.php");
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "functions/functions_update.php");
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "functions/functions_login.php");
	
	// Fix undefined variables notices
	$error = NULL;
	$msg = NULL;
	$arrowchat_has_update = false;
	$themes_have_update = false;
	$themes_update_count = 0;
	
	if (empty($_GET['do'])) $_GET['do'] = NULL;
	
	// Get do variable
	$do = get_var('do');
	
	// Check if logged in as admin
	admin_check_login($error);
	
	// Create CSRF Token
	if (empty($_SESSION['token'])) 
	{
		if (function_exists('mcrypt_create_iv')) 
		{
			$_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		} 
		else 
		{
			$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
		}
	}
	
	$token = $_SESSION['token'];
	
	//session_write_close();
	
	// Various admin checks
	$result = $db->execute("
		SELECT arrowchat_themes.folder 
		FROM arrowchat_themes
		WHERE arrowchat_themes.default = 1
	");
	$row = $db->fetch_array($result);
	$theme = $row['folder'];
	$write = check_config_file();
	$install = check_install_folder();
	
	//*********Smarty Variables************
	// Check if features are disabled to display message
	$feature_disabled = "";
	if ($chatrooms_on != 1 AND $do == "chatroomsettings")
	{
		$feature_disabled = "Chatrooms";
	}
	
	if ($notifications_on != 1 AND $do == "notificationsettings")
	{
		$feature_disabled = "Notifications";
	}
	
?>