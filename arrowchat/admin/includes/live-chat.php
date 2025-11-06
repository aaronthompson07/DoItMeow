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
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "admin_init.php");
	
	// ########################### INITILIZATION #############################
	$response = array();
	$messages = array();

	// ####################### START SUBMIT/POST DATA ########################
	if (var_check('id') && var_check('w'))
	{	
		$result2 = $db->execute("
			SELECT " . DB_USERTABLE_NAME . ", " . DB_USERTABLE_USERID . " 
			FROM " . TABLE_PREFIX . DB_USERTABLE . " 
			WHERE " . DB_USERTABLE_USERID . " = '" . $db->escape_string(get_var('w')) . "'
		");

		$result3 = $db->execute("
			SELECT " . DB_USERTABLE_NAME . ", " . DB_USERTABLE_USERID . " 
			FROM " . TABLE_PREFIX . DB_USERTABLE . " 
			WHERE " . DB_USERTABLE_USERID . " = '" . $db->escape_string(get_var('id')) . "'
		");
		
		$result = $db->execute("
			SELECT * 
			FROM arrowchat 
			WHERE (arrowchat.to = '" . $db->escape_string(get_var('w')) . "' 
					AND arrowchat.from = '" . $db->escape_string(get_var('id')) . "') 
				OR (arrowchat.to = '" . $db->escape_string(get_var('id')) . "' 
					AND arrowchat.from = '" . $db->escape_string(get_var('w')) . "') 
			ORDER BY id ASC
		");

		if ($result AND $db->count_select() > 0) 
		{
			$row2 = $db->fetch_array($result2);
			$row3 = $db->fetch_array($result3);
			
			while ($row = $db->fetch_array($result)) 
			{
				if ($row['sent'] > time() - 10)
				{
					if ($row['from'] == get_var('w')) 
					{
						if (check_if_guest(get_var('w')))
						{
							$msg_username = $language[83] . " " . substr(get_var('w'), 1);
						}
						else
						{
							$msg_username = $row2[DB_USERTABLE_NAME];
						}
						
						$self = 0;
					} 
					else 
					{
						if (check_if_guest(get_var('id')))
						{
							$msg_username = $language[83] . " " . substr(get_var('id'), 1);
						}
						else
						{
							$msg_username = $row3[DB_USERTABLE_NAME];
						}
						
						$self = 1;
					}
				
					$chat_message = $row['message'];
					$chat_message = str_replace("\\'", "'", $chat_message);
					$chat_message = str_replace('\\"', '"', $chat_message);
					$chat_message = clickable_links($chat_message);
					
					$msg_username = str_replace("\\'", "'", $msg_username);
					$msg_username = str_replace('\\"', '"', $msg_username);
					
					$date = date('M j, Y g:i a', $row['sent']);
					
					$messages[] = array('id' => $row['id'], 'from' => $row['from'], 'message' => $chat_message, 'self' => $self,  'sent' => $date, 'username' => $msg_username);
				}
			}
		}
		
		if (!empty($messages))
		{
			$response['messages'] = $messages;
		}
	}
	
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($response);
	close_session();
	exit;
?>