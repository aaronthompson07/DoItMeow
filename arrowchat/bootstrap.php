<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #    Copyright ©2010-2020 ArrowSuites LLC. All Rights Reserved.    # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/
	
	// Turns off PHP error reporting. Change error_reporting to E_ALL and display_errors
	// to 1 if you are debugging.
	error_reporting(0);
	@ini_set('display_errors', 0);

	// Define the current ArrowChat version
	define('ARROWCHAT_VERSION', '4.1.3');
	
	// Define that we are within ArrowChat
	define('IN_ARROWCHAT', true);

	// Define the ArrowChat directory paths
	define('AC_FOLDER_ADMIN', 'admin');
	define('AC_FOLDER_CACHE', 'cache');
	define('AC_FOLDER_INCLUDES', 'includes');
	define('AC_FOLDER_INSTALL', 'install');
	define('AC_FOLDER_LANGUAGE', 'language');
	define('AC_FOLDER_PUBLIC', 'public');
	define('AC_FOLDER_THEMES', 'themes');
	define('AC_FOLDER_UPGRADE', 'upgrade');
	define('AC_FOLDER_UPLOADS', 'uploads');
	
	//******* REQUIRE CORE FILES *******//
	// ArrowChat Edition File
	include_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "edition.php");
	
	// Database Class
	if (function_exists('mysqli_connect')) require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "classes/class_database_mysqli.php"); else require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "classes/class_database.php");
	
	// Configuration File
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "config.php")) require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "config.php"); else die("The includes/config.php file does not exist.  We recommend installing ArrowChat again making sure to CHMOD all necessary files/folders to 777 regardless of what the installer tells you.  If you still have problems, please contact support at http://www.arrowchat.com/support/.");
	
	//******* CONNECT TO DATABASE *******//
	if (MSSQL_DATABASE == 1)
	{
		$db = new QuickMSDB(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false, false, false);
	}
	else
	{
		if (EMOJI_SUPPORT == 1)
			$db = new QuickDB(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false, false, true);
		else
			$db = new QuickDB(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false, false, false);
	}
	//***** END CONNECT TO DATABASE *****//
	
	// Cache File For Admin Options
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_CACHE . DIRECTORY_SEPARATOR . "data_admin_options.php")) require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_CACHE . DIRECTORY_SEPARATOR . "data_admin_options.php"); else die("The cache/data_admin_options.php file does not exist.  We recommend installing ArrowChat again making sure to CHMOD all necessary files/folders to 777 regardless of what the installer tells you.  If you still have problems, please contact support at http://www.arrowchat.com/support/.");
	
	// Integration File
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "integration.php")) require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "integration.php"); else die("The includes/integration.php file does not exist.  We recommend installing ArrowChat again making sure to CHMOD all necessary files/folders to 777 regardless of what the installer tells you.  If you still have problems, please contact support at http://www.arrowchat.com/support/.");
	
	// PHP functions for PHP v4
	if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION < 5) include_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "functions/functions_php.php");
	
	// Common Functions
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "functions/functions_common.php");
	
	// Language File
	if (!isset($language)) $language = "en";
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_LANGUAGE . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $language . ".php");
	
	// Push Service File
	if ($push_on == 1)
	{
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . "classes/class_arrowpush.php");
	}
	//******* END REQUIRE CORE FILES *******//
	
	// Exit if the user agent is a bot
	if (is_bot())
	{
		exit;
	}

?>