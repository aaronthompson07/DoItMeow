var ArrowChat = {};
ArrowChat.Templates = {
	notifications_tab			: function () { return '<?php echo $file_notifications_tab; ?>'; },
	notifications_window			: function () { return '<?php echo $file_notifications_window; ?>'; },
	warnings_display			: function (h) { return '<?php echo $file_warnings_display; ?>'; },
	welcome_display			: function (header, content) { return '<?php echo $file_welcome_display; ?>'; },
	chat_tab				: function (shortname,e) { return '<?php echo $file_chat_tab; ?>'; },
	unseen_chat_tab				: function () { return '<?php echo $file_unseen_chat_tab; ?>'; },
	chat_window				: function (name, img, link, id) { return '<div class="arrowchat_tabpopup"><?php echo $file_chat_window; ?></div>'; },
	buddylist_tab				: function () { return '<?php echo $file_buddylist_tab; ?>'; },
	buddylist_window			: function (d, acp) { return '<?php echo str_replace("<!--", "", $file_buddylist_window); ?>'; },
	maintenance_tab				: function (c_login_url) { if(c_login_url=="")c_login_url="#"; return '<div id="arrowchat_maintenance"><a href="'+c_login_url+'"><?php echo $file_maintenance_tab; ?></a></div>'; },
	announcements_display			: function (h) { return '<?php echo $file_announcements_display; ?>'; },
	chatrooms_tab				: function (name, img, online_count) { return '<?php echo $file_chatrooms_tab; ?>'; },
	chatrooms_window			: function (b, name, img, desc, online_count, maxlength) { return '<div class="arrowchat_tabpopup"><?php echo $file_chatrooms_window; ?></div>'; }
};
ArrowChat.IdleTime = <?php echo $idle_time; ?>;

