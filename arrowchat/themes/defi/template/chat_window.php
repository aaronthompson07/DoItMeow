<div class="arrowchat_tabtitle">
	<div class="arrowchat_user_status"></div>
	<div class="arrowchat_user_name_wrapper">
		<div class="arrowchat_user_image">
			<div class="arrowchat_chat_window_status"></div>
			<div class="arrowchat_avatarbox arrowchat_white_background"><a href="' + link + '"><img src="' + img + '" class="arrowchat_avatar" /><span class="arrowchat_tab_letter arrowchat_tab_letter_small"></span></a></div>
		</div>
		<div class="arrowchat_more_anchor">
			<span class="arrowchat_name"><div>' + name + '</div></span>
			<i class="arrowchat_name_more fa-light fa-chevron-down"></i>
		</div>
		<div class="arrowchat_more_user_wrapper">
			<div class="arrowchat_more_popout arrowchat_more_popout_user">
				<ul class="arrowchat_inner_menu">
					<li class="arrowchat_menu_item">
						<a class="arrowchat_menu_anchor arrowchat_chat_popout">
							<i class="fa-light fa-up-right-from-square"></i>
							<span>'+lang[60]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_item">
						<a class="arrowchat_clear_user arrowchat_menu_anchor">
							<i class="fa-light fa-trash-can"></i>
							<span>'+lang[24]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_separator"></li>
					<li class="arrowchat_menu_item">
						<a class="arrowchat_block_user arrowchat_menu_anchor" style="background:none">
							<i class="fa-light fa-user-xmark"></i>
							<span>'+lang[84]+'</span>
						</a>
					</li>
					<li class="arrowchat_menu_item">
						<a class="arrowchat_report_user arrowchat_menu_anchor">
							<i class="fa-light fa-triangle-exclamation"></i>
							<span>'+lang[167]+'</span>
						</a>
					</li>
				</ul>
				<i class="arrowchat_more_tip"></i>
			</div>
		</div>
	</div>
	<div class="arrowchat_closebox far fa-xmark"></div>
	<div class="arrowchat_video_icon arrowchat_video_unavailable far fa-video"></div>
	<div class="arrowchat_dash_button far fa-dash"></div>
</div>
<div class="arrowchat_tabcontent">
	<div id="arrowchat_user_upload_queue_'+id+'" class="arrowchat_users_upload_queue"></div>
	<div class="arrowchat_message_box">
		<div class="arrowchat_message_box_wrapper">
			<div>
				<span class="arrowchat_message_text">'+lang[68]+'</span>
			</div>
		</div>
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
			<textarea class="arrowchat_textarea" placeholder="'+lang[213]+'"></textarea>
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