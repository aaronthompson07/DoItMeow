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
	
	// Deny hacking attempt
	if (!defined('IN_ARROWCHAT'))
	{
		exit;
	}
	
	if ($spam_protection == 1)
	{
		$a = session_id();
		if (empty($a)) 
		{
			session_start();
		}
		
		if (!isset($_SESSION['spam_protection']))
		{
			$_SESSION['spam_protection'] = time();
		}
		
		if (!isset($_SESSION['spam_detected']))
		{
			$_SESSION['spam_detected'] = false;
		}
		
		if (isset($_SESSION['spam_protection']) AND ($_SESSION['spam_protection'] < time() - 60))
		{
			$_SESSION['spam_protection'] = time();
			$_SESSION['spam_protection_count'] = 1;
			$_SESSION['spam_detected'] = false;
		}
		
		if (!isset($_SESSION['spam_protection_count']))
		{
			$_SESSION['spam_protection_count'] = 1;
		}
		
		if (isset($_SESSION['spam_protection_count']))
		{
			$_SESSION['spam_protection_count'] = $_SESSION['spam_protection_count'] + 1;
		}
		
		if (isset($_SESSION['spam_protection_count']) AND isset($_SESSION['spam_protection']))
		{
			if ($_SESSION['spam_protection_count'] > 200)
			{
				$_SESSION['spam_protection'] = time() + 300;
				$_SESSION['spam_protection_count'] = 1;
				$_SESSION['spam_detected'] = true;
				die("Spam Detected. Please wait and try again later.");
			}
			
			if ($_SESSION['spam_detected'])
			{
				die("Spam Detected. Please wait and try again later.");
			}
		}
	}

?>