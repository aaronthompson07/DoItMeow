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

	// ########################## INCLUDE BACK-END ###########################
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php');

	// ############################ OPTIMIZATION #############################
	//if (!ob_start("ob_gzhandler"))
	//{
		ob_start();
	//}
	
	// ########################## START AUTOLOAD JS ##########################
		header('Content-type: text/javascript; charset=UTF-8');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
	$v = get_var('v');
	if ($v == "popout")
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_autoload_popout.js');
	else
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_autoload.js');
	
	close_session();
	exit;

?>