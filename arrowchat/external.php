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
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'functions/functions_mobile.php');

	$type = get_var('type');

	// ############################ OPTIMIZATION #############################
	//if (!ob_start("ob_gzhandler"))
	//{
		ob_start();
	//}

	// ########################### EXIT CONDITIONS ###########################
	// Exit if the type is not supported
	if ($type != "css" AND $type != "popoutcss" AND $type != "js" AND $type != "djs" AND $type != "pjs" AND $type != "mjs")
	{
		close_session();
		exit;
	}
	
	// Exit if not logged in
	if (!logged_in($userid) AND empty($guests_can_view)) 
	{
		$not_logged_in = 1;
	}
	else
	{
		$not_logged_in = 0;
	}

	// Exit if banned
	if (in_array($_SERVER['REMOTE_ADDR'], $banlist) || in_array($userid, $banlist)) 
	{
		if (!empty($_SERVER['REMOTE_ADDR']))
		{
			close_session();
			exit;
		}
	}

	// Exit if IE8 or lower
	if (isset($_SERVER['HTTP_USER_AGENT'])) 
	{
		if (isset($_SERVER['HTTP_USER_AGENT']) && ((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)))
		{
			close_session();
			exit;
		}
	}

	// Detect mobile device
	$mobile_device = 0;
	$detect = new Mobile_Detect;
	if ($detect->isMobile()) 
	{
		$mobile_device = 1;
	}
	
	// Exit for group permissions
	if ($group_enable_mode == 1)
	{
		if (check_array_for_match($group_id, $group_disable_arrowchat_sep))
		{
		}
		else
		{
			close_session();
			exit;
		}
	}
	else
	{
		if (check_array_for_match($group_id, $group_disable_arrowchat_sep))
		{
			close_session();
			exit;
		}
	}

	// ############################ PROCESS THEME ############################
	if (is_numeric($theme)) 
	{
		$result = $db->execute("
			SELECT folder 
			FROM arrowchat_themes
			WHERE id = '" . $db->escape_string($theme) . "'
		");

		if ($result AND $db->count_select() > 0) 
		{
			$row = $db->fetch_array($result);
			$theme = $row['folder'];
		} 
		else 
		{
			$theme = "defi";
		}
	}

	// ############################## START CSS ##############################
	// This is the primary CSS file for ArrowChat
	if ($type == "css") 
	{
		header ("Content-type: text/css; charset=UTF-8");
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600*24*7) . ' GMT');
		
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'css/fontawesome/css/all.min.css');

		if ($enable_rtl == 1)
		{
			$theme_css = file_get_contents(dirname(__FILE__) . '/themes/' . $theme . '/css/style.css');
			
			$theme_css = preg_replace_callback('/{(?!\/\*i\*\/)(.*?)}/i', 'rtl_callback', $theme_css);
			eval("?> $theme_css <?php ");
		}
		else
		{
			require_once (dirname(__FILE__) . '/themes/' . $theme . '/css/style.css');
		}
		
		if (file_exists(dirname(__FILE__) . '/cache/style_' . $theme . '.php'))
		{
			include_once (dirname(__FILE__) . '/cache/style_' . $theme . '.php');
			include_once (dirname(__FILE__) . '/themes/' . $theme . '/css/custom_css.php');
		}
		
		close_session();
		exit;
	}
	
	// ############################## START POPOUT CSS ##############################
	// This is the popout CSS file for ArrowChat
	if ($type == "popoutcss") 
	{
		header ("Content-type: text/css; charset=UTF-8");
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600*24*7) . ' GMT');
		
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'css/fontawesome/css/all.min.css');

		if ($enable_rtl == 1)
		{
			$theme_css = file_get_contents(dirname(__FILE__) . '/themes/' . $theme . '/css/style_popout.css');
			
			$theme_css = preg_replace_callback('/{(?!\/\*i\*\/)(.*?)}/i', 'rtl_callback', $theme_css);
			eval("?> $theme_css <?php ");
		}
		else
		{
			require_once (dirname(__FILE__) . '/themes/' . $theme . '/css/style_popout.css');
		}
		
		if (file_exists(dirname(__FILE__) . '/cache/style_' . $theme . '.php'))
		{
			include_once (dirname(__FILE__) . '/cache/style_' . $theme . '.php');
			include_once (dirname(__FILE__) . '/themes/' . $theme . '/css/custom_css.php');
		}
		
		close_session();
		exit;
	}

	// ############################## START DJS ##############################
	// These are all the dynamic variables that change on each load. This does not cache
	if ($type == "djs") 
	{
		header('Content-type: text/javascript; charset=UTF-8');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		
		// Mark user as no longer idle on load
		if ($status == "away" || $status == "busy") 
		{
			$db->execute("
				UPDATE arrowchat_status 
				SET status = 'available' 
				WHERE userid = '" . $db->escape_string($userid) . "'
			");
			
			$status = "available";
		}
		
		// Show chat bar regardless of maintenance if user is admin
		if ($is_admin == 1 AND $admin_view_maintenance == 1) 
		{
			$chat_maintenance = 0;
		}
		
		// Load another language if lang GET value is set and exists
		if (var_check('lang'))
		{
			$lang = get_var('lang');
			
			if (preg_match("#^[a-z]{2,20}$#i", $lang))
			{
				if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_LANGUAGE . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $lang . ".php"))
				{
					include (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_LANGUAGE . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $lang . ".php");
				}
			}
		}

		// Get the language
		foreach ($language as $key => $phrase) 
		{
			$settings .= 'lang[' . $key . '] = "' . $phrase . '";';
		}
		
		// Get all the themes
		for ($i = 0; $i < count($themes); $i++) 
		{
			$settings .= "Themes[" . $i . "] = ['" . implode("', '", $themes[$i]) . "'];";
		}
		
		$i=0;
		
		// Get all the smilies
		foreach ($smileys as $pattern => $result) 
		{
			$settings .= "Smiley[" . $i . "] = ['" . $result . "','" . $pattern . "'];";
			$i++;
		}
		
		// Put all the blocked users into an array
		if (!empty($block_chats))
		{
			$block_chats_unserialized = unserialize($block_chats);
			if (!is_array($block_chats_unserialized)) $block_chats_unserialized = array();
			$i=0;
			foreach ($block_chats_unserialized as $id) 
			{
				$settings .= "blockList['" . $id . "'] = ['" . $id . "'];";
				$i++;
			}
		}
		
		// Get all the chat windows and user details that are in focus
		$i = 0;
		$double_check = array();
		$focus_array = array();
		foreach($focus_chat as $key => $value)
		{
			if (!in_array($value, $double_check) AND !empty($value)) 
			{
				$doesnt_exist = false;
				
				if (substr($value, 0, 1) == "r")
				{
					$value = ltrim($value, 'r');
					
					// focused chat is a room
					// Start receive room details
					$result = $db->execute("
						SELECT name, description, welcome_message, image, type
						FROM arrowchat_chatroom_rooms 
						WHERE id = '" . $db->escape_string($value) . "'
					");
					
					if ($result AND $db->count_select() > 0) 
					{
						$row = $db->fetch_array($result);
						
						if (empty($row['image']))
							$image = "chatroom_default.png";
						else
							$image = $row['image'];
						
						$settings .= 'focus_chat[' . $i . '] = "' . $value . '";';
						$settings .= 'focus_is_room[' . $i . '] = "1";';
						$settings .= 'cr_name[' . $value . '] = "' . addslashes(strip_tags($row['name'])) . '";';
						$settings .= 'cr_desc[' . $value . '] = "' . addslashes($row['description']) . '";';
						$settings .= 'cr_welcome[' . $value . '] = "' . addslashes($row['welcome_message']) . '";';
						$settings .= 'cr_img[' . $value . '] = "' . addslashes($image) . '";';
						$settings .= 'cr_type[' . $value . '] = "' . addslashes($row['type']) . '";';
						$i++;
						$value = 'r' . $value;
						$focus_array[] = $value;
					}
					else
					{
						$doesnt_exist = true;
					}
					
					$double_check[] = $value;
				}
				else
				{
					// focused chat is a user
					// Start Receive User Details
					if (check_if_guest($value))
					{
						$sql = get_guest_details($value);
						$result = $db->execute($sql);
					}
					else
					{
						$sql = get_user_details($value);
						$result = $db->execute($sql);
					}
							
					if ($result AND $db->count_select() > 0) 
					{
						$chat = $db->fetch_array($result);

						if (((time()-$chat['lastactivity']) < $online_timeout) AND $chat['status'] != 'invisible' AND $chat['status'] != 'offline')
						{
							if ($chat['status'] != 'busy' AND $chat['status'] != 'away') 
							{
								$chat['status'] = 'available';
							}
						} 
						else 
						{
							$chat['status'] = 'offline';
						}
						
						if (check_if_guest($value))
						{
							$link = "#";
							$avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
							$chat['username'] = create_guest_username($value, $chat['guest_name']);
						}
						else
						{
							$link = get_link($chat['link'], $chat['userid']);
							$avatar = get_avatar($chat['avatar'], $chat['userid']);
						}
						
						if (empty($avatar)) $avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
						if (empty($link)) $link = "#";
						
						if (!empty($chat))
						{
							$settings .= 'focus_chat[' . $i . '] = "' . $value . '";';
							$settings .= 'focus_is_room[' . $i . '] = "0";';
							$settings .= 'uc_name["' . $value . '"] = "' . addslashes(strip_tags($chat['username'])) . '";';
							$settings .= 'uc_status["' . $value . '"] = "' . $chat['status'] . '";';
							$settings .= 'uc_avatar["' . $value . '"] = "' . addslashes($avatar) . '";';
							$settings .= 'uc_link["' . $value . '"] = "' . addslashes($link) . '";';
							$settings .= 'uc_message["' . $value . '"] = "' . addslashes(get_recent_message($chat['userid'])) . '";';
							$i++;
							$focus_array[] = $value;
						}
					}
					else
					{
						$doesnt_exist = true;
					}
					// End Receive User Details
					
					$double_check[] = $value;
				}
				
				if ($doesnt_exist)
				{
					$focus_array = $focus_chat;
					
					if (($key2 = array_search($value, $focus_array)) !== false) 
					{
						unset($focus_array[$key2]);
					}
					
					$focus_insert = serialize($focus_array);
					
					$db->execute("
						UPDATE arrowchat_status
						SET focus_chat = '" . $db->escape_string($focus_insert) . "'
						WHERE userid = '" . $db->escape_string($userid) . "'
					");
				}
			}
			
			// Do not allow more than 3 open chats or it will clutter page
			if ($i >= $allowed_open_windows)
				break;
		}
		
		// Get all the chat windows and user details that are not in focus
		$i = 0;
		$double_check = array();
		foreach($unfocus_chat as $key => $value)
		{
			if (!in_array($value, $double_check) AND !in_array($value, $focus_array) AND !empty($value)) 
			{
				$doesnt_exist = false;
				
				if (substr($value, 0, 1) == "r")
				{
					$value = ltrim($value, 'r');
					
					// focused chat is a room
					// Start receive room details
					$result = $db->execute("
						SELECT name, description, welcome_message, image, type
						FROM arrowchat_chatroom_rooms 
						WHERE id = '" . $db->escape_string($value) . "'
					");
					
					if ($result AND $db->count_select() > 0) 
					{
						$row = $db->fetch_array($result);
						
						if (empty($row['image']))
							$image = "chatroom_default.png";
						else
							$image = $row['image'];
						
						$settings .= 'unfocus_chat[' . $i . '] = "' . $value . '";';
						$settings .= 'unfocus_is_room[' . $i . '] = "1";';
						$settings .= 'cr_name[' . $value . '] = "' . addslashes(strip_tags($row['name'])) . '";';
						$settings .= 'cr_desc[' . $value . '] = "' . addslashes($row['description']) . '";';
						$settings .= 'cr_welcome[' . $value . '] = "' . addslashes($row['welcome_message']) . '";';
						$settings .= 'cr_img[' . $value . '] = "' . addslashes($image) . '";';
						$settings .= 'cr_type[' . $value . '] = "' . addslashes($row['type']) . '";';
						$i++;
					}
					else
					{
						$doesnt_exist = true;
					}
					
					$value = 'r' . $value;
					$double_check[] = $value;
				}
				else
				{
					// Start Receive User Details
					if (check_if_guest($value))
					{
						$sql = get_guest_details($value);
						$result = $db->execute($sql);
					}
					else
					{
						$sql = get_user_details($value);
						$result = $db->execute($sql);
					}
							
					if ($result AND $db->count_select() > 0) 
					{
						$chat = $db->fetch_array($result);

						if (((time()-$chat['lastactivity']) < $online_timeout) AND $chat['status'] != 'invisible' AND $chat['status'] != 'offline')
						{
							if ($chat['status'] != 'busy' AND $chat['status'] != 'away') 
							{
								$chat['status'] = 'available';
							}
						} 
						else 
						{
							$chat['status'] = 'offline';
						}
						
						if (check_if_guest($value))
						{
							$link = "#";
							$avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
							$chat['username'] = create_guest_username($value, $chat['guest_name']);
						}
						else
						{
							$link = get_link($chat['link'], $chat['userid']);
							$avatar = get_avatar($chat['avatar'], $chat['userid']);
						}
						
						if (empty($avatar)) $avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
						if (empty($link)) $link = "#";
						
						if (!empty($chat))
						{
							$settings .= 'unfocus_chat[' . $i . '] = "' . $value . '";';
							$settings .= 'unfocus_is_room[' . $i . '] = "0";';
							$settings .= 'uc_name["' . $value . '"] = "' . addslashes(strip_tags($chat['username'])) . '";';
							$settings .= 'uc_status["' . $value . '"] = "' . $chat['status'] . '";';
							$settings .= 'uc_avatar["' . $value . '"] = "' . addslashes($avatar) . '";';
							$settings .= 'uc_link["' . $value . '"] = "' . addslashes($link) . '";';
							$settings .= 'uc_message["' . $value . '"] = "' . addslashes(get_recent_message($chat['userid'])) . '";';
							$i++;
						}
					}
					else
					{
						$doesnt_exist = true;
					}
					// End Receive User Details
					
					$double_check[] = $value;
				}
				
				if ($doesnt_exist)
				{
					$unfocus_array = $unfocus_chat;
					
					if (($key2 = array_search($value, $unfocus_array)) !== false) 
					{
						unset($unfocus_array[$key2]);
					}
					
					$unfocus_insert = serialize($unfocus_array);
					
					$db->execute("
						UPDATE arrowchat_status
						SET unfocus_chat = '" . $db->escape_string($unfocus_insert) . "'
						WHERE userid = '" . $db->escape_string($userid) . "'
					");
				}
			}
			
			// Dangerous to allow users to open unlimited chats, so limit at 20
			if ($i >= $allowed_closed_windows)
				break;
		}
		
		// Get the logged in user's avatar
		if (check_if_guest($userid))
		{
			$user_username = create_guest_username($userid, $guest_name);
			$user_avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
			$user_is_guest = 1;
		}
		else
		{
			$user_is_guest = 0;
			$user_username = get_username($userid);
			
			$sql = get_user_details($userid);
			$result = $db->execute($sql);
			
			if ($result AND $db->count_select() > 0) 
			{
				$row = $db->fetch_array($result);
				$user_avatar = $row['avatar'];
				$user_avatar = get_avatar($user_avatar, $userid);
			}
			else
			{
				$user_avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
			}
		}
		
		$num_mod_reports = 0;
		if ($is_admin == 1) $is_mod = 1;
		if ($is_admin == 1 OR $is_mod == 1)
		{
			$result = $db->execute("
				SELECT COUNT(id)
				FROM arrowchat_reports
				WHERE (working_time < (" . time() . " - 600)
							OR working_by = '" . $db->escape_string($userid) . "')
					AND completed_time = 0
			");
		
			if ($row = $db->fetch_array($result))
			{
				$num_mod_reports = $row['COUNT(id)'];
			}
		}
		
		// Check and set group permissions
		$disable_sending_private_msg = 0;
		$disable_sending_group_msg = 0;
		
		if (check_array_for_match($group_id, $group_disable_video_sep))
			$video_chat = 0;

		if (check_array_for_match($group_id, $group_disable_rooms_sep))
			$chatrooms_on = 0;
		
		if (check_array_for_match($group_id, $group_disable_sending_private_sep))
			$disable_sending_private_msg = 1;
			
		if (check_array_for_match($group_id, $group_disable_sending_rooms_sep))
			$disable_sending_group_msg = 1;
		
		// Reverse group settings if enable mode is on
		if ($group_enable_mode == 1)
		{
			$disable_sending_private_msg = 1;
			$disable_sending_group_msg = 1;
			$video_chat = 0;
			$chatrooms_on = 0;
			
			if (check_array_for_match($group_id, $group_disable_video_sep))
				$video_chat = 1;
				
			if (check_array_for_match($group_id, $group_disable_rooms_sep))
				$chatrooms_on = 1;
			
			if (check_array_for_match($group_id, $group_disable_sending_private_sep))
				$disable_sending_private_msg = 0;
				
			if (check_array_for_match($group_id, $group_disable_responding_private_sep))
				$disable_sending_private_msg = 0;
				
			if (check_array_for_match($group_id, $group_disable_sending_rooms_sep))
				$disable_sending_group_msg = 0;
		}
		
		$db_connect = 0;
		if ($db->con == false)
		{
			$db_connect = 1;
		}
		
		// Welcome message display processing
		$first_time_message_viewed = 0;
		
		if ($welcome_viewed == 1)
		{
			$first_time_message_on = 0;
		}
		else
		{
			if ($first_time_message_on == 1)
			{
				if (isset($_COOKIE['arrowchat_welcome']))
				{
					$first_time_message_on = 0;
				}
				else
				{
					if (!empty($userid) AND $chat_maintenance != 1 AND $db_connect != 1) 
					{
						$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? '.' . $_SERVER['HTTP_HOST'] : false;
						setcookie('arrowchat_welcome', '1', time() + 1800, '/', $domain, false);
					}
				}
				
				$first_time_message_viewed = 1;
			}
		}
		
		// Get all the rest of the general settings
		$settings .= 'var T=0,';
		$settings .= 'u_theme="' . $theme . '",';
		$settings .= 'u_name="' . addslashes(strip_tags($user_username)) . '",';
		$settings .= 'u_id="' . $userid . '",';
		$settings .= 'u_group=' . json_encode($group_id) . ',';
		$settings .= 'u_blist_open="' . $window_open . '",';
		$settings .= 'u_sounds="' . $play_sound . '",';
		$settings .= 'u_chatroom_block_chats="' . $chatroom_block_chats . '",';
		$settings .= 'u_chatroom_invisible="' . $chatroom_invisible . '",';
		$settings .= 'u_status="' . $status . '",';
		$settings .= 'u_no_avatars="' . $only_names . '",';
		$settings .= 'u_hash_id="' . $hash_id . '",';
		$settings .= 'u_chatroom_sound="' . $chatroom_sound . '",';
		$settings .= 'u_chatroom_show_names="' . $chatroom_show_names . '",';
		$settings .= 'u_logged_in="' . $not_logged_in . '",';
		$settings .= 'u_popout_time="' . $popout . '",';
		$settings .= 'u_avatar="' . $user_avatar . '",';
		$settings .= 'u_is_guest="' . $user_is_guest . '",';
		$settings .= 'u_guest_name="' . $guest_name . '",';
		$settings .= 'u_is_mod="' . $is_mod . '",';
		$settings .= 'u_is_admin="' . $is_admin . '",';
		$settings .= 'u_num_mod_reports="' . $num_mod_reports . '",';
		$settings .= 'c_send_priv_msg="' . $disable_sending_private_msg . '",';
		$settings .= 'c_send_room_msg="' . $disable_sending_group_msg . '",';
		$settings .= 'c_chatrooms="' . $chatrooms_on . '",';
		$settings .= 'c_video_chat="' . $video_chat . '",';
		$settings .= 'c_notifications="' . $notifications_on . '",';
		$settings .= 'c_chat_maintenance="' . $chat_maintenance . '",';
		$settings .= 'c_guests_login_msg="' . $guests_can_view . '",';
		$settings .= 'c_us_time="' . $us_time . '",';
		$settings .= 'c_file_transfer="' . $file_transfer_on . '",';
		$settings .= 'c_chatroom_transfer="' . $chatroom_transfer_on . '",';
		$settings .= 'c_giphy="' . $giphy_off . '",';
		$settings .= 'c_giphy_chatroom="' . $giphy_chatroom_off . '",';
		$settings .= 'c_heart_beat="' . $heart_beat . '",';
		$settings .= 'c_list_heart_beat="' . $buddy_list_heart_beat . '",';
		$settings .= 'c_user_chatrooms="' . $user_chatrooms . '",';
		$settings .= 'c_disable_avatars="' . $disable_avatars . '",';
		$settings .= 'c_disable_arrowchat="' . $disable_arrowchat . '",';
		$settings .= 'c_show_full_name="' . $show_full_username . '",';
		$settings .= 'c_popout_on="' . $popout_chat_on . '",';
		$settings .= 'c_push_engine="' . $push_on . '",';
		$settings .= 'c_push_publish="' . $push_publish . '",';
		$settings .= 'c_push_subscribe="' . $push_subscribe . '",';
		$settings .= 'c_push_encrypt="' . $push_encrypt . '",';
		$settings .= 'c_mobile_device="' . $mobile_device . '",';
		$settings .= 'c_mobile_icon="' . $mobile_chat_icon . '",';
		$settings .= 'c_mobile_action="' . $mobile_chat_action . '",';
		$settings .= 'c_disable_smilies="' . $disable_smilies . '",';
		$settings .= 'c_guest_name_change="' . $guest_name_change . '",';
		$settings .= 'c_login_url="' . $login_url . '",';
		$settings .= 'c_admin_bg="' . $admin_background_color . '",';
		$settings .= 'c_admin_txt="' . $admin_text_color . '",';
		$settings .= 'c_max_upload_size="' . $max_upload_size . '",';
		$settings .= 'c_max_chatroom_msg="' . $chatroom_message_length . '",';
		$settings .= 'c_enable_moderation="' . $enable_moderation . '",';
		$settings .= 'c_push_ssl="' . $push_ssl . '",';
		$settings .= 'c_video_height="' . $video_chat_height . '",';
		$settings .= 'c_video_width="' . $video_chat_width . '",';
		$settings .= 'c_video_select="' . $video_chat_selection . '",';
		$settings .= 'c_online_list="' . $online_list_on . '",';
		$settings .= 'c_num_closed_windows="' . $num_closed_windows . '",';
		$settings .= 'c_window_left_padding="' . $window_left_padding . '",';
		$settings .= 'c_first_time_message_on="' . $first_time_message_on . '",';
		$settings .= 'c_first_time_message_header="' . addslashes($first_time_message_header) . '",';
		$settings .= 'c_first_time_message_content="' . addslashes($first_time_message_content) . '",';
		$settings .= 'c_first_time_message_viewed="' . $first_time_message_viewed . '",';
		$settings .= 'c_idle_time="' . $idle_time . '",';
		$settings .= 'c_db_connection="' . $db_connect . '",';
		$settings .= 'c_ac_path="' . $base_url . '";';		
			
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_dynamic.js');	
		
		close_session();
		exit;
	}

	// ############################## START JS ###############################
	// These are the core JavaScript files that will cache
	if ($type == "js") 
	{
		header('Content-type: text/javascript; charset=UTF-8');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600*24*7) . ' GMT');
		
		if ($mobile_device == 1)
		{	
			echo "// **********Main Script Start**********\n// http://www.arrowchat.com\n";
			echo "var c_enable_mobile='" . $enable_mobile . "';\n";
			require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_mobile.js');
		}
		else
		{
			// Inclue Template Files
			$file_notifications_tab				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/notifications_tab.php"));
			$file_notifications_window			= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/notifications_window.php"));
			$file_warnings_display				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/warnings_display.php"));
			$file_welcome_display				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/welcome_display.php"));
			$file_chat_tab						= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/chat_tab.php"));
			$file_unseen_chat_tab				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/unseen_chat_tab.php"));
			$file_chat_window					= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/chat_window.php"));
			$file_buddylist_tab					= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/buddylist_tab.php"));
			$file_buddylist_window				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/buddylist_window.php"));
			$file_maintenance_tab				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/maintenance_tab.php"));
			$file_announcements_display			= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/announcements_display.php"));
			$file_chatrooms_tab					= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/chatrooms_tab.php"));
			$file_chatrooms_window				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/chatrooms_window.php"));
			$file_chatrooms_room				= line_break_replace(get_include_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . "/template/chatrooms_room.php"));

			require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_libraries.js');
			
			echo "\n\n//**********Templates**********\n";
			require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_templates.js');
			
			echo "\n\n// **********Main Script Start**********\n// http://www.arrowchat.com\n";
			require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'js/arrowchat_core.js');
		}
		
		echo "\n/* ArrowChat Version: " . ARROWCHAT_VERSION . " */";
		
		close_session();
		exit;
	}

	// ############################## START POPOUT JS ###############################
	// This includes all the files required for the popout chat windows
	if ($type == "pjs") 
	{
		header('Content-type: text/javascript; charset=UTF-8');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600*24*7) . ' GMT');
			
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_PUBLIC . DIRECTORY_SEPARATOR . 'popout/js/popout_libraries.js');

		echo "\n\n// **********Main Script Start**********\n// http://www.arrowchat.com\n";
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_PUBLIC . DIRECTORY_SEPARATOR . 'popout/js/popout_core.js');
		
		close_session();
		exit;
	}
	
	// ############################## START MOBILE JS ###############################
	// This includes all the files required for the mobile chat
	if ($type == "mjs") 
	{
		header('Content-type: text/javascript; charset=UTF-8');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600*24*7) . ' GMT');
			
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_PUBLIC . DIRECTORY_SEPARATOR . 'mobile/includes/js/mobile_libraries.js');

		echo "\n\n// **********Main Script Start**********\n// http://www.arrowchat.com\n";
		require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . AC_FOLDER_PUBLIC . DIRECTORY_SEPARATOR . 'mobile/includes/js/mobile_core.js');
		
		close_session();
		exit;
	}

?>