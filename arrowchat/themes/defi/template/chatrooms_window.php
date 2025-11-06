<div class="arrowchat_tabtitle">
	<div class="arrowchat_user_status"></div>
	<div class="arrowchat_user_name_wrapper">
		<div class="arrowchat_user_image">
			<img class="arrowchat_avatar" src="'+img+'" />
			<div class="arrowchat_chatroom_count_window"><i class="fad fa-circle-user"></i><span>'+online_count+'</span></div>
		</div>
		<div class="arrowchat_more_anchor">
			<div>
				<span class="arrowchat_name"><div>'+name+'</div></span>
				<span class="arrowchat_name_description">'+desc+'</span>
			</div>
			<i class="arrowchat_name_more fa-light fa-chevron-down"></i>
		</div>
		<div class="arrowchat_more_user_wrapper">
			<div class="arrowchat_more_popout arrowchat_more_popout_user">
				<ul class="arrowchat_inner_menu">
					<li class="arrowchat_menu_item">
						<a class="arrowchat_room_sounds arrowchat_menu_anchor">
							<i class="fa-light fa-music"></i>
							<span>'+lang[101]+'</span>
							<label class="arrowchat_switch">
								<input type="checkbox" checked="" />
								<span class="arrowchat_slider"></span>
							</label>
						</a>
					</li>
					<li class="arrowchat_menu_item">
						<a class="arrowchat_block_private_chats arrowchat_menu_anchor">
							<i class="fa-light fa-user-slash"></i>
							<span>'+lang[279]+'</span>
							<label class="arrowchat_switch">
								<input type="checkbox" checked="" />
								<span class="arrowchat_slider"></span>
							</label>
						</a>
					</li>
					<li class="arrowchat_menu_separator arrowchat_admin_controls"></li>
					<li class="arrowchat_menu_item arrowchat_admin_controls">
						<a class="arrowchat_chatroom_invisible arrowchat_menu_anchor">
							<i class="fa-light fa-eye-slash"></i>
							<span>'+lang[319]+'</span>
							<label class="arrowchat_switch">
								<input type="checkbox" checked="" />
								<span class="arrowchat_slider"></span>
							</label>
						</a>
					</li>
					<li class="arrowchat_menu_item arrowchat_admin_controls">
						<a class="arrowchat_edit_description arrowchat_menu_anchor">
							<i class="fa-light fa-book-copy"></i>
							<span>'+lang[157]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_item arrowchat_admin_controls">
						<a class="arrowchat_edit_welcome_msg arrowchat_menu_anchor">
							<i class="fa-light fa-door-open"></i>
							<span>'+lang[153]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_item arrowchat_admin_controls">
						<a class="arrowchat_edit_flood arrowchat_menu_anchor">
							<i class="fa-light fa-water"></i>
							<span>'+lang[171]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_separator"></li>
					<li class="arrowchat_menu_item">
						<a class="arrowchat_menu_anchor arrowchat_chat_popout">
							<i class="fa-light fa-up-right-from-square"></i>
							<span>'+lang[117]+'</span>
						</a>
					</li>
				</ul>
				<div class="arrowchat_flood_menu">
					<div class="arrowchat_block_wrapper">
						<i class="fa-light fa-water"></i>
						<span class="arrowchat_block_menu_text">'+lang[172]+'</span>
						<div class="arrowchat_block_buttons_wrapper">
							<div>
								<select class="arrowchat_flood_select_messages">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
								</select>
								<span>'+lang[174]+'</span>
							</div>
							<div>
								<select class="arrowchat_flood_select_seconds">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
									<option value="25">25</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
									<option value="60">60</option>
									<option value="90">90</option>
									<option value="120">120</option>
								</select>
								<span>'+lang[175]+'</span>
							</div>
							<div class="arrowchat_ui_button arrowchat_flood_save">
								<div>'+lang[173]+'</div>
							</div>
						</div>
					</div>
					<div class="arrowchat_menu_separator"></div>
					<ul>
						<li class="arrowchat_menu_item">
							<a class="arrowchat_menu_anchor arrowchat_flood_back">
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
	<div class="arrowchat_closebox far fa-xmark"></div>
	<div class="arrowchat_room_list_icon far fa-user-group"></div>
	<div class="arrowchat_dash_button far fa-dash"></div>
