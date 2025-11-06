			<div class="title_bg"> 
				<div class="title">General</div> 
				<div class="module_content">
					<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?do=<?php echo $do; ?>" enctype="multipart/form-data">
<?php
	if ($do == "chatfeatures") 
	{
?>
					<div class="subtitle">General Features</div>
					<fieldset class="firstFieldset">
						<?php
							if (ARROWCHAT_EDITION == "lite")
							{
						?>
							<input type="hidden" name="chatrooms_on" value="0" />
							<input type="hidden" name="notifications_on" value="0" />
							<input type="hidden" name="moderation_on" value="0" />
						<?php
							} else {
						?>
						<dl class="selectionBox">
							<dt>Bar Features</dt>
							<dd>
								<ul>
									<li>
										<label for="chatrooms_on">
											<input type="checkbox" id="chatrooms_on" name="chatrooms_on" <?php if($chatrooms_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Chat Rooms
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to use the chat rooms feature.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="notifications_on">
											<input type="checkbox" id="notifications_on" name="notifications_on" <?php if($notifications_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Site Notifications
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to use the site notifications feature. This feature must first be installed correctly to function. Please visit http://www.arrowchat.com/support/ for documentation on installation.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="enable_moderation">
											<input type="checkbox" id="enable_moderation" name="enable_moderation" <?php if($enable_moderation == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Moderation
										</label>
									</li>
								</ul>
								<p class="explain">
									Enables a report abuse/spam button for users and the moderation tab in the chat bar for moderators and admins.
								</p>
							</dd>
							</div>
						</dl>
					<?php
						}
					?>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="online_list_on">
											<input type="checkbox" id="online_list_on" name="online_list_on" <?php if($online_list_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Online List
										</label>
									</li>
								</ul>
								<p class="explain">
									Unchecking this will hide the online list. One-on-one chat can still be initiated through chat rooms or via a link using our API.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="first_time_message_on">
											<input type="checkbox" id="first_time_message_on" name="first_time_message_on" <?php if($first_time_message_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable First-Time Message
										</label>
									</li>
								</ul>
								<p class="explain">
									The first-time message is a tooltip that will display next to the chat bubble telling your user about the chat function. It will display once per session until clicked.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<label for="first_time_message_header">First-Time Message Header</label>
								<input type="text" autocomplete="off" id="first_time_message_header" class="selectionText" name="first_time_message_header" value="<?php echo $first_time_message_header; ?>" />
								<p class="explain">
									This is the bolded header text for the first-time message.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<label for="first_time_message_content">First-Time Message Content</label>
								<input type="text" autocomplete="off" id="first_time_message_content" class="selectionText" name="first_time_message_content" value="<?php echo $first_time_message_content; ?>" />
								<p class="explain">
									This is the main text for the first-time message.
								</p>
							</dd>
							</div>
						</dl>
					</fieldset>
					<fieldset>
					<?php
						if (ARROWCHAT_EDITION != "starter")
						{
					?>
						<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
						<dl class="selectionBox">
							<dt>Mobile Chat Features</dt>
							<dd>
								<ul>
									<li>
										<label for="enable_mobile">
											<input type="checkbox" id="enable_mobile" name="enable_mobile" <?php if($enable_mobile == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Chat Tab on Mobile Devices
										</label>
									</li>
								</ul>
								<p class="explain">
									This will enable a small floating tab for mobile devices that will register the user as logged in, display the buddy list count, and number of unread messages. Clicking it will take them to the full <a href="../public/mobile/">mobile application</a>.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="mobile_chat_action">Mobile Chat Tab Action</label>
								<select name="mobile_chat_action" id="mobile_chat_action" style="width: 454px;">
									<option value="1" <?php if (empty($mobile_chat_action) OR $mobile_chat_action == 1) echo 'selected="selected"'; ?>>Pop-up in same page</option>
									<option value="2" <?php if ($mobile_chat_action == 2) echo 'selected="selected"'; ?>>Open a new page</option>
								</select>
								<p class="explain">
									Select what should happen when a user clicks on the mobile chat tab.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="mobile_chat_icon">Font Awesome Icon</label>
								<input type="text" autocomplete="off" id="mobile_chat_icon" class="selectionText" name="mobile_chat_icon" value="<?php echo $mobile_chat_icon; ?>" />
								<p class="explain">
									Enter the Font Awesome class to use for the icon
								</p>
							</dd>
						</dl>
						</div>
					<?php
						}
					?>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt>User Features</dt>
							<dd>
								<ul>
									<li>
										<label for="popout_chat_on">
											<input type="checkbox" id="popout_chat_on" name="popout_chat_on" <?php if($popout_chat_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Users to Pop Out the Chat
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to use pop out chat.
								</p>
							</dd>
							</div>
						</dl>
					</fieldset>
					<fieldset>
						<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
						<dl class="selectionBox">
							<dt>Video Chat Features</dt>
							<dd>
								<ul>
									<li>
										<label for="video_chat">
											<input type="checkbox" id="video_chat" name="video_chat" <?php if($video_chat == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Users to Video Chat
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to video chat with each other.
								</p>
							</dd>
						</dl>
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$('#video_chat_selection').change(function() {
									if ($('#video_chat_selection').val() == 2) {
										$('.tokbox').show();
									} else {
										$('.tokbox').hide();
									}
									if ($('#video_chat_selection').val() == 3) {
										$('.agora').show();
									} else {
										$('.agora').hide();
									}
								});
							});
						</script>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="video_chat_selection">Video Chat Service</label>
								<select name="video_chat_selection" id="video_chat_selection" style="width: 454px;">
									<option value="1" <?php if (empty($video_chat_selection) OR $video_chat_selection == 1) echo 'selected="selected"'; ?>>Jitsi (free)</option>
								<?php
									if (ARROWCHAT_EDITION == "business" OR ARROWCHAT_EDITION == "enterprise")
									{
								?>
									<option value="2" <?php if ($video_chat_selection == 2) echo 'selected="selected"'; ?>>Vonage/Tokbox (best; paid; requires SSL)</option>
									<option value="3" <?php if ($video_chat_selection == 3) echo 'selected="selected"'; ?>>Agora.io (best; 10k free mins; requires SSL)</option>
									<!--<option value="4" <?php if ($video_chat_selection == 4) echo 'selected="selected"'; ?>>Jitsi (better; free)</option>-->
								<?php
									}
								?>
								</select>
								<p class="explain">
									The video chat service that you would like users to use.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="video_chat_height">Default Video Height</label>
								<input type="text" id="video_chat_height" class="selectionText" name="video_chat_height" value="<?php echo $video_chat_height; ?>" />
								<p class="explain">
									The default video height in pixels.  Enter a number only.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="video_chat_width">Default Video Width</label>
								<input type="text" id="video_chat_width" class="selectionText" name="video_chat_width" value="<?php echo $video_chat_width; ?>" />
								<p class="explain">
									The default video width in pixels.  Enter a number only.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox tokbox" <?php if ($video_chat_selection != 2) echo 'style="display:none"'; ?>>
							<dt></dt>
							<dd>
								<label for="tokbox_api">Vonage API Key</label>
								<input type="text" id="tokbox_api" class="selectionText" name="tokbox_api" value="<?php echo $tokbox_api; ?>" />
								<p class="explain">
									Your project API key. Both your API key and secret key are available in your Vonage account after creating a video API account on <a href="https://www.vonage.com/communications-apis/video/" target="_blank">vonage.com</a>.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox tokbox" <?php if ($video_chat_selection != 2) echo 'style="display:none"'; ?>>
							<dt></dt>
							<dd>
								<label for="tokbox_secret">Vonage Secret</label>
								<input type="text" id="tokbox_secret" class="selectionText" name="tokbox_secret" value="<?php echo $tokbox_secret; ?>" />
								<p class="explain">
									Your project secret key.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox agora" <?php if ($video_chat_selection != 3) echo 'style="display:none"'; ?>>
							<dt></dt>
							<dd>
								<label for="agora_app_certificate"><b>Instructions</b></label>
								<p class="explain">
									Create an account on <a href="https://www.agora.io/" target="_blank">agora.io</a>. Create a new project and select the APP ID + APP Certificate + Token authentication mechanism.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox agora" <?php if ($video_chat_selection != 3) echo 'style="display:none"'; ?>>
							<dt></dt>
							<dd>
								<label for="agora_app_id">Agora.io App ID</label>
								<input type="text" id="agora_app_id" class="selectionText" name="agora_app_id" value="<?php echo $agora_app_id; ?>" />
								<p class="explain">
									Your project app ID.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox agora" <?php if ($video_chat_selection != 3) echo 'style="display:none"'; ?>>
							<dt></dt>
							<dd>
								<label for="agora_app_certificate">Agora.io App Certificate</label>
								<input type="text" id="agora_app_certificate" class="selectionText" name="agora_app_certificate" value="<?php echo $agora_app_certificate; ?>" />
								<p class="explain">
									Your project app certificate.
								</p>
							</dd>
						</dl>
						</div>
					</fieldset>
					<fieldset>
						<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
						<dl class="selectionBox">
							<dt>File Upload Features</dt>
							<dd>
								<ul>
									<li>
										<label for="file_transfer_on">
											<input type="checkbox" id="file_transfer_on" name="file_transfer_on" <?php if($file_transfer_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Private File Transfers
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to transfer files with each other. CHMOD your uploads folder to 777 before enabling this feature.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="chatroom_transfer_on">
											<input type="checkbox" id="chatroom_transfer_on" name="chatroom_transfer_on" <?php if($chatroom_transfer_on == 1) echo 'checked="checked"';  ?> value="1" />
											Enable Chat Room File Transfers
										</label>
									</li>
								</ul>
								<p class="explain">
									Allows users to transfer files in chat rooms. CHMOD your uploads folder to 777 before enabling this feature.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<label for="max_upload_size">Max Upload Size</label>
								<input type="text" id="max_upload_size" class="selectionText" name="max_upload_size" value="<?php echo $max_upload_size; ?>" />
								<p class="explain">
									The maximum amount of MB that a user can upload.  Enter a number only.
								</p>
							</dd>
						</dl>
						</div>
					</fieldset>
					<dl class="selectionBox submitBox">
						<dt></dt>
						<dd>
							<div class="floatr">
								<a class="fwdbutton" onclick="document.forms[0].submit(); return false">
									<span>Save Changes</span>
								</a>
								<input type="hidden" name="chatfeatures_submit" value="1" />
							</div>
						</dd>
					</dl>
<?php
	}
?>
<?php
	if ($do == "embedcodes") 
	{
?>
			   <script type="text/javascript">
					jQuery(document).ready(function($) {
						$("#user_chat_select").click(function() {
							$('#user_chat').select();
						});
						$("#chat_rooms_select").click(function() {
							$('#chat_rooms').select();
						});
						$("#buddy_list_select").click(function() {
							$('#buddy_list').select();
						});
						$('#uc_width').keyup(function() {
							updateUserChat();
						});
						$('#uc_height').keyup(function() {
							updateUserChat();
						});
						$('#uc_border').click(function() {
							updateUserChat();
						});
						$('#cr_width').keyup(function() {
							updateChatRooms();
						});
						$('#cr_height').keyup(function() {
							updateChatRooms();
						});
						$('#cr_border').click(function() {
							updateChatRooms();
						});
						$('#cr_autohide').click(function() {
							updateChatRooms();
						});
						$('#cr_autoselect').click(function() {
							updateChatRooms();
						});
						$('#chatroom_auto_join').change(function() {
							updateChatRooms();
						});
						$('#bl_width').keyup(function() {
							updateBuddyList();
						});
						$('#bl_height').keyup(function() {
							updateBuddyList();
						});
						$('#bl_max_results').keyup(function() {
							updateBuddyList();
						});
					});
					function updateUserChat() {
						var width = $('#uc_width').val();
						var height = $('#uc_height').val();
						var border = $('#uc_border').attr('checked');
						if (border) {
							border = 1;
						} else {
							border = 0;
						}
						$("#user_chat").val('<iframe src="<?php echo $base_url; ?>public/popout/" width="'+width+'" height="'+height+'" frameborder="0" style="border:'+border+'px solid #aaa"></iframe>');
					}
					function updateChatRooms() {
						var width = $('#cr_width').val();
						var height = $('#cr_height').val();
						var border = $('#cr_border').attr('checked');
						var autohide = $('#cr_autohide').attr('checked');
						var autoselect = $('#cr_autoselect').attr('checked');
						var room = $('#chatroom_auto_join').val();
						if (border) {
							border = 1;
						} else {
							border = 0;
						}
						if (autohide) {
							autohide = 1;
						} else {
							autohide = 0;
						}
						if (autoselect) {
							autoselect = 1;
						} else {
							autoselect = 0;
						}
						$("#chat_rooms").val('<iframe src="<?php echo $base_url; ?>public/popout/?cid='+room+'&ah='+autohide+'&sc='+autoselect+'" width="'+width+'" height="'+height+'" frameborder="0" style="border:'+border+'px solid #aaa"></iframe>');
					}
					function updateBuddyList() {
						var width = $('#bl_width').val();
						var height = $('#bl_height').val();
						var max_results = $('#bl_max_results').val();
						var bracket = '<';
						$("#buddy_list").val('<link type="text/css" rel="stylesheet" media="all" href="<?php echo $base_url; ?>public/list/css/style.css" charset="utf-8" />'+bracket+'script type="text/javascript">var ac_max_results = '+max_results+';'+bracket+'/script><script type="text/javascript" src="<?php echo $base_url; ?>public/list/js/list_core.js" charset="utf-8">'+bracket+'/script><div id="arrowchat_public_list" style="width:'+width+'; height:'+height+';"></div>');
					}
				</script>
					<div class="subtitle">Embed Codes</div>
					
					<fieldset class="firstFieldset">
					<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
						<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
						<dl class="selectionBox">
							<dt><label>Buddy List Embed Code</label></dt>
							<dd>
								<ul>
									<li>
										<label>
											
										</label>
									</li>
								</ul>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Width</label></dt>
							<dd>
								<input type="text" id="bl_width" class="selectionText" value="170px" />
								<p class="explain">
									Enter the width, in pixels, that you want the box to be.  You can enter "100%" also.  You need to enter "px" or "%" after the number.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Height</label></dt>
							<dd>
								<input type="text" id="bl_height" class="selectionText" value="162px" />
								<p class="explain">
									Enter the height, in pixels, that you want the box to be.  You can enter "100%" also.  You need to enter "px" or "%" after the number.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Max Results</label></dt>
							<dd>
								<input type="text" id="bl_max_results" class="selectionText" value="0" />
								<p class="explain">
									Enter the maximum number of users that you would like to display.  Entering 0 will display all.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Code</label></dt>
							<dd>
								<textarea id="buddy_list" class="selectionArea">&lt;link type="text/css" rel="stylesheet" media="all" href="<?php echo $base_url; ?>public/list/css/style.css" charset="utf-8" /&gt; 
&lt;script type="text/javascript"&gt;var ac_max_results = 0;&lt;/script&gt;
&lt;script type="text/javascript" src="<?php echo $base_url; ?>public/list/js/list_core.js" charset="utf-8"&gt;&lt;/script&gt;
&lt;div id="arrowchat_public_list"&gt;&lt;/div&gt;</textarea>
								<p>
									<a title="Select all the code" href="javascript:;" id="buddy_list_select">Select All</a><br /><br />
								</p>
								<p class="explain">
									Please understand that this code must be put on a page where the ArrowChat djs script code and jQuery is on.
								</p>
							</dd>
						</dl>
						</div>
					</fieldset>
					<fieldset>
					<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
						<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
						<dl class="selectionBox">
							<dt><label>Chat Embed Code</label></dt>
							<dd>
								<ul>
									<li>
										<label>
											
										</label>
									</li>
								</ul>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Width</label></dt>
							<dd>
								<input type="text" id="cr_width" class="selectionText" value="600" />
								<p class="explain">
									Enter the width, in pixels, that you want the box to be.  You can enter "100%" also.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Height</label></dt>
							<dd>
								<input type="text" id="cr_height" class="selectionText" value="300" />
								<p class="explain">
									Enter the height, in pixels, that you want the box to be.  You can enter "100%" also.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="chatroom_auto_join">Chat Room</label>
							</dt>
							<dd>
								<select name="chatroom_auto_join" id="chatroom_auto_join" style="width: 454px;">
									<option value="0" <?php if (empty($chatroom_auto_join)) echo 'selected="selected"'; ?>>No auto-open chat room</option>
								<?php
									$result = $db->execute("
										SELECT * 
										FROM arrowchat_chatroom_rooms 
										ORDER BY id ASC
									");

									if ($result AND $db->count_select() > 0) 
									{
										while ($row = $db->fetch_array($result)) 
										{
								?>
									<option value="<?php echo $row['id']; ?>" <?php if ($chatroom_auto_join == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']; ?></option>
								<?php
										}
									}
								?>
								</select>
							<p class="explain">
								If you'd like a chat room to automatically open, select it here. If left blank, chat window will automatically open.
							</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="cr_border">
											<input type="checkbox" id="cr_border" name="cr_border" checked="checked" value="1" />
											Enable border
										</label>
									</li>
								</ul>
								<p class="explain">
									Puts a border around the embed.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="cr_autohide">
											<input type="checkbox" id="cr_autohide" name="cr_autohide" value="1" />
											Auto-hide Left Panel
										</label>
									</li>
								</ul>
								<p class="explain">
									This will auto hide the left panel until the user chooses to unhide it.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="cr_autoselect">
											<input type="checkbox" id="cr_autoselect" name="cr_autoselect" value="1" />
											Auto-select Chat Room List
										</label>
									</li>
								</ul>
								<p class="explain">
									This will auto-select the chat room list on load instead of private chat.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Code</label></dt>
							<dd>
								<textarea id="chat_rooms" class="selectionArea">&lt;iframe src="<?php echo $base_url; ?>public/popout/" width="600" height="300" frameborder="0" style="border:1px solid #aaa"&gt;&lt;/iframe&gt;</textarea>
								<p>
									<a title="Select all the code" href="javascript:;" id="chat_rooms_select">Select All</a><br /><br />
								</p>
							</dd>
						</dl>
						</div>
					</fieldset>
<?php
	}
?>
<?php
	if ($do == "chatsettings") 
	{
?>
					<div class="subtitle">General Settings</div>
					<fieldset class="firstFieldset">
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="guests_can_view">
											<input type="checkbox" id="guests_can_view" name="guests_can_view" <?php if($guests_can_view == 1) echo 'checked="checked"'; ?> value="1" />
											Guests Can View the Bar
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will allow guests to see a message telling them to register or login.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="guests_can_chat">
											<input type="checkbox" id="guests_can_chat" name="guests_can_chat" <?php if($guests_can_chat == 1) echo 'checked="checked"'; ?> value="1" />
											Guests Can Chat
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will allow guests to be able to chat without logging in.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="guest_name_change">
											<input type="checkbox" id="guest_name_change" name="guest_name_change" <?php if($guest_name_change == 1) echo 'checked="checked"'; ?> value="1" />
											Guests Can Change Name
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will allow guests to be able to change their name while chatting instead of the generic "Guest {Random Number}".
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="guest_name_duplicates">
											<input type="checkbox" id="guest_name_duplicates" name="guest_name_duplicates" <?php if($guest_name_duplicates == 1) echo 'checked="checked"'; ?> value="1" />
											Guests Can Have Duplicate Names
										</label>
									</li>
								</ul>
								<p class="explain">
									If guests are allowed to change their name, checking this will allow guests to have the same name.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="guest_name_bad_words">Guest Name Blocked Words</label>
							</dt>
							<dd>
								<input type="text" id="guest_name_bad_words" class="selectionText" name="guest_name_bad_words" value="<?php echo $guest_name_bad_words; ?>" />
								<p class="explain">
									SEPARATE WITH COMMAS. Enter words that you would like to be blocked when guests choose a name. Caution: Entering "ass" would also block "grass".
								</p>
							</dd>
						</dl>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="disable_buddy_list">
										<?php
											if (NO_FREIND_SYSTEM == 1)
											{
										?>
											<input type="checkbox" id="disable_buddy_list" disabled="disabled" checked="checked" 
value="1" />
											<input type="hidden" name="disable_buddy_list" value="1" />
										<?php
											} else {
										?>
											<input type="checkbox" id="disable_buddy_list" name="disable_buddy_list" <?php if($disable_buddy_list == 1) echo 'checked="checked"'; ?> value="1" />
										<?php
											}
										?>
											Show All Online Users
										</label>
									</li>
								</ul>
								<p class="explain">
								<?php
									if (NO_FREIND_SYSTEM == 1)
									{
								?>
									This feature has been disabled because we detected that your site has no friend system.  You can change this in the includes/config.php file.
								<?php
									} else {
								?>
									Checking this will disable the friend's list and everyone on your site will be able to chat with anyone online.
								<?php
									}
								?>
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="hide_admins_buddylist">
											<input type="checkbox" id="hide_admins_buddylist" name="hide_admins_buddylist" <?php if($hide_admins_buddylist == 1) echo 'checked="checked"'; ?> value="1" />
											Hide Administrators
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will hide all administrators from the buddy list.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Guests Chat With</label></dt>
							<dd>
								<ul>
									<li>
										<label for="chat_with_guests_1">
											<input type="radio" name="guests_chat_with" value="1" id="chat_with_guests_1" <?php if($guests_chat_with == 1) echo 'checked="checked"'; ?> /> Guests Only
										</label>
									</li>
									<li>
										<label for="chat_with_guests_2">
											<input type="radio" name="guests_chat_with" value="2" id="chat_with_guests_2" <?php if($guests_chat_with == 2) echo 'checked="checked"'; ?> /> Guests and Members
										</label>
									</li>
									<li>
										<label for="chat_with_guests_3">
											<input type="radio" name="guests_chat_with" value="3" id="chat_with_guests_3" <?php if($guests_chat_with == 3) echo 'checked="checked"'; ?> /> Members Only
										</label>
									</li>
									<li>
										<label for="chat_with_guests_4">
											<input type="radio" name="guests_chat_with" value="4" id="chat_with_guests_4" <?php if($guests_chat_with == 4) echo 'checked="checked"'; ?> /> Admins Only
										</label>
									</li>
								</ul>
								<p class="explain">
									Select what guest users will see in their buddy list if guest chat is enabled.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt><label>Users Chat With</label></dt>
							<dd>
								<ul>
									<li>
										<label for="chat_with_members_1">
											<input type="radio" name="users_chat_with" value="1" id="chat_with_members_1" <?php if($users_chat_with == 1) echo 'checked="checked"'; ?> /> Guests Only
										</label>
									</li>
									<li>
										<label for="chat_with_members_2">
											<input type="radio" name="users_chat_with" value="2" id="chat_with_members_2" <?php if($users_chat_with == 2) echo 'checked="checked"'; ?> /> Guests and Members
										</label>
									</li>
									<li>
										<label for="chat_with_members_3">
											<input type="radio" name="users_chat_with" value="3" id="chat_with_members_3" <?php if($users_chat_with == 3) echo 'checked="checked"'; ?> /> Members Only
										</label>
									</li>
									<li>
										<label for="chat_with_members_4">
											<input type="radio" name="users_chat_with" value="4" id="chat_with_members_4" <?php if($users_chat_with == 4) echo 'checked="checked"'; ?> /> Admins Only
										</label>
									</li>
								</ul>
								<p class="explain">
									Select what logged in users will see in their buddy list.
								</p>
							</dd>
						</dl>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="disable_avatars">
											<input type="checkbox" id="disable_avatars" name="disable_avatars" <?php if($disable_avatars == 1) echo 'checked="checked"'; ?> value="1" />
											Disable the Use of Avatars
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will disable avatars across your entire chat.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="disable_smilies">
											<input type="checkbox" id="disable_smilies" name="disable_smilies" <?php if($disable_smilies == 1) echo 'checked="checked"'; ?> value="1" />
											Disable the Use of Emojis
										</label>
									</li>
								</ul>
								<p class="explain">
									Check this to disable emojis in chat.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="giphy_off">
											<input type="checkbox" id="giphy_off" name="giphy_off" <?php if($giphy_off == 1) echo 'checked="checked"'; ?> value="1" />
											Disable the Use of GIPHY
										</label>
									</li>
								</ul>
								<p class="explain">
									Check this to disable GIPHY support in chat.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="giphy_chatroom_off">
											<input type="checkbox" id="giphy_chatroom_off" name="giphy_chatroom_off" <?php if($giphy_chatroom_off == 1) echo 'checked="checked"'; ?> value="1" />
											Disable the Use of GIPHY in Chat Rooms
										</label>
									</li>
								</ul>
								<p class="explain">
									Check this to disable GIPHY support in chat rooms.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<?php if (ARROWCHAT_EDITION == "free") { ?><div class="unlock-feature">Unlock this feature by purchasing an <a href="https://www.arrowchat.com/purchase/" target="_blank">ArrowChat license</a></div><?php } ?>
							<div class="<?php if (ARROWCHAT_EDITION == "free") echo 'version-disabled'; ?>">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="enable_rtl">
											<input type="checkbox" id="enable_rtl" name="enable_rtl" <?php if($enable_rtl == 1) echo 'checked="checked"'; ?> value="1" />
											Enable Right-to-Left Text (beta)
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will set up ArrowChat to support right-to-left languages.
								</p>
							</dd>
							</div>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="us_time">
											<input type="checkbox" id="us_time" name="us_time" <?php if($us_time == 1) echo 'checked="checked"'; ?> value="1" />
											Enable AM/PM Time Format
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking will use the am/pm time format. Unchecking it will use the 24 hour time format.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="show_full_username">
											<input type="checkbox" id="show_full_username" name="show_full_username" <?php if($show_full_username == 1) echo 'checked="checked"'; ?> value="1" />
											Enable Showing Full Names
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will show the full names in chat instead of omitting things past a space.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="blocked_words">Blocked Words</label>
							</dt>
							<dd>
								<input type="text" id="blocked_words" class="selectionText" name="blocked_words" value="<?php echo $blocked_words; ?>" />
								<p class="explain">
									SEPARATE WITH COMMAS. Enter the word in square brackets for an exact match. For example, entering ass would also block grass but entering [ass] would only block that word.
								</p>
							</dd>
						</dl>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="admin_chat_all">
											<input type="checkbox" id="admin_chat_all" name="admin_chat_all" <?php if($admin_chat_all == 1) echo 'checked="checked"'; ?> value="1" />
											ArrowChat Admins/Mods Can View All Online
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will allow administrators and moderators to view all online users instead of just friends. You can set a user as an admin by clicking 'Manage Users'.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="admin_view_maintenance">
											<input type="checkbox" id="admin_view_maintenance" name="admin_view_maintenance" <?php if($admin_view_maintenance == 1) echo 'checked="checked"'; ?> value="1" />
											ArrowChat Admins Ignore Maintenance Mode
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will allow administrators to still be able to use the chat bar when maintenance mode is enabled.
								</p>
							</dd>
						</dl>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="chat_maintenance">
											<input type="checkbox" id="chat_maintenance" name="chat_maintenance" <?php if($chat_maintenance == 1) echo 'checked="checked"'; ?> value="1" />
											Enable Chat Maintenance
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will disable chat on your site with a message saying it is down for maintenance.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt></dt>
							<dd>
								<ul>
									<li>
										<label for="disable_arrowchat">
											<input type="checkbox" id="disable_arrowchat" name="disable_arrowchat" <?php if($disable_arrowchat == 1) echo 'checked="checked"'; ?> value="1" />
											Disable ArrowChat
										</label>
									</li>
								</ul>
								<p class="explain">
									Checking this will completely disable ArrowChat on your site until it is unchecked again.
								</p>
							</dd>
						</dl>
					</fieldset>
					<dl class="selectionBox submitBox">
						<dt></dt>
						<dd>
							<div class="floatr">
								<a class="fwdbutton" onclick="document.forms[0].submit(); return false">
									<span>Save Changes</span>
								</a>
								<input type="hidden" name="chatsettings_submit" value="1" />
							</div>
						</dd>
					</dl>
<?php
	}
?>

<?php
	if ($do == "chatstyle") {
?>
					<link rel="stylesheet" href="includes/css/colorpicker.css" type="text/css" media="screen">
					<script type="text/javascript" src="includes/js/colorpicker.js"></script> 
					<script type="text/javascript">
						$(document).ready(function() {
							$( "#bar_left, #bar_right" ).sortable({
								connectWith: ".horzlist",
								axis: "x",
								items: "li:not(.ui-state-disabled)",
								placeholder: "ui-state-highlight",
								update: function() {
									var b = $(this).parent().attr('id');
									if (b == "left") {
										$(this).find('img').removeClass('border-left');
										$(this).find('img').addClass('border-right');
									} else {
										$(this).find('img').removeClass('border-right');
										$(this).find('img').addClass('border-left');
									}
								}
							}).disableSelection();
							$('#num_closed_windows').slider({
								value: <?php echo $num_closed_windows; ?>,
								min: 2,
								max: 10,
								step: 1,
								slide: function ( event, ui ) {
									$('#num_closed_windows_amt').val( ui.value );
									$('#num_closed_windows_amt2').html( ui.value );
								}
							});
							$('#window_left_padding').slider({
								value: <?php echo $window_left_padding; ?>,
								min: 30,
								max: 300,
								step: 2,
								slide: function ( event, ui ) {
									$('#window_left_padding_amt').val( ui.value );
									$('#window_left_padding_amt2').html( ui.value );
								}
							});
							$('#allowed_closed_windows').slider({
								value: <?php echo $allowed_closed_windows; ?>,
								min: 1,
								max: 40,
								step: 1,
								slide: function ( event, ui ) {
									$('#allowed_closed_windows_amt').val( ui.value );
									$('#allowed_closed_windows_amt2').html( ui.value );
								}
							});
							$('#allowed_open_windows').slider({
								value: <?php echo $allowed_open_windows; ?>,
								min: 1,
								max: 10,
								step: 1,
								slide: function ( event, ui ) {
									$('#allowed_open_windows_amt').val( ui.value );
									$('#allowed_open_windows_amt2').html( ui.value );
								}
							});
							$('#admin_background_color').ColorPicker({
								onSubmit: function(hsb, hex, rgb, el) {
									$(el).val(hex);
									$(el).ColorPickerHide();
								},
								onBeforeShow: function() {
									$(this).ColorPickerSetColor(this.value);
								},
								onChange: function (hsb, hex, rgb) {
									$('#admin_background_color').css('backgroundColor', '#' + hex);
									$('#admin_background_color').val(hex);
								}
							}).bind('keyup', function() {
								$(this).ColorPickerSetColor(this.value);
								$('#admin_background_color').css('backgroundColor', '#' + this.value);
							});
							$('#admin_text_color').ColorPicker({
								onSubmit: function(hsb, hex, rgb, el) {
									$(el).val(hex);
									$(el).ColorPickerHide();
								},
								onBeforeShow: function() {
									$(this).ColorPickerSetColor(this.value);
								},
								onChange: function (hsb, hex, rgb) {
									$('#admin_text_color').css('backgroundColor', '#' + hex);
									$('#admin_text_color').val(hex);
								}
							}).bind('keyup', function() {
								$(this).ColorPickerSetColor(this.value);
								$('#admin_text_color').css('backgroundColor', '#' + this.value);
							});
							$('#admin_text_color').css('backgroundColor', '#<?php echo $admin_text_color; ?>');
							$('#admin_background_color').css('backgroundColor', '#<?php echo $admin_background_color; ?>');
						});
					</script>
					<style type="text/css">
						.ui-state-highlight { height: 1.5em; line-height: 1.2em; border: 1px solid #000; }
						.border-right {
							border-right: 1px solid #999;
						}
						.border-left {
							border-left: 1px solid #999;
						}
						.horzlist {
							min-width: 16px;
							height: 30px;
						}
						.horzlist li
						{
							float: left;
							list-style-type: none;
							height: 30px;
							min-width: 16px;
							cursor: e-resize;
						}
						.ui-state-highlight { 
							background-color: #cecece;
							border: 1px dashed #333;
							width: 110px;
						}
						.ui-state-disabled {
							cursor: auto !important;
						}
						#left {
							float: left;
						}
						#right {
							float: right;
						}
					</style>
					<div class="subtitle">General Settings</div>
					<fieldset class="firstFieldset">
						<dl class="selectionBox">
							<dt>
								<label for="num_closed_windows">Number of Closed Windows</label>
							</dt>
							<dd>
								<div id="num_closed_windows" class="slider"></div><div id="num_closed_windows_amt2" class="slider-number"><?php echo $num_closed_windows; ?></div>
								<input type="hidden" id="num_closed_windows_amt" name="num_closed_windows" value="<?php echo $num_closed_windows; ?>" />
								<p class="explain">
									The number of allowed closed chat windows allowed before they group together. Default: 5
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="window_left_padding">Window Left Padding</label>
							</dt>
							<dd>
								<div id="window_left_padding" class="slider"></div><div id="window_left_padding_amt2" class="slider-number"><?php echo $window_left_padding; ?></div>
								<input type="hidden" id="window_left_padding_amt" name="window_left_padding" value="<?php echo $window_left_padding; ?>" />
								<p class="explain">
									The padding, in pixels, that the open chat windows will collapse when approaching the edge of the browser window. Default: 160
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="allowed_closed_windows">No. of Remembered Closed Windows</label>
							</dt>
							<dd>
								<div id="allowed_closed_windows" class="slider"></div><div id="allowed_closed_windows_amt2" class="slider-number"><?php echo $allowed_closed_windows; ?></div>
								<input type="hidden" id="allowed_closed_windows_amt" name="allowed_closed_windows" value="<?php echo $allowed_closed_windows; ?>" />
								<p class="explain">
									The number of closed chat windows that are remembered when the user changes to another page. Setting this to a higher amount will use more resources. This will create a bad user experience if set too low. Default: 20
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="allowed_open_windows">No. of Remembered Open Windows</label>
							</dt>
							<dd>
								<div id="allowed_open_windows" class="slider"></div><div id="allowed_open_windows_amt2" class="slider-number"><?php echo $allowed_open_windows; ?></div>
								<input type="hidden" id="allowed_open_windows_amt" name="allowed_open_windows" value="<?php echo $allowed_open_windows; ?>" />
								<p class="explain">
									The number of open chat windows that are remembered when the user changes to another page. Setting this to a higher amount will use more resources. This will create a bad user experience if set too low. Default: 3
								</p>
							</dd>
						</dl>
					</fieldset>
					<fieldset>
						<dl class="selectionBox">
							<dt>
								<label for="admin_background_color">Admin Background Color</label>
							</dt>
							<dd>
								<input type="text" id="admin_background_color" class="selectionText" name="admin_background_color" value="<?php echo $admin_background_color; ?>" />
								<p class="explain">
									Specify a special background color for ArrowChat admins in the buddy list.  This should be in hex format without a leading #.  Leave blank for no special distinction.  Further customization can be done with the "arrowchat_buddylist_admin_1" CSS class.
								</p>
							</dd>
						</dl>
						<dl class="selectionBox">
							<dt>
								<label for="admin_text_color">Admin Text Color</label>
							</dt>
							<dd>
								<input type="text" id="admin_text_color" class="selectionText" name="admin_text_color" value="<?php echo $admin_text_color; ?>" />
								<p class="explain">
									Specify a special text color for ArrowChat admins in the buddy list.  This should be in hex format without a leading #.  Leave blank for no special distinction.  Further customization can be done with the "arrowchat_buddylist_admin_1" CSS class.
								</p>
							</dd>
						</dl>
					</fieldset>
					<dl class="selectionBox submitBox">
						<dt></dt>
						<dd>
							<div class="floatr">
								<a class="fwdbutton" onclick="document.forms[0].submit(); return false">
									<span>Save Changes</span>
								</a>
								<input type="hidden" name="chatstyle_submit" value="1" />
							</div>
						</dd>
					</dl>
<?php
	}
?>

					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					</form>

				</div>
			</div>