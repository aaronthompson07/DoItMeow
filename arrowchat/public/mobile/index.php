<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #    Copyright �2010-2012 ArrowSuites LLC. All Rights Reserved.    # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/

	// ########################## INCLUDE BACK-END ###########################
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'bootstrap.php');
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');

	if (preg_match('#public/mobile#', $_SERVER['REQUEST_URI']))
		$home_url = "../../../";
	else
		$home_url = "../../";
		
	// Exit for group permissions
	if (check_array_for_match($group_id, $group_disable_rooms_sep))
			$chatrooms_on = 0;
			
	if ($group_enable_mode == 1)
	{
		$chatrooms_on = 0;
		
		if (check_array_for_match($group_id, $group_disable_rooms_sep))
				$chatrooms_on = 1;
		
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
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $language[145]; ?></title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="apple-touch-fullscreen" content="yes" />

		<link rel="apple-touch-icon" href="images/apple-touch-icon.png"/>
		<link rel="stylesheet" href="<?php echo $base_url; ?><?php echo AC_FOLDER_PUBLIC; ?>/mobile/onsenui/css/onsenui.min.css" />
		<link rel="stylesheet" href="<?php echo $base_url; ?><?php echo AC_FOLDER_PUBLIC; ?>/mobile/onsenui/css/onsen-css-components.min.css" />
		<link type="text/css" rel="stylesheet" id="arrowchat_css" media="all" href="<?php echo $base_url; ?><?php echo AC_FOLDER_PUBLIC; ?>/mobile/includes/css/style.css" charset="utf-8" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

		<script type="text/javascript" src="<?php echo $base_url; ?><?php echo AC_FOLDER_INCLUDES; ?>/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="<?php echo $base_url; ?><?php echo AC_FOLDER_PUBLIC; ?>/mobile/onsenui/js/onsenui.min.js"></script>
		<script>ons.platform.select('ios');</script>
		<script type="text/javascript" src="<?php echo $base_url; ?><?php echo AC_FOLDER_INCLUDES; ?>/js/jquery-ui.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>external.php?type=djs" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>external.php?type=mjs" charset="utf-8"></script>
	</head>
    <body>
		<ons-page>
			<ons-splitter>
				<ons-splitter-side id="menu" side="left" width="220px" collapse>
					<ons-page>
						<div class="settings-image-wrapper arrowchat_white_background">
							<span class="arrowchat_tab_letter"></span>
							<img id="settings-image2" src="<?php echo $base_url; ?>admin/images/img-no-avatar.png" />
						</div>
						<div class="settings-name-wrapper">
							<h3 id="settings-name2"></h3>
						</div>
						<ons-list modifier="noborder">
							<ons-list-item onclick="top.location.href='<?php echo $home_url; ?>'" tappable>
								<ons-icon icon="fa-external-link-alt" class="menu-icon"></ons-icon><?php echo $language[126]; ?>
							</ons-list-item>
							<?php if ($is_admin == 1 OR $is_mod == 1) { ?>
							<ons-list-item onclick="top.location.href='<?php echo $base_url; ?>mod/'" tappable>
								<ons-icon icon="fa-external-link-alt" class="menu-icon"></ons-icon><?php echo $language[263]; ?>
							</ons-list-item>
							<?php } ?>
							<ons-list-item onclick="fn.showTemplateDialog()" tappable class="no-menu-icon">
								<?php echo $language[264]; ?>
							</ons-list-item>
						</ons-list>
					</ons-page>
				</ons-splitter-side>
				<ons-splitter-content id="content" page="main-navigator"></ons-splitter-content>
			</ons-splitter>
		</ons-page>
		<ons-template id="main-navigator">
			<ons-page>
				<ons-navigator id="myNavigator" page="main-page"></ons-navigator>
			</ons-page>
		</ons-template>
		<template id="main-page">
			<ons-page id="main-pages">
				<ons-toolbar>
					<div class="right">
						<ons-toolbar-button icon="fa-pen-to-square" id="add-icon"></ons-toolbar-button>
					</div>
					<div class="center" id="main-title">
						<?php echo $language[260]; ?>
					</div>
					<div class="left">
						<div class="settings-open arrowchat_white_background" onclick="fn.open('menu')">
							<span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall"></span>
							<img id="settings-open-image" src="<?php echo $base_url; ?>admin/images/img-no-avatar.png" />
						</div>
					</div>
				</ons-toolbar>
				<ons-tabbar position="bottom" animation="none">
					<ons-tab id="recent-chat-tab" page="recent-chat" label="<?php echo $language[260]; ?>" add-icon="fa-pen-to-square" icon="fa-comment" active-icon="fa-comment">
					</ons-tab>
				<?php if ($online_list_on != 0) { ?>
					<ons-tab id="online-users-tab" page="online-users" label="<?php echo $language[261]; ?>" add-icon="fa-pen-to-square" icon="fa-globe-americas" active-icon="fa-globe-americas">
					</ons-tab>
				<?php } ?>
				<?php if ($chatrooms_on == 1) { ?>
					<ons-tab id="chat-rooms-tab" page="chat-rooms" label="<?php echo $language[262]; ?>" add-icon="fa-circle-plus" icon="fa-users" active-icon="fa-users">
					</ons-tab>
				<?php } ?>
				</ons-tabbar>
			</ons-page>
		</template>
		<template id="recent-chat">
			<ons-page id="Tab1">
				<div id="recent-loader" class="loader-div"><ons-progress-circular indeterminate></ons-progress-circular></div>
				<ons-list id="buddylist-container-recent" modifier="noborder">
				</ons-list>
			</ons-page>
		</template>

		<template id="online-users">
			<ons-page id="Tab2">
				<ons-pull-hook id="pull-hook" threshold-height="120px">
					<ons-icon id="pull-hook-icon" size="22px" class="pull-hook-content" icon="fa-arrow-down"></ons-icon>
				</ons-pull-hook>
				<ons-list id="buddylist-container-available" modifier="noborder">
				</ons-list>
			</ons-page>
		</template>

		<template id="chat-rooms">
			<ons-page id="Tab3">
				<ons-list id="buddylist-container-chatroom-joined" modifier="noborder">
				</ons-list>
				<ons-list id="buddylist-container-chatroom" modifier="noborder">
				</ons-list>
			</ons-page>
		</template>

		<template id="search-page">
			<ons-dialog id="search-dialog" cancelable>
				<div class="searchcontainer">
					<div class="searchbutton">
						<ons-icon icon="fa-times" onclick="fn.hideDialog('search-dialog')"></ons-icon>
					</div>
					<div class="search-page-input-wrapper">
						<input type="text" name="search" id="search-page-input" class="textarea textarea--transparent" placeholder="<?php echo $language[271]; ?>" tabindex="-1" />
					</div>
				</div>
				<div id="search-buddylist">
					<div class="search-empty"><ons-icon icon="fa-binoculars"></ons-icon></div>
					<ons-list id="search-buddylist-list" modifier="noborder">
					</ons-list>
				</div>
			</ons-dialog>
		</template>
		
		<template id="change-name-page">
			<ons-dialog id="change-name-dialog">
				<div class="arrowchat_enter_name_content">
					<div class="arrowchat_enter_name_text_wrapper">
						<i class="fad fa-wreath"></i>
						<div class="arrowchat_enter_name_text">
							<span class="arrowchat_enter_name_text_top"><?php echo $language[315]; ?></span>
							<span class="arrowchat_enter_name_text_bot"><?php echo $language[314]; ?></span>
						</div>
					</div>
					<div class="arrowchat_enter_name_input_wrap">
						<input type="text" id="change-name-page-input" class="textarea textarea--transparent" placeholder="<?php echo $language[119]; ?>" tabindex="-1" />
						<ons-button id="arrowchat_guest_name_button"><?php echo $language[316]; ?></ons-button>
					</div>
				</div>
			</ons-dialog>
		</template>
		
		<template id="chatroom-password-page">
			<ons-dialog id="password-dialog" cancelable>
				<div class="searchcontainer">
					<div class="searchicon">
						<ons-icon icon="fa-lock"></ons-icon>
					</div>
					<div class="search-page-input-wrapper">
						<input type="text" name="password" id="password-page-input" class="textarea textarea--transparent" placeholder="<?php echo $language[138]; ?>" tabindex="-1" />
					</div>
				</div>
				<div class="searchcontainer">
					<div class="searchbutton submit-password">
						<ons-button id="chatroom-submit-password"><?php echo $language[265]; ?></ons-button>
						<ons-button id="chatroom-cancel-password" modifier="outline light"><?php echo $language[266]; ?></ons-button>
					</div>
				</div>
			</ons-dialog>
		</template>
		
		<template id="chatroom-create-page">
			<ons-dialog id="create-dialog" cancelable>
				<div class="searchcontainer">
					<div class="searchicon">
						<ons-icon icon="fa-i-cursor"></ons-icon>
					</div>
					<div class="search-page-input-wrapper">
						<input type="text" name="create-name" id="create-page-input" class="textarea textarea--transparent" placeholder="<?php echo $language[272]; ?>" tabindex="-1" />
					</div>
				</div>
				<div class="searchcontainer">
					<div class="searchicon">
						<ons-icon icon="fa-lock"></ons-icon>
					</div>
					<div class="search-page-input-wrapper">
						<input type="text" name="create-password" id="create-password-page-input" class="textarea textarea--transparent" placeholder="<?php echo $language[273]; ?>" tabindex="-1" />
					</div>
				</div>
				<div class="searchcontainer">
					<div class="searchbutton submit-create">
						<ons-button id="chatroom-submit-create"><?php echo $language[274]; ?></ons-button>
						<ons-button id="chatroom-cancel-create" modifier="outline light"><?php echo $language[275]; ?></ons-button>
					</div>
				</div>
			</ons-dialog>
		</template>
		
		<template id="warnings-page">
			<ons-dialog id="warnings-dialog">
				<div style="text-align: center; padding: 10px;">
					<p class="warning-icon"><ons-icon icon="fa-exclamation-triangle"></ons-icon></p>
					<p class="warning-message"><?php echo $language[199]; ?></p>
					<p class="warning-reason"></p>
					<p>
						<ons-button class="warnings_close"><?php echo $language[198]; ?></ons-button>
					</p>
				</div>
			</ons-dialog>
		</template>

		<template id="settings-page">
			<ons-dialog id="settings-dialog" cancelable>
				<div style="float:right">
					<ons-button modifier="quiet" onclick="fn.hideDialog('settings-dialog')"><?php echo $language[270]; ?></ons-button>
				</div>
				<div class="settings-image-wrapper arrowchat_white_background">
					<span class="arrowchat_tab_letter"></span>
					<img id="settings-image" src="<?php echo $base_url; ?>/admin/images/img-no-avatar.png" />
				</div>
				<div class="settings-name-wrapper">
					<h3 id="settings-name"></h3>
					<ons-button modifier="quiet" onclick="fn.showNamePrompt()" id="change-name"><?php echo $language[276]; ?></ons-button>
				</div>
				<!-- WILL ALLOW USERS TO HIDE MOBILE CHAT BUTTON -->
				<!-- <ons-list-item>
					<div class="center">
						<?php echo $language[211]; ?>
					</div>
					<div class="right">
						<ons-switch id="flip-hide-mobile"></ons-switch>
					</div>
				</ons-list-item> -->
				<ons-list-item>
					<div class="center">
						<?php echo $language[267]; ?>
					</div>
					<div class="right">
						<ons-switch id="flip-chat-sounds"></ons-switch>
					</div>
				</ons-list-item>
				<?php if ($disable_avatars != 1) { ?>
				<ons-list-item>
					<div class="center">
						<?php echo $language[268]; ?>
					</div>
					<div class="right">
						<ons-switch id="flip-disable-avatars"></ons-switch>
					</div>
				</ons-list-item>
				<?php } ?>
				<ons-list-item>
					<div class="center">
						<?php echo $language[269]; ?>
					</div>
					<div class="right">
						<ons-select id="unblock-mobile" modifier="material underbar">
						</ons-select>
					</div>
				</ons-list-item>
			</ons-dialog>
		</template>
		
		<template id="chatroom-settings-page">
			<ons-dialog id="chatroom-settings-dialog" cancelable>
				<div style="float:right">
					<ons-button modifier="quiet" onclick="fn.hideDialog('chatroom-settings-dialog')"><?php echo $language[270]; ?></ons-button>
				</div>
				<div class="settings-image-wrapper">
					<img id="chatroom-settings-image" src="<?php echo $base_url; ?>/admin/images/img-no-avatar.png" />
				</div>
				<div class="settings-name-wrapper">
					<h3 id="chatroom-settings-name"></h3>
					<ons-button modifier="quiet" onclick="fn.showDescriptionPrompt()" id="change-description"><?php echo $language[277]; ?></ons-button>
					<ons-button modifier="quiet" onclick="fn.showWelcomePrompt()" id="change-welcome"><?php echo $language[278]; ?></ons-button>
				</div>
				<ons-list-item id="chatroom-is-invisible">
					<div class="center">
						<?php echo $language[319]; ?>
					</div>
					<div class="right">
						<ons-switch id="chatroom-flip-is-invisible"></ons-switch>
					</div>
				</ons-list-item>
				<ons-list-item>
					<div class="center">
						<?php echo $language[279]; ?>
					</div>
					<div class="right">
						<ons-switch id="chatroom-flip-block-private"></ons-switch>
					</div>
				</ons-list-item>
				<ons-list-item>
					<div class="center">
						<?php echo $language[280]; ?>
					</div>
					<div class="right">
						<ons-switch id="chatroom-flip-sounds"></ons-switch>
					</div>
				</ons-list-item>
				<ons-list-item>
					<div class="center">
						<ons-button id="chatroom-leave-button" modifier="large"><?php echo $language[281]; ?></ons-button>
					</div>
				</ons-list-item>
			</ons-dialog>
		</template>

		<template id="private-chat">
			<ons-page id="private-chat-page">
				<ons-toolbar>
					<div class="left"><ons-back-button></ons-back-button><div class="back-count-wrapper"><span id="private-chat-back-notification" class="notification"></span></div></div>
					<div class="center" id="username-header"></div>
					<div class="right">
						<ons-toolbar-button icon="fa-video" id="start-video-chat"></ons-toolbar-button>
						<ons-toolbar-button icon="fa-ellipsis-v" id="show-private-actions"></ons-toolbar-button>
					</div>
				</ons-toolbar>
				<div class="chat_user_content"></div>
				<ons-bottom-toolbar>
					<div class="giphy-wrapper">
						<div class="arrowchat_giphy_box">
							<div class="arrowchat_giphy_image_wrapper"></div>
							<div class="arrowchat_giphy_search_wrapper">
								<div class="giphy_cancel_container">
									<ons-toolbar-button icon="fa-times" class="giphy_cancel"></ons-toolbar-button>
								</div>
								<div class="giphy_search_container">
									<input type="text" class="arrowchat_giphy_search textarea textarea--transparent" placeholder="<?php echo $language[214]; ?>" tabindex="-1" />
								</div>
							</div>
						</div>
					</div>
					<div class="textcontainer">
						<div class="sendbutton">
							<ons-toolbar-button icon="fa-paper-plane-top" id="send_button"></ons-toolbar-button>
						</div>
						<div class="textinput">
							<textarea id="textinput1" class="textarea textarea--transparent" placeholder="<?php echo $language[208]; ?>" tabindex="-1"></textarea>
						</div>
					</div>
					<div class="msg_controls">
						<ons-icon icon="fa-camera" class="fas" id="arrowchat_upload_button"><div id="arrowchat_chatroom_uploader"></div></ons-icon>
						<ons-icon icon="fa-image" class="far arrowchat_giphy_button"></ons-icon>
						<ons-icon icon="fa-smile" class="far arrowchat_smiley_button"></ons-icon>
					</div>
					<div class="smiley_box_wrapper" id="smiley_box_wrapper2"></div>
				</ons-bottom-toolbar>
			</ons-page>
		</template>

		<template id="chatroom-chat-splitter">
			<ons-page>
				<ons-splitter>
					<ons-splitter-side id="chatroom-menu" side="right" width="220px" collapse>
						<ons-page>
							<ons-list id="chatroom-users-list" modifier="noborder">

							</ons-list>
						</ons-page>
					</ons-splitter-side>
					<ons-splitter-content id="content" page="chatroom-chat"></ons-splitter-content>
				</ons-splitter>
			</ons-page>
		</template>

		<template id="chatroom-chat">
			<ons-page id="chatroom-chat-page">
				<ons-toolbar>
					<div class="left"><ons-back-button></ons-back-button><div class="back-count-wrapper"><span id="chat-room-back-notification" class="notification"></span></div></div>
					<div class="center" id="chatroom-header"></div>
					<div class="right"><ons-toolbar-button icon="fa-cog" onclick="fn.showChatroomSettings()" id="show-chatroom-settings"></ons-toolbar-button><ons-toolbar-button icon="fa-user-group" onclick="fn.open('chatroom-menu')" id="show-chatroom-list"></ons-toolbar-button></div>
				</ons-toolbar>
				<div class="arrowchat_chatroom_chat_content"></div>
				<ons-bottom-toolbar>
					<div class="giphy-wrapper">
						<div class="arrowchat_giphy_box">
							<div class="arrowchat_giphy_image_wrapper2"></div>
							<div class="arrowchat_giphy_search_wrapper2">
								<div class="giphy_cancel_container">
									<ons-toolbar-button icon="fa-times" class="giphy_cancel2"></ons-toolbar-button>
								</div>
								<div class="giphy_search_container">
									<input type="text" class="arrowchat_giphy_search2 textarea textarea--transparent" placeholder="<?php echo $language[214]; ?>" tabindex="-1" />
								</div>
							</div>
						</div>
					</div>
					<div class="textcontainer">
						<div class="sendbutton">
							<ons-toolbar-button icon="fa-paper-plane-top" id="send_button_chatroom"></ons-toolbar-button>
						</div>
						<div class="textinput">
							<textarea id="textinput2" class="textarea textarea--transparent" placeholder="<?php echo $language[208]; ?>" tabindex="-1"></textarea>
						</div>
					</div>
					<div class="msg_controls2">
						<ons-icon icon="fa-camera" class="fas" id="arrowchat_upload_button2"><div id="arrowchat_chatroom_uploader2"></div></ons-icon>
						<ons-icon icon="fa-image" class="far arrowchat_giphy_button2"></ons-icon>
						<ons-icon icon="fa-smile" class="far arrowchat_smiley_button2"></ons-icon>
					</div>
					<div class="smiley_box_wrapper" id="smiley_box_wrapper3"></div>
				</ons-bottom-toolbar>
			</ons-page>
		</template>
		<div id="arrowchat_user_upload_queue" class="arrowchat_users_upload_queue"></div>
    </body>
</html>
