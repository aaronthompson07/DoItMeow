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
	require_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'bootstrap.php');
	require_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');

	// ########################### INITILIZATION #############################
	$response 			= array();
	$chat_users			= array();
	$time 				= time();
	$user_title			= array();
	$chatroom_data		= array();
	$chatrooms			= get_var('chatrooms');
	
	if (!empty($chatrooms))
	{
		$unfocused_tabs_array = explode(",", $chatrooms);
	}
	else
	{
		close_session();
		exit(0);
	}

	// ###################### START CHATROOM BANLIST CHECK ###################
	if (logged_in($userid))
	{
		$result = $db->execute("
			SELECT ban_length, ban_time, chatroom_id
			FROM arrowchat_chatroom_banlist 
			WHERE (user_id = '" . $db->escape_string($userid) . "'
				OR ip_address = '" . $db->escape_string($user_ip) . "')
			ORDER BY ban_time DESC
		");
		
		if ($result AND $db->count_select() > 0) 
		{
			while ($row = $db->fetch_array($result)) 
			{
				if ((empty($row['ban_length']) OR ((($row['ban_length'] * 60) + $row['ban_time']) > time())) AND $is_admin != 1) 
				{
					$chatroom_id = $row['chatroom_id'];
					
					if (in_array($chatroom_id, $unfocused_tabs_array))
					{
						// Fail-safe if name isn't retrieved
						$chatroom_name = "a chat room";
						
						$result2 = $db->execute("
							SELECT name
							FROM arrowchat_chatroom_rooms 
							WHERE id = '" . $db->escape_string($chatroom_id) . "'
						");
						
						if ($result2 AND $db->count_select() > 0) 
						{
							$row2 = $db->fetch_array($result2);
							$chatroom_name = $row2['name'];
						}
						
						if (empty($row['ban_length']))
						{
							$chatroom_data[] = array('id' => $chatroom_id, 'error' => '3', 'error_msg' => $language[55] . $chatroom_name, 'chat_users' => '0', 'user_title' => '0');
						}
						else
						{
							$chatroom_data[] = array('id' => $chatroom_id, 'error' => '3', 'error_msg' => $language[56] . $chatroom_name . $language[219] . $row['ban_length'] . $language[220], 'chat_users' => '0', 'user_title' => '0');
						}
						
						// Remove the chat room from the array since the user is banned
						if (($key = array_search($chatroom_id, $unfocused_tabs_array)) !== false){
							unset($unfocused_tabs_array[$key]);
						}
					}
				}
			}
		}
	}

	// ##################### START CHATROOM USERS RECEIVE ####################
	if (logged_in($userid)) 
	{	
		foreach ($unfocused_tabs_array as $key => $value)
		{
			if (is_numeric($value))
			{
				$global_is_mod		= 0;
				$global_is_admin	= 0;
				$chatroom_id 		= $value;
				$chat_users			= array();
				
				$db->execute("
					INSERT INTO arrowchat_chatroom_users (user_id,chatroom_id,session_time) 
					VALUES ('" . $db->escape_string($userid) . "', '" . $db->escape_string($chatroom_id) . "', '" . $time . "') 
					ON DUPLICATE KEY 
						UPDATE chatroom_id = '" . $db->escape_string($chatroom_id) . "', session_time = '" . $time . "'
				");
				
				$result = $db->execute("
					SELECT user_id, is_admin, is_mod, block_chats, is_invisible 
					FROM arrowchat_chatroom_users
					WHERE (chatroom_id = '" . $db->escape_string($chatroom_id) . "'
						AND session_time > (" . $time . " - 61))
					ORDER BY is_admin DESC, is_mod DESC, session_time DESC");
				
				while ($chatroom_users = $db->fetch_array($result)) 
				{
					$title = 4;
					
					if ($chatroom_users['is_mod'] == "1")
					{
						$title = 2;
					}
					
					if ($chatroom_users['is_admin'] == "1")
					{
						$title = 3;
					}

					$fetchid = $chatroom_users['user_id'];
					
					if ($fetchid == $userid) 
					{
						if ($title==2) 
						{
							$global_is_mod = 1;
						}
						
						if ($title==3) 
						{
							$global_is_admin = 1;
						}
					}
					
					if (check_if_guest($fetchid))
					{
						if ($title == 4)
						{
							$title = 1;
						}
						$sql = get_guest_details($fetchid);
						$result2 = $db->execute($sql);
						$user = $db->fetch_array($result2);
						
						$user['username'] = create_guest_username($user['userid'], $user['guest_name']);
						$link = "#";
						$avatar = $base_url . AC_FOLDER_ADMIN . "/images/img-no-avatar.png";
					}
					else
					{
						$sql = get_user_details($fetchid);
						$result3 = $db->execute($sql);
						$user = $db->fetch_array($result3);
						
						$avatar	= get_avatar($user['avatar'], $fetchid);
						$link	= get_link($user['link'], $fetchid);
					}
					
					if (((time()-$user['lastactivity']) < $online_timeout) AND $user['status'] != 'invisible' AND $user['status'] != 'offline') 
					{
						if ($user['status'] != 'busy' AND $user['status'] != 'away') 
						{
							$user['status'] = 'available';
						}
					} 
					else 
					{
						$user['status'] = 'available';
					}
					
					if ($chatroom_users['is_invisible'] == 1 AND $chatroom_users['is_admin'] == 1) 
					{
						$user['status'] = 'invisible';
					}
					
					$chat_users[] = array('id' => $user['userid'], 'n' => strip_tags($user['username']), 'a' => $avatar, 'l' => $link, 't' => $title, 'b' => $chatroom_users['block_chats'], 'status' => $user['status']);
				}
				
				$user_title = array('admin' => $global_is_admin, 'mod' => $global_is_mod);
				$chatroom_data[] = array('id' => $chatroom_id, 'error' => '0', 'error_msg' => '0', 'chat_users' => $chat_users, 'user_title' => $user_title);
			}
		}
		
		$response['room_data'] = $chatroom_data;
	}

	header('Content-type: application/json; charset=UTF-8');
	echo json_encode($response);
	close_session();
	exit;

?>