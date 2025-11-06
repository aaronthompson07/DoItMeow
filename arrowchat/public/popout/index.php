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
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'bootstrap.php');
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'functions/functions_mobile.php');

	$load_chatroom_id		= get_var('cid');
	$autohide_panel			= get_var('ah');
	$select_chatroom		= get_var('sc');
	
	if (!is_numeric($load_chatroom_id))
		$load_chatroom_id = 0;
		
	if ($autohide_panel != 1 AND $autohide_panel != 0)
		$autohide_panel = 0;
		
	if ($select_chatroom != 1 AND $select_chatroom != 0)
		$select_chatroom = 0;
		
	$detect = new Mobile_Detect;
	if ($detect->isMobile() AND ARROWCHAT_EDITION != "starter" AND ARROWCHAT_EDITION != "free") 
	{
		if (!empty($load_chatroom_id))
		{
			header("Location: " . $base_url . AC_FOLDER_PUBLIC . "/mobile/#room-" . $load_chatroom_id);
		}
		else
		{
			header("Location: " . $base_url . AC_FOLDER_PUBLIC . "/mobile/");
		}
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

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-gb" xml:lang="en-gb"> 
<head> 

	<title><?php echo $language[110]; ?></title>
	
	<script type="text/javascript" src="<?php echo $base_url; ?>autoload.php?v=popout" charset="utf-8"></script>
	<script type="text/javascript">
		var ac_load_chatroom_id = <?php echo htmlspecialchars($load_chatroom_id); ?>;
		var ac_autohide_panel = <?php echo htmlspecialchars($autohide_panel); ?>;
		var ac_select_chatroom = <?php echo htmlspecialchars($select_chatroom); ?>;
	</script>
	
	<style type="text/css"> 
		body, html {
			margin: 0px;
			padding: 0px;
			height: 100%;
			width: 100%;
			overflow: hidden;
			font-size: 11px;
			font-family: "Helvetica Neue", "Segoe UI", Helvetica, Arial, sans-serif;
		}
.ps-container{-ms-touch-action:none;touch-action:none;overflow:hidden !important;-ms-overflow-style:none}@supports (-ms-overflow-style: none){.ps-container{overflow:auto !important}}@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none){.ps-container{overflow:auto !important}}.ps-container.ps-active-x>.ps-scrollbar-x-rail,.ps-container.ps-active-y>.ps-scrollbar-y-rail{display:block;background-color:transparent}.ps-container.ps-in-scrolling{pointer-events:none}.ps-container.ps-in-scrolling.ps-x>.ps-scrollbar-x-rail{background-color:#eee;opacity:.9}.ps-container.ps-in-scrolling.ps-x>.ps-scrollbar-x-rail>.ps-scrollbar-x{background-color:#999}.ps-container.ps-in-scrolling.ps-y>.ps-scrollbar-y-rail{background-color:#eee;opacity:.9}.ps-container.ps-in-scrolling.ps-y>.ps-scrollbar-y-rail>.ps-scrollbar-y{background-color:#999}.ps-container>.ps-scrollbar-x-rail{display:none;position:absolute;opacity:0;-webkit-transition:background-color .2s linear, opacity .2s linear;-moz-transition:background-color .2s linear, opacity .2s linear;-o-transition:background-color .2s linear, opacity .2s linear;transition:background-color .2s linear, opacity .2s linear;bottom:0px;height:15px}.ps-container>.ps-scrollbar-x-rail>.ps-scrollbar-x{position:absolute;background-color:#aaa;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;-moz-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;-o-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -webkit-border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;bottom:2px;height:6px}.ps-container>.ps-scrollbar-x-rail:hover>.ps-scrollbar-x,.ps-container>.ps-scrollbar-x-rail:active>.ps-scrollbar-x{height:11px}.ps-container>.ps-scrollbar-y-rail{display:none;position:absolute;opacity:0;-webkit-transition:background-color .2s linear, opacity .2s linear;-moz-transition:background-color .2s linear, opacity .2s linear;-o-transition:background-color .2s linear, opacity .2s linear;transition:background-color .2s linear, opacity .2s linear;right:0;width:15px}.ps-container>.ps-scrollbar-y-rail>.ps-scrollbar-y{position:absolute;background-color:#aaa;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;-moz-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;-o-transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;transition:background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -webkit-border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;right:2px;width:6px}.ps-container>.ps-scrollbar-y-rail:hover>.ps-scrollbar-y,.ps-container>.ps-scrollbar-y-rail:active>.ps-scrollbar-y{width:11px}.ps-container:hover.ps-in-scrolling{pointer-events:none}.ps-container:hover.ps-in-scrolling.ps-x>.ps-scrollbar-x-rail{background-color:#eee;opacity:.9}.ps-container:hover.ps-in-scrolling.ps-x>.ps-scrollbar-x-rail>.ps-scrollbar-x{background-color:#999}.ps-container:hover.ps-in-scrolling.ps-y>.ps-scrollbar-y-rail{background-color:#eee;opacity:.9}.ps-container:hover.ps-in-scrolling.ps-y>.ps-scrollbar-y-rail>.ps-scrollbar-y{background-color:#999}.ps-container:hover>.ps-scrollbar-x-rail,.ps-container:hover>.ps-scrollbar-y-rail{opacity:.6}.ps-container:hover>.ps-scrollbar-x-rail:hover{background-color:#eee;opacity:.9}.ps-container:hover>.ps-scrollbar-x-rail:hover>.ps-scrollbar-x{background-color:#999}.ps-container:hover>.ps-scrollbar-y-rail:hover{background-color:#eee;opacity:.9}.ps-container:hover>.ps-scrollbar-y-rail:hover>.ps-scrollbar-y{background-color:#999}
	</style>
</head>
<body>
	<div id="arrowchat_sound_player_holder"></div>
	<div id="arrowchat_chatroom_password_flyout" class="arrowchat_password_box arrowchat_popout_password_flyout">
		<div class="arrowchat_create_menu">
			<div class="arrowchat_create_menu_wrapper">
				<i class="fa-light fa-key-skeleton"></i>
				<span class="arrowchat_create_menu_text"><?php echo $language[50]; ?></span>
				<div class="arrowchat_create_menu_buttons_wrapper">
					<label class="arrowchat_create_input_wrapper">
						<div class="arrowchat_create_input_icon">
							<i class="far fa-lock-keyhole"></i>
						</div>
						<input type="password" autocomplete="off" id="arrowchat_chatroom_password_input" class="arrowchat_create_input" maxlength="50" value="" tabindex="0" />
						<input type="hidden" id="arrowchat_chatroom_password_id" value="" />
					</label>
					<div class="arrowchat_ui_button" id="arrowchat_password_button">
						<div><?php echo $language[100]; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="arrowchat_popout_wrapper">
		<div class="arrowchat_enter_name_wrapper">
			<div class="arrowchat_enter_name_content">
				<div class="arrowchat_enter_name_text_wrapper">
					<i class="fad fa-wreath"></i>
					<div class="arrowchat_enter_name_text">
						<span class="arrowchat_enter_name_text_top"><?php echo $language[315]; ?></span>
						<span class="arrowchat_enter_name_text_bot"><?php echo $language[314]; ?></span>
					</div>
				</div>
				<label class="arrowchat_enter_name_input_wrapper">
					<div class="arrowchat_enter_name_input_icon">
						<i class="far fa-italic"></i>
					</div>
					<input type="password" name="arrowchat_realPassword2" id="arrowchat_realPassword2" style="display:none" />
					<input placeholder="<?php echo $language[119]; ?>" type="text" class="arrowchat_guest_name_input" maxlength="25" />
				</label>
				<div class="arrowchat_ui_button" id="arrowchat_guest_name_button"><div><?php echo $language[316]; ?></div></div>
			</div>
		</div>
		<div id="arrowchat_popout_left">
			<div id="arrowchat_popout_friends">
				<div id="arrowchat_popout_left_header">
					<div id="arrowchat_popout_settings">
						<div class="arrowchat_popout_settings_button arrowchat_white_background">
							<img src="<?php echo $user_avatar; ?>" />
							<span class="arrowchat_tab_letter arrowchat_tab_letter_psmall"></span>
						</div>
						<div id="arrowchat_options_wrapper" class="arrowchat_more_wrapper">
							<div id="arrowchat_options_flyout" class="">
								<ul class="arrowchat_inner_menu">
									<li class="arrowchat_menu_item">
										<a id="arrowchat_setting_sound" class="arrowchat_menu_anchor">
											<i class="fal fa-music"></i>
											<span><?php echo $language[6]; ?></span>
											<label class="arrowchat_switch">
												<input type="checkbox" checked="" />
												<span class="arrowchat_slider"></span>
											</label>
										</a>
									</li>
									<li class="arrowchat_menu_item">
										<a id="arrowchat_setting_names_only" class="arrowchat_menu_anchor">
											<i class="fa-light fa-image"></i>
											<span><?php echo $language[18]; ?></span>
											<label class="arrowchat_switch">
												<input type="checkbox" checked="" />
												<span class="arrowchat_slider"></span>
											</label>
										</a>
									</li>
									<li class="arrowchat_menu_separator"></li>
									<li class="arrowchat_menu_item">
										<a id="arrowchat_setting_block_list" class="arrowchat_menu_anchor">
											<i class="fa-light fa-ban"></i>
											<span><?php echo $language[95]; ?></span>
											<input type="checkbox" checked="" />
										</a>
									</li>
									<li class="arrowchat_menu_item" id="arrowchat_setting_mod_cp">
										<a class="arrowchat_menu_anchor">
											<i class="fa-light fa-up-right-from-square"></i>
											<span><?php echo $language[305]; ?></span>
											<div id="arrowchat_more_notification_modcp">0</div>
										</a>
									</li>
									<li class="arrowchat_menu_separator"></li>
									<li class="arrowchat_menu_item" id="arrowchat_hide_lists_button">
										<a class="arrowchat_menu_anchor">
											<i class="fa-light fa-arrow-left-from-line"></i>
											<span><?php echo $language[229]; ?></span>
											<input type="checkbox" checked="" />
										</a>
									</li>
								</ul>
								<div class="arrowchat_block_menu">
									<div class="arrowchat_block_wrapper">
										<i class="fa-light fa-user-unlock"></i>
										<span class="arrowchat_block_menu_text"><?php echo $language[96]; ?></span>
										<div class="arrowchat_block_buttons_wrapper">
											<select></select>
											<div class="arrowchat_ui_button" id="arrowchat_unblock_button">
												<div><?php echo $language[97]; ?></div>
											</div>
										</div>
									</div>
									<div class="arrowchat_menu_separator"></div>
									<ul>
										<li class="arrowchat_menu_item">
											<a id="arrowchat_block_back" class="arrowchat_menu_anchor">
												<i class="fa-light fa-angles-left"></i>
												<span><?php echo $language[302]; ?></span>
											</a>
										</li>
									</ul>
								</div>
								<i class="arrowchat_more_tip"></i>
							</div>
						</div>
					</div>
					<div class="arrowchat_popout_left_header_text"><?php echo $language[110]; ?></div>
				</div>
				<label id="arrowchat_search_friends"> 
					<div class="arrowchat_search_friends_magnify"> 
						<i class="far fa-magnifying-glass"></i>
					</div> 
					<input type="password" name="arrowchat_realPassword" id="arrowchat_realPassword" style="display:none" />
					<input type="text" class="arrowchat_search_friends_input" autocomplete="off" placeholder="<?php echo $language[12]; ?>" value="" tabindex="0"> 
				</label>
				<div id="arrowchat_chat_selection_tabs">
					<div id="arrowchat_user_selection" class="arrowchat_selection_tab arrowchat_selection_tab_selected"><?php echo $language[300]; ?></div>
					<div id="arrowchat_room_selection" class="arrowchat_selection_tab">
						<span><?php echo $language[301]; ?></span>
						<i class="arrowchat_room_create fa-solid fa-ellipsis-vertical">
							<div class="arrowchat_more_wrapper">
								<div id="arrowchat_create_room_flyout">
									<div class="arrowchat_create_menu">
										<div class="arrowchat_create_menu_wrapper">
											<i class="fa-light fa-users"></i>
											<span class="arrowchat_create_menu_text"><?php echo $language[37]; ?></span>
											<div class="arrowchat_create_menu_buttons_wrapper">
												<label class="arrowchat_create_input_wrapper">
													<div class="arrowchat_create_input_icon">
														<i class="far fa-italic"></i>
													</div>
													<input type="text" autocomplete="off" class="arrowchat_create_input arrowchat_room_name_input" maxlength="100" placeholder="<?php echo $language[98]; ?>" value="" tabindex="0" />
												</label>
												<div class="arrowchat_ui_button" id="arrowchat_create_room_button">
													<div><?php echo $language[31]; ?></div>
												</div>
											</div>
											<div class="arrowchat_create_menu_buttons_wrapper arrowchat_password_input_wrapper">
												<label class="arrowchat_create_input_wrapper">
													<div class="arrowchat_create_input_icon">
														<i class="far fa-lock-keyhole"></i>
													</div>
													<input type="text" autocomplete="off" class="arrowchat_create_input arrowchat_room_password_input" maxlength="25" placeholder="<?php echo $language[99]; ?>" value="" tabindex="0" />
												</label>
											</div>
											<div class="arrowchat_create_password_wrapper">
												<i class="fa-solid fa-circle-plus"></i><span>Add a password</span>
											</div>
											
										</div>
									</div>
									<i class="arrowchat_more_tip"></i>
								</div>
							</div>
						</i>
					</div>
				</div>
				<div id="arrowchat_popout_left_lists">
					<div id="arrowchat_userslist_available"></div>
					<div id="arrowchat_userslist_busy"></div>
					<div id="arrowchat_userslist_away"></div>
					<div id="arrowchat_userslist_offline"></div>
				</div>
			</div>
		</div>
		<div id="arrowchat_popout_right">
			<div id="arrowchat_popout_chat">
				<div class="arrowchat_no_open_convos"><i class="fas fa-comments-question"></i><?php echo $language[317]; ?></div>
				<div class="arrowchat_popout_hide_lists fas fa-arrow-right-from-line"></div>
				<div id="arrowchat_chatroom_message_flyout" class="arrowchat_message_box">
					<div class="arrowchat_message_box_wrapper">
						<div>
							<span class="arrowchat_message_text"></span>
						</div>
					</div>
				</div>	
			</div>
			<div id="arrowchat_popout_open_chats">
				<div id="arrowchat_popout_container">
				</div>
			</div>
		</div>
	</div>
</body>
</html>