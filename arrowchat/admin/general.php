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
	
	// Get the page to process
	if (empty($do))
	{
		$do = "chatsettings";
	}
	
	// ####################### START SUBMIT/POST DATA ########################
	$security_token = get_var('token');
	
	if (!empty($security_token))
	{
		if (hash_equals($_SESSION['token'], $security_token)) 
		{
			// Chat Features Submit Processor
			if (var_check('chatfeatures_submit')) 
			{
				if (!is_numeric(get_var('video_chat_width')) OR !is_numeric(get_var('video_chat_height')))
					$error = "The video chat width and height must be a number only.";
				
				if (empty($error))
				{
					$result = $db->execute("
						UPDATE arrowchat_config 
						SET config_value = CASE 
							WHEN config_name = 'chatrooms_on' THEN '" . get_var('chatrooms_on') . "'
							WHEN config_name = 'notifications_on' THEN '" . get_var('notifications_on') . "'
							WHEN config_name = 'popout_chat_on' THEN '" . get_var('popout_chat_on') . "'
							WHEN config_name = 'enable_mobile' THEN '" . get_var('enable_mobile') . "'
							WHEN config_name = 'video_chat' THEN '" . get_var('video_chat') . "'
							WHEN config_name = 'file_transfer_on' THEN '" . get_var('file_transfer_on') . "' 
							WHEN config_name = 'max_upload_size' THEN '" . get_var('max_upload_size') . "' 
							WHEN config_name = 'chatroom_transfer_on' THEN '" . get_var('chatroom_transfer_on') . "' 
							WHEN config_name = 'enable_moderation' THEN '" . get_var('enable_moderation') . "' 
							WHEN config_name = 'video_chat_selection' THEN '" . get_var('video_chat_selection') . "' 
							WHEN config_name = 'video_chat_width' THEN '" . get_var('video_chat_width') . "' 
							WHEN config_name = 'video_chat_height' THEN '" . get_var('video_chat_height') . "' 
							WHEN config_name = 'tokbox_api' THEN '" . get_var('tokbox_api') . "' 
							WHEN config_name = 'tokbox_secret' THEN '" . get_var('tokbox_secret') . "' 
							WHEN config_name = 'online_list_on' THEN '" . get_var('online_list_on') . "'
							WHEN config_name = 'first_time_message_on' THEN '" . get_var('first_time_message_on') . "'
							WHEN config_name = 'first_time_message_header' THEN '" . get_var('first_time_message_header') . "'
							WHEN config_name = 'first_time_message_content' THEN '" . get_var('first_time_message_content') . "'
							WHEN config_name = 'mobile_chat_icon' THEN '" . get_var('mobile_chat_icon') . "'
							WHEN config_name = 'mobile_chat_action' THEN '" . get_var('mobile_chat_action') . "'
							WHEN config_name = 'agora_app_id' THEN '" . get_var('agora_app_id') . "'
							WHEN config_name = 'agora_app_certificate' THEN '" . get_var('agora_app_certificate') . "'
						END WHERE config_name IN ('chatrooms_on', 'notifications_on', 'popout_chat_on', 'enable_mobile', 'video_chat', 'file_transfer_on', 'max_upload_size', 'chatroom_transfer_on', 'enable_moderation', 'video_chat_selection', 'video_chat_width', 'video_chat_height', 'tokbox_api', 'tokbox_secret', 'online_list_on', 'first_time_message_on', 'first_time_message_header', 'first_time_message_content', 'mobile_chat_icon', 'mobile_chat_action', 'agora_app_id', 'agora_app_certificate')
					");
								
					if ($result) 
					{
						$chatrooms_on = get_var('chatrooms_on');
						$notifications_on = get_var('notifications_on');
						$popout_chat_on = get_var('popout_chat_on');
						$enable_mobile = get_var('enable_mobile');
						$video_chat = get_var('video_chat');
						$file_transfer_on = get_var('file_transfer_on');
						$max_upload_size = get_var('max_upload_size');
						$chatroom_transfer_on = get_var('chatroom_transfer_on');
						$enable_moderation = get_var('enable_moderation');
						$video_chat_selection = get_var('video_chat_selection');
						$video_chat_width = get_var('video_chat_width');
						$video_chat_height = get_var('video_chat_height');
						$tokbox_api = get_var('tokbox_api');
						$tokbox_secret = get_var('tokbox_secret');
						$online_list_on = get_var('online_list_on');
						$first_time_message_on = get_var('first_time_message_on');
						$first_time_message_header = get_var('first_time_message_header');
						$first_time_message_content = get_var('first_time_message_content');
						$mobile_chat_icon = get_var('mobile_chat_icon');
						$mobile_chat_action = get_var('mobile_chat_action');
						$agora_app_id = get_var('agora_app_id');
						$agora_app_certificate = get_var('agora_app_certificate');
					
						update_config_file();
						$msg = "Your settings were successfully saved.";
					} 
					else
					{
						$error = "There was a database error.  Please try again.";
					}
				}
			}
			
			// Chat Settings Submit Processor
			if (var_check('chatsettings_submit')) 
			{
				$guest_name_bad_words = trim(get_var('guest_name_bad_words'));
				if (substr($guest_name_bad_words, -1, 1) == ",") $guest_name_bad_words = substr($guest_name_bad_words, 0, -1);
				
				$blocked_words = trim(get_var('blocked_words'));
				if (substr($blocked_words, -1, 1) == ",") $blocked_words = substr($blocked_words, 0, -1);
				
				$result = $db->execute("
					UPDATE arrowchat_config 
					SET config_value = CASE 
						WHEN config_name = 'disable_avatars' THEN '" . get_var('disable_avatars') . "'
						WHEN config_name = 'disable_smilies' THEN '" . get_var('disable_smilies') . "'
						WHEN config_name = 'disable_arrowchat' THEN '" . get_var('disable_arrowchat') . "'
						WHEN config_name = 'disable_buddy_list' THEN '" . get_var('disable_buddy_list') . "'
						WHEN config_name = 'chat_maintenance' THEN '" . get_var('chat_maintenance') . "' 
						WHEN config_name = 'admin_chat_all' THEN '" . get_var('admin_chat_all') . "'
						WHEN config_name = 'admin_view_maintenance' THEN '" . get_var('admin_view_maintenance') . "'
						WHEN config_name = 'guests_can_view' THEN '" . get_var('guests_can_view') . "'
						WHEN config_name = 'guests_can_chat' THEN '" . get_var('guests_can_chat') . "'
						WHEN config_name = 'guests_chat_with' THEN '" . get_var('guests_chat_with') . "'
						WHEN config_name = 'guest_name_change' THEN '" . get_var('guest_name_change') . "'
						WHEN config_name = 'guest_name_duplicates' THEN '" . get_var('guest_name_duplicates') . "'
						WHEN config_name = 'guest_name_bad_words' THEN '" . $guest_name_bad_words . "'
						WHEN config_name = 'users_chat_with' THEN '" . get_var('users_chat_with') . "'
						WHEN config_name = 'show_full_username' THEN '" . get_var('show_full_username') . "'
						WHEN config_name = 'us_time' THEN '" . get_var('us_time') . "'
						WHEN config_name = 'hide_admins_buddylist' THEN '" . get_var('hide_admins_buddylist') . "'
						WHEN config_name = 'blocked_words' THEN '" . $blocked_words . "'
						WHEN config_name = 'giphy_off' THEN '" . get_var('giphy_off') . "'
						WHEN config_name = 'giphy_chatroom_off' THEN '" . get_var('giphy_chatroom_off') . "'
						WHEN config_name = 'enable_rtl' THEN '" . get_var('enable_rtl') . "'
					END WHERE config_name IN ('disable_avatars', 'disable_smilies', 'disable_arrowchat', 'disable_buddy_list', 'chat_maintenance', 'admin_chat_all', 'admin_view_maintenance', 'guests_can_view', 'guests_can_chat', 'guests_chat_with', 'guest_name_change', 'guest_name_duplicates', 'guest_name_bad_words', 'users_chat_with', 'show_full_username', 'us_time', 'hide_admins_buddylist', 'blocked_words', 'giphy_off', 'giphy_chatroom_off', 'enable_rtl')
				");
							
				if ($result) 
				{	
					$disable_avatars = get_var('disable_avatars');
					$disable_smilies = get_var('disable_smilies');
					$disable_arrowchat = get_var('disable_arrowchat');
					$disable_buddy_list = get_var('disable_buddy_list');
					$chat_maintenance = get_var('chat_maintenance');
					$admin_chat_all = get_var('admin_chat_all');
					$admin_view_maintenance = get_var('admin_view_maintenance');
					$guests_can_view = get_var('guests_can_view');
					$guests_can_chat = get_var('guests_can_chat');
					$guest_name_change = get_var('guest_name_change');
					$guest_name_duplicates = get_var('guest_name_duplicates');
					$guests_chat_with = get_var('guests_chat_with');
					$users_chat_with = get_var('users_chat_with');
					$show_full_username = get_var('show_full_username');
					$us_time = get_var('us_time');
					$hide_admins_buddylist = get_var('hide_admins_buddylist');
					$giphy_off = get_var('giphy_off');
					$giphy_chatroom_off = get_var('giphy_chatroom_off');
					$enable_rtl = get_var('enable_rtl');

					update_config_file();
					include_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . AC_FOLDER_CACHE . DIRECTORY_SEPARATOR . 'data_admin_options.php');
					$msg = "Your settings were successfully saved.";
				} 
				else
				{
					$error = "There was a database error.  Please try again.";
				}
			}
			
			// Chat Style Submit Processor
			if (var_check('chatstyle_submit')) 
			{
					
				if (empty($error)) 
				{
					if (get_var('num_closed_windows') > 10 OR get_var('num_closed_windows') < 2)
					{
						$error = "Your number of closed windows is either above 10 or under 2.  Please correct the value.";
					}
					
					if (get_var('window_left_padding') > 300 OR get_var('window_left_padding') < 30)
					{
						$error = "Your window left padding number is either above 300 or under 30.  Please correct the value.";
					}
					
					if (get_var('allowed_closed_windows') > 40 OR get_var('allowed_closed_windows') < 1)
					{
						$error = "Your remembered closed windows number is either above 40 or under 1.  Please correct the value.";
					}
					
					if (get_var('allowed_open_windows') > 10 OR get_var('allowed_open_windows') < 1)
					{
						$error = "Your remembered open windows number is either above 10 or under 1.  Please correct the value.";
					}
				}
			
				if (empty($error)) 
				{
					$result = $db->execute("
						UPDATE arrowchat_config 
						SET config_value = CASE 
							WHEN config_name = 'num_closed_windows' THEN '" . get_var('num_closed_windows') . "'
							WHEN config_name = 'window_left_padding' THEN '" . get_var('window_left_padding') . "'
							WHEN config_name = 'admin_background_color' THEN '" . get_var('admin_background_color') . "'
							WHEN config_name = 'admin_text_color' THEN '" . get_var('admin_text_color') . "'
							WHEN config_name = 'allowed_closed_windows' THEN '" . get_var('allowed_closed_windows') . "'
							WHEN config_name = 'allowed_open_windows' THEN '" . get_var('allowed_open_windows') . "'
						END WHERE config_name IN ('num_closed_windows', 'window_left_padding', 'admin_background_color', 'admin_text_color', 'allowed_closed_windows', 'allowed_open_windows')
					");
								
					if ($result) 
					{
						$num_closed_windows = get_var('num_closed_windows');
						$window_left_padding = get_var('window_left_padding');
						$admin_background_color = get_var('admin_background_color');
						$admin_text_color = get_var('admin_text_color');
						$allowed_closed_windows = get_var('allowed_closed_windows');
						$allowed_open_windows = get_var('allowed_open_windows');
						
						update_config_file();
						$msg = "Your settings were successfully saved.";
					} 
					else
					{
						$error = "There was a database error.  Please try again.";
					}	
				}
			}
		}
		else
		{
			die("No valid token");
		}
	}
	
	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_header.php");
	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_general.php");
	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_footer.php");
	
?>