</div>
<div class="arrowchat_tabcontent">
	<div id="arrowchat_room_upload_queue_'+b+'" class="arrowchat_users_upload_queue"></div>
	<div class="arrowchat_message_box">
		<div class="arrowchat_message_box_wrapper">
			<div>
				<span class="arrowchat_message_text">'+lang[68]+'</span>
			</div>
		</div>
	</div>
	<div class="arrowchat_chatroom_user_popouts"></div>
	<div class="arrowchat_room_userlist">
		<div class="arrowchat_chatroom_line_admins" class="arrowchat_group_container">
			<span class="arrowchat_group_text">'+lang[148]+'</span>
		</div>
		<div class="arrowchat_chatroom_list_admins"></div>
		<div class="arrowchat_chatroom_line_mods" class="arrowchat_group_container">
			<span class="arrowchat_group_text">'+lang[149]+'</span>
		</div>
		<div class="arrowchat_chatroom_list_mods"></div>
		<div class="arrowchat_chatroom_line_users" class="arrowchat_group_container">
			<span class="arrowchat_group_text">'+lang[147]+'</span>
		</div>
		<div class="arrowchat_chatroom_list_users"></div>
	</div>
	<div class="arrowchat_tabcontenttext"></div>
	<div class="arrowchat_tabcontentinput">
		<div class="arrowchat_more_options_button">
			<i class="fa-solid fa-circle-plus"></i>
			<div class="arrowchat_more_user_popout_wrapper">
				<div class="arrowchat_more_popout">
					<ul class="arrowchat_inner_menu">
						<li class="arrowchat_menu_item arrowchat_gif_button">
							<div class="arrowchat_menu_anchor">
								<i class="fa-light fa-gif"></i>
								<span>'+lang[309]+'</span>
							</div>
						</li>
						<li class="arrowchat_menu_item">
							<div class="arrowchat_menu_anchor arrowchat_attach_button">
								<i class="fa-light fa-camera"></i>
								<span>'+lang[310]+'</span>
							</div>
							<div class="arrowchat_file_transfer_user"></div>
						</li>
					</ul>
					<i class="arrowchat_more_tip"></i>
				</div>
			</div>
			<div class="arrowchat_more_wrapper arrowchat_giphy_popout">
				<div class="arrowchat_more_popout">
					<div class="arrowchat_giphy_box">
						<label class="arrowchat_giphy_search_wrapper">
							<div class="arrowchat_giphy_magnify">
								<i class="far fa-magnifying-glass"></i>
							</div>
							<input type="text" class="arrowchat_giphy_search" placeholder="'+lang[214]+'" value="" tabindex="0" />
						</label>
						<div class="arrowchat_giphy_image_wrapper">
							<div class="arrowchat_loading_icon"></div>
						</div>
					</div>
					<i class="arrowchat_more_tip"></i>
				</div>
			</div>
		</div>
		<div class="arrowchat_textarea_wrapper">
			<textarea class="arrowchat_textarea" maxlength="'+maxlength+'" placeholder="'+lang[213]+'"></textarea>
			<div class="arrowchat_smiley_button">
				<i class="fa-solid fa-face-grin-wide"></i>
				<div class="arrowchat_more_wrapper arrowchat_smiley_popout">
					<div class="arrowchat_more_popout">
						<div class="arrowchat_smiley_box">
							<div class="arrowchat_emoji_wrapper"></div>
							<div class="arrowchat_emoji_select_wrapper">
								<div class="arrowchat_emoji_selector arrowchat_emoji_smileys" data-id="emoji_smileys"><i class="fa-solid fa-face-grin-beam"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_animals" data-id="emoji_animals"><i class="fa-solid fa-dog"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_food" data-id="emoji_food"><i class="fa-solid fa-fork-knife"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_activities" data-id="emoji_activities"><i class="fa-solid fa-basketball"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_travel" data-id="emoji_travel"><i class="fa-solid fa-plane"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_objects" data-id="emoji_objects"><i class="fa-solid fa-bath"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_symbols" data-id="emoji_symbols"><i class="fa-solid fa-symbols"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_flags" data-id="emoji_flags"><i class="fa-solid fa-flag-swallowtail"></i></div>
								<div class="arrowchat_emoji_selector arrowchat_emoji_custom" data-id="emoji_custom"><i class="fa-solid fa-sparkles"></i></div>
							</div>
						</div>
						<i class="arrowchat_more_tip"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="arrowchat_user_send_button">
			<i class="fa-solid fa-paper-plane-top"></i>
		</div>
	</div>
</div>