.arrowchat_chatboxmessage_wrapper,.arrowchat_chatroom_message_content{background-color:#<?php echo $chat_bubble; ?>}
.arrowchat_chatboxmessagecontent,.arrowchat_chatroom_message_content{color:#<?php echo $chat_bubble_text; ?>}
.arrowchat_self .arrowchat_chatboxmessage_wrapper,.arrowchat_self .arrowchat_chatroom_message_content{background-color:#<?php echo $chat_bubble_self; ?> !important}
.arrowchat_self .arrowchat_chatboxmessagecontent,.arrowchat_self .arrowchat_chatroom_msg{color:#<?php echo $chat_bubble_self_text; ?> !important}
#arrowchat_mobiletab{background-color:#<?php echo $button_background; ?> !important}
#arrowchat_mobiletab_icon{color:#<?php echo $button_icon; ?> !important}
.arrowchat_typing_title .arrowchat_video_icon,.arrowchat_typing_title .arrowchat_dash_button,.arrowchat_typing_title .arrowchat_closebox,.arrowchat_typing_title .arrowchat_name_more,.arrowchat_typing_title .arrowchat_smiley_button,.arrowchat_typing_title .arrowchat_more_options_button,.arrowchat_typing_title .arrowchat_user_send_button,.arrowchat_typing_title .arrowchat_room_list_icon{color:#<?php echo $primary_color; ?>}
.arrowchat_userstabtitle .arrowchat_more_button{color:#<?php echo $primary_color; ?>}
.arrowchat_bell_button{color:#<?php echo $primary_color; ?>}
.arrowchat_close_button{color:#<?php echo $primary_color; ?>}
.arrowchat_action_message a{color:#<?php echo $primary_color; ?>}
.arrowchat_emoji_focused{color:#<?php echo $primary_color; ?>}
.arrowchat_unseen_close{color:#<?php echo $primary_color; ?>}
.arrowchat_room_list_icon_selected{background-color:#<?php echo $primary_color; ?> !important}
.arrowchat_ui_button{background:#<?php echo $primary_color; ?>}
.arrowchat_see_all_button{color:#<?php echo $primary_color; ?>}
.arrowchat_notification_box i{color:#<?php echo $primary_color; ?>}
#arrowchat_block_back,#arrowchat_block_back span{color:#<?php echo $primary_color; ?>}
.arrowchat_flood_back,.arrowchat_flood_back span{color:#<?php echo $primary_color; ?>}
.arrowchat_create_password_wrapper{color:#<?php echo $primary_color; ?>}
.uploadifive-queue-item .close{color:#<?php echo $primary_color; ?>}
.uploadifive-queue-item .progress-bar{background-color:#<?php echo $primary_color; ?>}
.arrowchat_selection_tab_selected{border-bottom:3px solid #<?php echo $primary_color; ?>;color:#<?php echo $primary_color; ?> !important}
input:checked+.arrowchat_slider{background-color:#<?php echo $secondary_color; ?>}
input:focus+.arrowchat_slider{box-shadow:0 0 1px #<?php echo $secondary_color; ?>}
.arrowchat_popout_focused{background-color:#<?php echo $primary_color; ?>}
.arrowchat_popout_hide_lists{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_display_list{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_room_options{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_input_container .arrowchat_upload_button_container{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_input_container .arrowchat_giphy_button{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_input_container .arrowchat_smiley_button{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_input_container .arrowchat_user_send_button{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_focused.arrowchat_tabmouseover_popout{background-color:#<?php echo $secondary_color; ?>}
.arrowchat_popout_info{color:#<?php echo $primary_color; ?>}
.arrowchat_popout_video_chat{color:#<?php echo $primary_color; ?>}
.arrowchat_ui_button:hover{background:#<?php echo $secondary_color; ?>}
#arrowchat_maintenance .arrowchat_corner_button{background-color:#<?php echo $secondary_color; ?>}
@media(max-width: 640px){#arrowchat_popout_left #arrowchat_room_selection,#arrowchat_popout_left #arrowchat_user_selection{color:#<?php echo $secondary_color; ?>}}

<?php
	if (!empty($bar_hover)) {
?>
.arrowchat_tabclick,.arrowchat_trayclick,.arrowchat_unseen_list_open{background-color:#fff !important}
<?php
	}
?>