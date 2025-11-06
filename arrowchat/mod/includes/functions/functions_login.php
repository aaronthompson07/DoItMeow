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
	
	/**
	 * Check and see if the computer is already logged in
	 *
	 * @param string $error An error message if one exists
	*/
	function admin_check_login($error)
	{
		global $is_mod;
		global $is_admin;

		if ($is_mod == 1 OR $is_admin == 1)
		{
		
		}
		else
		{
			die("You are not authorized for this area.");
		}
	}
	
?>