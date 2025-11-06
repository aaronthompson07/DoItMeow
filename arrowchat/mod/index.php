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

	// ########################## INCLUDE BACK-END ###########################
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "includes/admin_init.php");

	$security_token = get_var('token');
	
	if (!empty($security_token)) 
	{
		if (hash_equals($_SESSION['token'], $security_token)) 
		{


		}
		else
		{
			die("No valid token");
		}
	}
	
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_header.php");
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_index.php");
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_footer.php");
	
?>