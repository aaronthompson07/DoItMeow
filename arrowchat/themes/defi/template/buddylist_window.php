<div class="arrowchat_userstabtitle">
	<div class="arrowchat_tab_name">'+lang[4]+'</div>
	<div class="arrowchat_close_button far fa-xmark">
	
	</div>
	<div class="arrowchat_bell_button far fa-bell">
		<div id="arrowchat_bell_notification">0</div>
		<div id="arrowchat_bell_wrapper" class="arrowchat_bell_wrapper">
			<div id="arrowchat_bell_flyout" class="">
				<div class="arrowchat_see_all_button far fa-clock-rotate-left"></div>
				<div id="arrowchat_notifications_content">
					<div id="arrowchat_no_new_notifications"><i class="fa-solid fa-bell-slash"></i>'+lang[9]+'</div>
				</div>
				<i class="arrowchat_more_tip"></i>
			</div>
		</div>
	</div>
	<div class="arrowchat_more_button far fa-ellipsis">
		<div id="arrowchat_more_notification">0</div>
		<div id="arrowchat_options_wrapper" class="arrowchat_more_wrapper">
			<div id="arrowchat_options_flyout" class="">
				<ul class="arrowchat_inner_menu">
					<li class="arrowchat_menu_item">
						<a id="arrowchat_setting_sound" class="arrowchat_menu_anchor">
							<i class="fal fa-music"></i>
							<span>'+lang[6]+'</span>
							<label class="arrowchat_switch">
								<input type="checkbox" checked="" />
								<span class="arrowchat_slider"></span>
							</label>
						</a>
					</li>
					<li class="arrowchat_menu_item">
						<a id="arrowchat_setting_window_open" class="arrowchat_menu_anchor">
							<i class="fa-light fa-browsers"></i>
							<span>'+lang[17]+'</span>
							<label class="arrowchat_switch">
								<input type="checkbox" checked="" />
								<span class="arrowchat_slider"></span>
							</label>
						</a>
					</li>
					<li class="arrowchat_menu_item">
						<a id="arrowchat_setting_names_only" class="arrowchat_menu_anchor">
							<i class="fa-light fa-image"></i>
							<span>'+lang[18]+'</span>
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
							<span>'+lang[95]+'</span>
							<input type="checkbox" checked="" />
						</a>
					</li>
					<li class="arrowchat_menu_item" id="arrowchat_setting_mod_cp">
						<a class="arrowchat_menu_anchor">
							<i class="fa-light fa-up-right-from-square"></i>
							<span>'+lang[305]+'</span>
							<div id="arrowchat_more_notification_modcp">0</div>
						</a>
					</li>
					<li class="arrowchat_menu_separator"></li>
					<li class="arrowchat_menu_item">
						<a id="arrowchat_gooffline" class="arrowchat_menu_anchor">
							<i class="fa-light fa-power-off"></i>
							<span>'+lang[5]+'</span>
						</a>
					</li>
				</ul>
				<div class="arrowchat_block_menu">
					<div class="arrowchat_block_wrapper">
						<i class="fa-light fa-user-unlock"></i>
						<span class="arrowchat_block_menu_text">'+lang[96]+'</span>
						<div class="arrowchat_block_buttons_wrapper">
							<select></select>
							<div class="arrowchat_ui_button" id="arrowchat_unblock_button">
								<div>'+lang[97]+'</div>
							</div>
						</div>
					</div>
					<div class="arrowchat_menu_separator"></div>
					<ul>
						<li class="arrowchat_menu_item">
							<a id="arrowchat_block_back" class="arrowchat_menu_anchor">
								<i class="fa-light fa-angles-left"></i>
								<span>'+lang[302]+'</span>
							</a>
						</li>
					</ul>
				</div>
				<i class="arrowchat_more_tip"></i>
			</div>
		</div>
	</div>
