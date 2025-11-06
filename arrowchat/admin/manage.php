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
		$do = "appsettings";
	}
		
	// ####################### START SUBMIT/POST DATA ########################
	$security_token = get_var('token');
	
	if (!empty($security_token)) 
	{
		if (hash_equals($_SESSION['token'], $security_token)) 
		{	
			// Chatroom Settings Submit Processor
			if (var_check('chatroom_settings_submit')) 
			{				
				$result = $db->execute("
					UPDATE arrowchat_config 
					SET config_value = CASE 
						WHEN config_name = 'user_chatrooms' THEN '" . $db->escape_string(get_var('user_chatrooms')) . "'
						WHEN config_name = 'user_chatrooms_flood' THEN '" . $db->escape_string(get_var('user_chatrooms_flood')) . "'
						WHEN config_name = 'user_chatrooms_length' THEN '" . $db->escape_string(get_var('user_chatrooms_length')) . "'
						WHEN config_name = 'chatroom_history_length' THEN '" . $db->escape_string(get_var('chatroom_history_length')) . "'
						WHEN config_name = 'chatroom_message_length' THEN '" . $db->escape_string(get_var('chatroom_message_length')) . "'
					END WHERE config_name IN ('user_chatrooms', 'user_chatrooms_flood', 'user_chatrooms_length', 'chatroom_history_length', 'chatroom_message_length')
				");
							
				if ($result) 
				{
					$user_chatrooms = get_var('user_chatrooms');
					$user_chatrooms_flood = get_var('user_chatrooms_flood');
					$user_chatrooms_length = get_var('user_chatrooms_length');
					$chatroom_history_length = get_var('chatroom_history_length');
					$chatroom_message_length = get_var('chatroom_message_length');
					
					update_config_file();
					$msg = "Your settings were successfully saved.";
				} 
				else
				{
					$error = "There was a database error.  Please try again.";
				}
			}
			
			// Add Chatroom Processor
			if (var_check('add_chatroom_submit')) 
			{
				if (empty($_POST['add_chatroom_name'])) 
				{
					$error = "You must enter a name for this chatroom.";
				}
				
				if (empty($_POST['add_chatroom_desc'])) 
				{
					$error = "You must enter a description for this chatroom.";
				}
				
				if (empty($_POST['add_chatroom_length']) AND $_POST['add_chatroom_length'] != "0") 
				{
					$error = "You must enter a length for this chatroom.";
				}
				
				if (empty($_POST['add_chatroom_type'])) 
				{
					$error = "You must enter a type for this chatroom.";
				}
				
				if ($_POST['add_chatroom_type'] == 2 AND empty($_POST['add_chatroom_password'])) 
				{
					$error = "You must specify a password for a password protected chatroom.";
				}
				
				if (!is_numeric($_POST['add_chatroom_length'])) 
				{
					$error = "The chatroom length must be a number only.  Specify in minutes.";
				}
				
				if (!is_numeric($_POST['chatroom_max_users']))
				{
					$error = "The chatroom max users must be a number only.  Enter 0 for unlimited users.";
				}
				
				if (empty($_POST['limit_seconds_num']) OR empty($_POST['limit_message_num']) OR !is_numeric($_POST['limit_seconds_num']) OR !is_numeric($_POST['limit_message_num'])) 
				{
					$error = "The chat room flood selection is empty of invalid.";
				}
				
				if (empty($_POST['add_chatroom_img']) AND empty($_FILES['add_chatroom_img_upload']['size'])) 
				{
					$error = "You must enter or upload an icon for this chat room.";
				}
				
				if (!empty($_POST['add_chatroom_img']))
				{
					$icon_filename = $_POST['add_chatroom_img'];
				}
				
				if (!empty($_FILES['add_chatroom_img_upload']['size']))
				{
					if (($_FILES['add_chatroom_img_upload']['type'] != "image/gif") AND ($_FILES['add_chatroom_img_upload']['type'] != "image/jpeg") AND ($_FILES['add_chatroom_img_upload']['type'] != "image/pjpeg") AND ($_FILES['add_chatroom_img_upload']['type'] != "image/png"))
					{
						$error = "The image must be gif, jpeg or png.";
					}
					
					if ($_FILES['add_chatroom_img_upload']['size'] > 500000)
					{
						$error = "The image must be under 500kb.";
					}
					
					if ($_FILES['add_chatroom_img_upload']['error'] > 0)
					{
						$error = "There was a problem with the upload.  Error code: " . $_FILES['add_chatroom_img_upload']['error'];
					}
					
					if (empty($error))
					{
						$icon_filename = $_FILES['add_chatroom_img_upload']['name'];
					}
				}
				
				if (empty($error)) 
				{		
					if (!empty($_FILES['add_chatroom_img_upload']['size']))
					{
						move_uploaded_file($_FILES['add_chatroom_img_upload']['tmp_name'], dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . AC_FOLDER_THEMES . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "icons" . DIRECTORY_SEPARATOR . $_FILES['add_chatroom_img_upload']['name']);
					}
				
					$result = $db->execute("
						INSERT INTO arrowchat_chatroom_rooms (
							author_id, 
							name, 
							description,
							welcome_message,
							image,
							type, 
							password, 
							length, 
							is_featured,
							max_users,
							session_time,
							limit_message_num,
							limit_seconds_num,
							disallowed_groups
						) 
						VALUES (
							'" . $db->escape_string($userid) . "', 
							'" . $db->escape_string(get_var('add_chatroom_name')) . "', 
							'" . $db->escape_string(get_var('add_chatroom_desc')) . "', 
							'" . $db->escape_string(get_var('add_chatroom_welcome_msg')) . "', 
							'" . $db->escape_string($icon_filename) . "',
							'" . $db->escape_string(get_var('add_chatroom_type')) . "', 
							'" . $db->escape_string(get_var('add_chatroom_password')) . "', 
							'" . $db->escape_string(get_var('add_chatroom_length')) . "',
							'" . $db->escape_string(get_var('is_featured')) . "',
							'" . $db->escape_string(get_var('chatroom_max_users')) . "',
							'" . time() . "',
							'" . $db->escape_string(get_var('limit_message_num')) . "',
							'" . $db->escape_string(get_var('limit_seconds_num')) . "',
							'" . $db->escape_string(serialize(get_var('add_chatroom_group'))) . "'
						)
					");

					if ($result) 
					{
						$msg = "Your chatroom has been created successfully.";
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
			}
			
			// Delete Chatroom Processor
			if (var_check('delete') AND $do == "chatroomsettings") 
			{
				if (empty($_GET['delete'])) 
				{
					$error = "There was no chatroom ID to delete.";
				}
				
				if (empty($error)) 
				{
					$result = $db->execute("
						DELETE FROM arrowchat_chatroom_rooms 
						WHERE id = '" . $db->escape_string(get_var('delete')) . "'
					");

					if ($result) 
					{
						$msg = "Your chatroom was deleted successfully.";
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
			}
			
			// Edit Chatroom Processor
			if (var_check('chatroom_edit_submit')) 
			{
				if (empty($_POST['edit_chatroom_name'])) 
				{
					$error = "You must enter a name for this chatroom.";
				}
				
				if (empty($_POST['edit_chatroom_desc'])) 
				{
					$error = "You must enter a description for this chatroom.";
				}
				
				if (empty($_POST['edit_chatroom_img'])) 
				{
					$error = "You must enter an icon for this chatroom.";
				}
				
				if (empty($_POST['edit_chatroom_length']) AND $_POST['edit_chatroom_length'] != "0") 
				{
					$error = "You must enter a length for this chatroom.";
				}
				
				if (empty($_POST['edit_chatroom_type'])) 
				{
					$error = "You must enter a type for this chatroom.";
				}
				
				if ($_POST['edit_chatroom_type'] == 2 AND empty($_POST['edit_chatroom_password'])) 
				{
					$error = "You must specify a password for a password protected chatroom.";
				}
				
				if (!is_numeric($_POST['edit_chatroom_length'])) 
				{
					$error = "The chatroom length must be a number only.  Specify in minutes.";
				}
				
				if (!is_numeric($_POST['chatroom_max_users']))
				{
					$error = "The chatroom max users must be a number only.  Enter 0 for unlimited users.";
				}
				
				if (empty($_POST['limit_seconds_num']) OR empty($_POST['limit_message_num']) OR !is_numeric($_POST['limit_seconds_num']) OR !is_numeric($_POST['limit_message_num'])) 
				{
					$error = "The chat room flood selection is empty of invalid.";
				}
				
				if (empty($error)) 
				{	
					$usernames = get_var('unban_username');
					
					if ($usernames) 
					{
						foreach ($usernames as $unbans) 
						{
							$db->execute("
								DELETE FROM arrowchat_chatroom_banlist 
								WHERE user_id = '" . $db->escape_string($unbans) . "'
									AND chatroom_id = '" . $db->escape_string(get_var('chatroom_id')) . "'
							");
						}
					} 
					
					$unmod_usernames = get_var('remove_mod');
					
					if ($unmod_usernames) 
					{
						foreach ($unmod_usernames as $unmods) 
						{
							$db->execute("
								UPDATE arrowchat_chatroom_users
								SET is_admin = '0',
									is_mod = '0'
								WHERE user_id = '" . $db->escape_string($unmods) . "'
									AND chatroom_id = '" . $db->escape_string(get_var('chatroom_id')) . "'
							");
						}
					} 
					
					$result = $db->execute("
						UPDATE arrowchat_chatroom_rooms 
						SET name = '" . $db->escape_string(get_var('edit_chatroom_name')) . "', 
							description = '" . $db->escape_string(get_var('edit_chatroom_desc')) . "', 
							welcome_message = '" . $db->escape_string(get_var('edit_chatroom_welcome_msg')) . "', 
							image = '" . $db->escape_string(get_var('edit_chatroom_img')) . "', 
							type = '" . $db->escape_string(get_var('edit_chatroom_type')) . "', 
							password = '" . $db->escape_string(get_var('edit_chatroom_password')) . "', 
							length = '" . $db->escape_string(get_var('edit_chatroom_length')) . "',
							is_featured = '" . $db->escape_string(get_var('edit_is_featured')) . "',
							max_users = '" . $db->escape_string(get_var('chatroom_max_users')) . "',
							limit_message_num = '" . $db->escape_string(get_var('limit_message_num')) . "',
							limit_seconds_num = '" . $db->escape_string(get_var('limit_seconds_num')) . "',
							disallowed_groups = '" . $db->escape_string(serialize(get_var('edit_chatroom_group'))) . "'
						WHERE id = '" . $db->escape_string(get_var('chatroom_id')) . "'
					");

					if ($result) 
					{
						$msg = "Your chatroom was updated successfully.";
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
				
				$_GET['id'] = get_var('link_id');
			}
			
			// Add Notification Processor
			if (var_check('add_notification_submit')) 
			{
				if (empty($_POST['add_notification_name'])) 
				{
					$error = "You must enter a name for this notification.";
				}
				
				if (empty($_POST['add_notification_markup'])) 
				{
					$error = "You must enter markup for this notification.";
				}
				
				if (empty($error)) 
				{
					$result = $db->execute("
						SELECT MAX(type) 
						FROM arrowchat_notifications_markup
					");
					$row = $db->fetch_array($result);
					
					if (empty($row['MAX(type)']))
					{
						$type = 1;
					}
					else
					{
						$type = $row['MAX(type)'] + 1;
					}
					
					$markup_data = str_replace("\\r\\n", "
		",  get_var('add_notification_markup'));
						
					$result = $db->execute("
						INSERT INTO arrowchat_notifications_markup (
							name, 
							type, 
							markup
						) 
						VALUES (
							'" . $db->escape_string(get_var('add_notification_name')) . "', 
							'" . $db->escape_string($type) . "', 
							'" . $db->escape_string(stripslashes($markup_data)) . "'
						)
					");

					if ($result) 
					{
						$msg = "Your notification was added successfully with type number " . $db->escape_string($type) . ".  You must add a mySQL statement where the action occurs before the notification will work.  Consult the ArrowChat documentation for more information.";
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
			}
			
			// Delete Notifications Processor
			if (var_check('delete') AND $do == "notificationsettings") 
			{
				if (empty($_GET['delete'])) 
				{
					$error = "There was no notification ID to delete.";
				}
				
				if (empty($error)) 
				{
					$result = $db->execute("
						DELETE FROM arrowchat_notifications_markup 
						WHERE id = '" . $db->escape_string(get_var('delete')) . "'
					");

					if ($result) 
					{
						$msg = "Your notification was deleted successfully.  You should delete the mySQL query when this action is called to avoid unnecessary database calls.";
						update_config_file();
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
			}
			
			// Edit Notifications Processor
			if (var_check('notification_edit_submit')) 
			{
				if (empty($_POST['edit_notification_name'])) 
				{
					$error = "You must enter a name for this notification.";
				}
				
				if (empty($_POST['edit_notification_markup'])) 
				{
					$error = "You must enter markup for this notification.";
				}
				
				if (empty($error)) 
				{
					$markup_data = str_replace("\\r\\n", "
		",  get_var('edit_notification_markup'));

					$result = $db->execute("
						UPDATE arrowchat_notifications_markup 
						SET name = '" . $db->escape_string(get_var('edit_notification_name')) . "', 
							markup = '" . $db->escape_string(stripslashes($markup_data)) . "' 
						WHERE id = '" . $db->escape_string(get_var('notification_id')) . "'
					");

					if ($result) 
					{
						$msg = "Your notification was updated successfully.";
					} 
					else 
					{
						$error = "There was a database error.  Please try again.";
					}
				}
				
				$_GET['id'] = get_var('link_id');
			}
		}
		else
		{
			die("No valid token");
		}
	}

	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_header.php");
	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_manage.php");
	require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "layout/pages_footer.php");

?>