</div>
<div class="arrowchat_tabcontent arrowchat_tabstyle">
	<div id="arrowchat_buddylist_message_flyout" class="arrowchat_message_box">
		<div class="arrowchat_message_box_wrapper">
			<div>
				<span class="arrowchat_message_text"></span>
			</div>
		</div>
	</div>
	<div class="arrowchat_enter_name_wrapper">
		<div class="arrowchat_enter_name_content">
			<div class="arrowchat_enter_name_text_wrapper">
				<i class="fad fa-wreath"></i>
				<div class="arrowchat_enter_name_text">
					<span class="arrowchat_enter_name_text_top">'+lang[315]+'</span>
					<span class="arrowchat_enter_name_text_bot">'+lang[314]+'</span>
				</div>
			</div>
			<label class="arrowchat_enter_name_input_wrapper">
				<div class="arrowchat_enter_name_input_icon">
					<i class="far fa-italic"></i>
				</div>
				<input type="password" name="arrowchat_realPassword2" id="arrowchat_realPassword2" style="display:none" />
				<input placeholder="'+lang[119]+'" type="text" class="arrowchat_guest_name_input" maxlength="25" />
			</label>
			<div class="arrowchat_ui_button" id="arrowchat_guest_name_button"><div>'+lang[316]+'</div></div>
		</div>
	</div>
	<div id="arrowchat_search_friends" class="far fa-magnifying-glass">
		<input type="password" name="arrowchat_realPassword" id="arrowchat_realPassword" style="display:none" />
		<input type="text" class="arrowchat_search_friends_text" autocomplete="off" placeholder="'+lang[12]+'"  />
	</div>
	<div id="arrowchat_chat_selection_tabs">
		<div id="arrowchat_user_selection" class="arrowchat_selection_tab arrowchat_selection_tab_selected">'+lang[300]+'</div>
		<div id="arrowchat_room_selection" class="arrowchat_selection_tab">
			<span>'+lang[301]+'</span>
			<i class="arrowchat_room_create fa-solid fa-ellipsis-vertical">
				<div class="arrowchat_more_wrapper">
					<div id="arrowchat_create_room_flyout">
						<div class="arrowchat_create_menu">
							<div class="arrowchat_create_menu_wrapper">
								<i class="fa-light fa-users"></i>
								<span class="arrowchat_create_menu_text">'+lang[37]+'</span>
								<div class="arrowchat_create_menu_buttons_wrapper">
									<label class="arrowchat_create_input_wrapper">
										<div class="arrowchat_create_input_icon">
											<i class="far fa-italic"></i>
										</div>
										<input type="text" autocomplete="off" class="arrowchat_create_input arrowchat_room_name_input" maxlength="100" placeholder="'+lang[98]+'" value="" tabindex="0" />
									</label>
									<div class="arrowchat_ui_button" id="arrowchat_create_room_button">
										<div>'+lang[31]+'</div>
									</div>
								</div>
								<div class="arrowchat_create_menu_buttons_wrapper arrowchat_password_input_wrapper">
									<label class="arrowchat_create_input_wrapper">
										<div class="arrowchat_create_input_icon">
											<i class="far fa-lock-keyhole"></i>
										</div>
										<input type="text" autocomplete="off" class="arrowchat_create_input arrowchat_room_password_input" maxlength="25" placeholder="'+lang[99]+'" value="" tabindex="0" />
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
	<div id="arrowchat_userscontent">
		<div id="arrowchat_userslist_available"></div>
		<div id="arrowchat_userslist_busy"></div>
		<div id="arrowchat_userslist_away"></div>
		<div id="arrowchat_userslist_invisible"></div>
		<div id="arrowchat_userslist_offline"></div>
	</div>
	<div class="arrowchat_chatroom_full_content">
	</div>
	<div class="arrowchat_more_wrapper arrowchat_password_zindex">
			<div id="arrowchat_chatroom_password_flyout">
				<div class="arrowchat_create_menu">
					<div class="arrowchat_create_menu_wrapper">
						<i class="fa-light fa-key-skeleton"></i>
						<span class="arrowchat_create_menu_text">'+lang[50]+'</span>
						<div class="arrowchat_create_menu_buttons_wrapper">
							<label class="arrowchat_create_input_wrapper">
								<div class="arrowchat_create_input_icon">
									<i class="far fa-lock-keyhole"></i>
								</div>
								<input type="password" autocomplete="off" id="arrowchat_chatroom_password_input" class="arrowchat_create_input" maxlength="50" value="" tabindex="0" />
								<input type="hidden" id="arrowchat_chatroom_password_id" value="" />
							</label>
							<div class="arrowchat_ui_button" id="arrowchat_password_button">
								<div>'+lang[100]+'</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div id="arrowchat_chatroom_message_flyout" class="arrowchat_message_box">
		<div class="arrowchat_message_box_wrapper">
			<div>
				<span class="arrowchat_message_text">'+lang[49]+'</span>
			</div>
		</div>
	</div>
</div>
<div class="arrowchat_powered_by">'+acp+'</div>