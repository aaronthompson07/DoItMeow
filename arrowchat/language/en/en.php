<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #     Copyright 2010-2012 ArrowSuites LLC. All Rights Reserved.    # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/

	$language = array();

	// ########################### STATUS #############################
	$language[1]			=	"Available";								// Available users
	$language[2]			=	"Busy";										// Busy users
	$language[3]			=	"Invisible";								// Invisible users
	$status['available']	=	"I'm available";							// Default available status message
	$status['busy']			=	"I'm busy";									// Default busy status message
	$status['away']			=	"I'm idle";									// Default idle status message
	$status['offline']		=	"I'm offline";								// Default offline status message
	$status['invisible']	=	"I'm offline";								// Default invisible status message
	$language[83]			=	"Guest";									// Displayed if the user has no username
	$language[240]			=	"Away";										// Idle user status
	$language[241]			=	"Offline";									// Offline user status

	// ####################### SERVICE UPDATES ########################
	$language[27]			=	"Chat is currently down for maintenance";  // Hover message when chat is in maintenance mode
	$language[28]			=	"Close"; 									// Close the announcement message
	$language[58]			=	"You must login to chat";					// The message that guests view when logged out
	$language[303]			=	"We couldn't connect to the database. Please try again later.";	// Error message when unable to connect to database

	// ######################## NOTIFICATIONS #########################
	$language[0]			=	"Notifications"; 							// Displayed in the title bar of the notifications popup
	$language[9]   			=   "You have no new notifications."; 			// Displayed when a user has no new notifications
	$language[21]			=	"See past notifications"; 					// The tooltip to see all notifications
	$language[71]			=	"second ago";								// Displayed after the time in notifications (second)
	$language[72]			=	"seconds ago";								// Displayed after the time in notifications (seconds)
	$language[73]			=	"minute ago";								// Displayed after the time in notifications (minute)
	$language[74]			=	"minutes ago";								// Displayed after the time in notifications (minutes)
	$language[75]			=	"hour ago";									// Displayed after the time in notifications (hour)
	$language[76]			=	"hours ago";								// Displayed after the time in notifications (hours)
	$language[77]			=	"day ago";									// Displayed after the time in notifications (day)
	$language[78]			=	"days ago";									// Displayed after the time in notifications (days)
	$language[79]			=	"month ago";								// Displayed after the time in notifications (month)
	$language[80]			=	"months ago";								// Displayed after the time in notifications (months)
	$language[81]			=	"year ago";									// Displayed after the time in notifications (year)
	$language[82]			=	"years ago";								// Displayed after the time in notifications (years)
	$language[144]			=	"New message from ";						// DISPLAYS USERNAME AFTER - The title for HTML5 notifications

	// ######################### BUDDY LIST ###########################
	$language[4]			=	"Chat"; 									// Displayed in the title bar of the buddy list popup
	$language[7]			=	"Chat (Offline)"; 							// Displayed in the buddy list tab when offline
	$language[8]    		=   "<i class='fas fa-user-slash'></i>No one is available to chat. You can search for an offline user above."; 	// Displayed with no one is online
	$language[12]   		=   "Search for a friend"; 						// Displayed in the search text area of the buddy list
	$language[22]			=	"Status";									// Button to show status options in the buddy list
	$language[23]			=	"Options";									// Button to show options in the buddy list
	$language[26]			=	"No users found with that name";			// Displayed when no friends are found after searching
	$language[119]			=	"Username...";								// Displayed in the guest username box
	$language[120]			=	"You must enter a name.";					// Error message when the user enters no guest name
	$language[121]			=	"The name can only consist of letters and numbers.";	// Error message when the user enters a guest name with more than letter/numbers
	$language[122]			=	"There is a blocked word in your name: ";	// DISPLAYS BLOCKED WORD AFTER - Error message when the user enters a blocked word guest name
	$language[123]			=	"You cannot change your name again.";		// Error message when user trys to change guest name again
	$language[124]			=	"That name already exists.";				// Error message when duplicate guest name is selected
	$language[125]			=	"The name cannot be more than 25 characters.";// Error message when guest name is too long
	$language[300]			=	"Users Online";								// Text to close an open tab
	$language[301]			=	"Chat Rooms";								// Text to close an open tab
	$language[305]			=	"Moderation CP";							// Text to go to moderation control panel
	$language[306]			=	"Chat with others";							// Tooltip for buddylist
	$language[314]			=	"Enter a name to start chatting with other users on this site.";	// Text for guest name change
	$language[315]			=	"Welcome!";									// Header text for guest name change
	$language[316]			=	"Let's Go!";								// Text for guest name change button

	// ########################### OPTIONS ############################
	$language[5]			=	"Go offline";								// Option to go offline text
	$language[6]			=	"Message sounds";								// Option to play sound for new messages text
	$language[17]   		=   "Keep chat tab open";						// Option to keep the buddy list open text
	$language[18]   		=   "Hide user pictures";						// Option to hide avatars in the buddy list text
	$language[29]			=	"Theme:";									// Text to display next to the theme change select box
	$language[95]			=	"Manage block list";						// Option to manage the block list
	$language[96]			=	"Choose a user to unblock";					// Text to display when a user is managing the block list
	$language[97]			=	"Unblock";									// Text to display on unblock button
	$language[108]			=	"Select the theme you wish to use";			// Text to display when a user is choosing a theme
	$language[109]			=	"Choose";									// Text to display on the choose theme button
	$language[118]			=	"Select a user...";									// Text to display on the selection for the block menu
	$language[222]			=	"Cancel";									// Displayed on UI buttons to cancel an action
	$language[242]			=	"Change your name...";						// Option to manage guest name
	$language[243]			=	"Submit";									// Button to submit name change
	$language[244]			=	"Enter the name you'd like to use";			// Text for guest name change menu
	$language[299]			=	"Close tab";								// Text to close an open tab
	$language[302]			=	"Back";									// Text to go back from the block menu

	// ######################### HIDE CHAT ############################
	$language[14]   		=   "Hide Chat";								// Displayed when the user hovers over the hide chat button
	$language[15]   		=   "Show Chat";								// Displayed when the user hovers over the show chat button

	// ######################## POPOUT CHAT ############################
	$language[10]   		=   "Pop Out Chat";								// Option to pop out chat
	$language[11]   		=   "Pop In Chat";								// Option to pop in chat
	$language[221]			=	"Profile Link";								// Title text for the profile link
	$language[229]			=	"Hide Left Panel";							// Setting to hide the left panel in pop out chat
	$language[110]			=	"Chats";									// Displays in the header of the popout chat
	$language[295]			=	"Private";									// Tab for private chat
	$language[296]			=	"Chat Rooms";								// Tab for chat rooms
	$language[317]			=	"No open conversations. Click a user or room!";	// Popout message when no conversations are open

	// ############################ CHAT ###############################
	$language[13]  	 		=   "This user is offline. They will receive the message next login";		// DISPLAYS USERNAME FIRST - Shown when a message is sent to an offline user
	$language[24]			=	"Clear Conversation";													// Displayed in the chat popup to clear chat history
	$language[30]			=	"New message from";														// DISPLAYS USERNAME AFTER - Blinks in the title of the browser on new messages
	$language[59]			=	"More &#9660;";															// The text to display more chat options
	$language[60]			=	"Pop Out Chat";															// The pop out chat option in the more menu
	$language[61]			=	"I have sent you a <b>video chat</b> request.";							// The message to send when a video chat is reuqested
	$language[62]			=	"Accept";																// Accept a video chat request
	$language[63]			=	"Your video chat request has been sent.";								// Displayed when a user sends a video chat request
	$language[66]			=	"Add Files...";															// The file transfer option in the more menu
	$language[67]			=	"cancel the upload";													// The link to cancel the file transfer
	$language[68]			=	"Your file has been uploaded and sent.";								// Displayed when a user sends a file
	$language[69]			=	"I have sent you a <b>file attachment</b>.";							// The message to send when a file is sent
	$language[70]			=	"Download file";														// Download a file that was sent
	$language[84]			=	"Block User";															// Blocks a user
	$language[85]			=	"Would you also like to report this user?";								// Asks the user if they want to report another user
	$language[86]			=	"Browse";																// The text to browse for a file when uploading
	$language[87]			=	"Hit browse to upload a file or ";										// Text to display when uploading a file
	$language[88]			=	"Start a video call";													// Displays when mouseover the video chat icon
	$language[89]			=	"Close chat";															// Displays when mouserver the close icon
	$language[90]			=	"You";																	// Displays on mosueover of your own chat text
	$language[102]			=	"Message not sent.  This user has blocked you.";						// Displays this when a user tries to send a message to another user who has them blocked
	$language[103]			=	"The user has been blocked. Messages by the user will no longer be received."; // Displays when a user is blocked
	$language[134]			=	"Scroll down to see new messages.";										// Displays when a chat window is not scrolled down on a new message
	$language[135]			=	"There was an error sending your message. Try again later.";			// Error message when a message fails to send
	$language[146]			=	"Video calling is currently unavailable";								// Displays when mouseover the video chat icon and user is offline
	$language[151]			=	"There was an error uploading the file.";								// File upload error message
	$language[209]			=	"Sending messages has been disabled for your user group.";				// Error message when a bad user group tries to send a message
	$language[213]			=	"Aa";																	// Displayed in the input box to message someone
	$language[214]			=	"Search GIFs...";														// Placeholder on the Giphy Input for GIFs
	$language[215]			=	"Minimized Chat";														// The title for the minimized users tab
	$language[230]			=	"Smileys & People";														// Tooltip for smiley emojis
	$language[231]			=	"Animals & Nature";														// Tooltip for animal emojis
	$language[232]			=	"Food & Drink";															// Tooltip for food emojis
	$language[233]			=	"Activities";															// Tooltip for activity emojis
	$language[234]			=	"Travel & Places";														// Tooltip for travel emojis
	$language[235]			=	"Objects";																// Tooltip for object emojis
	$language[236]			=	"Symbols";																// Tooltip for symbol emojis
	$language[237]			=	"Flags";																// Tooltip for flag emojis
	$language[238]			=	"More Emojis";															// Tooltip for more emojis
	$language[298]			=	"Your user group can only respond to recent messages.";					// Displays when a user tries to send a message but is only able to respond
	$language[304]			=	"Minimize chat";														// Tooltip to minimize the chat window
	$language[307]			=	"Choose an emoji";														// Tooltip for smilies
	$language[308]			=	"Open more actions";													// Tooltip to open chat actions (gif, attachments, etc)
	$language[309]			=	"Choose a gif";															// Menu option to choose a gif
	$language[310]			=	"Attach a file";														// Menu option to attach a file
	$language[311]			=	"Press enter to send";													// Tooltip to send a user message

	// ######################### CHAT ROOMS #############################
	$language[19]			=	"Chat Rooms";								// Displayed in the chatrooms popup and tab
	$language[31]			=	"Create";									// Button to create chatroom
	$language[32]			=	"Options";									// Button to show chatroom options
	$language[33]			=	"Leave";									// Button to show leave chatroom
	$language[35]			=	"Users online";								// Tooltip for number of online users
	$language[37]			=	"Add a new chat room";						// Text to display in the create chatroom popup
	$language[36]   		=   "Keep Room Open";							// Option to keep the chatroom window open
	$language[47]			=	"Stay in Room";								// Option to stay in the chatroom without the window open on page load
	$language[38]			=	"Block Private Chats";						// Option to block private chats from users in the chatroom
	$language[39]			=	"You must wait more time before creating another chatroom.";	// Error to show when the chatroom flood limit is reached
	$language[40]			=	"User created chat rooms are currently disabled.";				// Error to show when user chatrooms are disabled
	$language[41]			=	"Private Message";							// Send user a private messages
	$language[42]			=	"Visit Profile";							// Visit the user's profile
	$language[43]			=	"Guest";									// The user's title in the chatroom - shown when the user is a guest
	$language[212]			=	"Member";									// The user's title in the chatroom - shown when the user is a member
	$language[44]			=	"Moderator";								// The user's title in the chatroom - shown when the user is a moderator
	$language[45]			=	"Administrator";							// The user's title in the chatroom - shown when the user is an administrator
	$language[46]			= 	"This user has private messaging disabled"; // The text that the alert box will display when a user trys to PM with blocked chat
	$language[48]			=	"That chat room does not exist.";			// Displayed when a user trys to enter an invalid chatroom
	$language[49]			=	"The password entered was incorrect. Please try again.";		// Displayed when a user enters the wrong password
	$language[50]			=	"Enter the password";						// Text to display when entering the chatroom password
	$language[51]			=	"Limit Reached: You must wait more time before sending another message.";	// Error to show when flood limit is reached
	$language[52]			=	"Make Moderator";							// Make the user a moderator
	$language[54]			=	"Remove Moderator";							// Remove the user from being a moderator
	$language[53]			=	"Kick User";								// Ban/Kick the user from the chatroom
	$language[55]			=	"You are permanently banned from ";			// DISPLAYS CHATROOM NAME AFTER - shown when a user is permanently banned
	$language[56]			=	"You are banned from  ";					// DISPLAYS CHATROOM NAME AFTER - shown when a user is temporarily banned
	$language[219]			=	" for ";									// DISPLAYS CHATROM NAME BEFORE AND MINUTES AFTER - shown when a user is temporarily banned
	$language[220]			=	" minute(s)";									// DISPLAYS MINUTES BEFORE - shown when a user is temporarily banned
	$language[57]			=	"Enter the number in minutes that the user should be banned for.  Enter 0 for a permanent ban.";	// Message to show when banning a user.  Typing 0 will permanently ban the user
	$language[91]			=	"Enter the name of the chat room you'd like to create.";		// Message to display when creating a chat room
	$language[92]			=	"Leave Chat Room";							// Tooltip when mousover the leave chat room icon
	$language[93]			=	"Add new room";								// Tooltip when mouseover the create chat room icon
	$language[94]			=	"Change the chat theme";					// Tooltip when mouseover the change theme icon
	$language[98]			=	"Room Name...";								// Placeholder for the create chatroom name box
	$language[99]			=	"Password...";								// Placeholder for the create chatroom password box
	$language[100]			=	"Join";										// Displayed on UI buttons to join a chat room
	$language[101]			=	"Chat Room Sounds";							// The option to enable/disable chat room sounds
	$language[106]			=	" has been made a moderator by ";			// DISPLAYS USERNAME FIRST/MODERATOR AFTER - Shown after a user is made a moderator
	$language[107]			=	" has been kicked from the chat room by ";	// DISPLAYS USERNAME FIRST/MODERATOR AFTER - Shown after a user is kicked
	$language[117]			=	"Pop Out Chat Room";						// Option to pop out the chat room
	$language[127]			=	"There are too many users in this chat room.  Please try again later.";	// Displayed when a user tries to enter a chat room with too many online.
	$language[136]			=	"fa-solid fa-crown";						// Will display after a username when an administrator
	$language[137]			=	"fa-solid fa-gavel";						// Will display after a username when a moderator
	$language[147]			=	"Online Users";								// Text to display for chat room's user group
	$language[148]			=	"Administrators";							// Text to display for chat room's admin group
	$language[149]			=	"Moderators";								// Text to display for chat room's mod group
	$language[150]			=	"User-created chat room";					// Text for chat rooms that have no description entered
	$language[152]			=	"Always Show Names";						// Option to always show names in a chat room
	$language[153]			=	"Edit Welcome Message...";					// Option to edit the welcome message
	$language[154]			=	"Enter the welcome message that you would like to be displayed when users enter this chat room. Enter a blank value for no welcome message.";	// Prompt for the mod/admin to edit the welcome message
	$language[155]			=	"The settings have been successfully saved.";	// Notice when an admin or mod saves settings
	$language[156]			=	" has had their moderator status taken away by ";	// DISPLAYS USERNAME FIRST/MODERATOR AFTER - Shown after a user is made a moderator
	$language[157]			=	"Edit Description...";						// Option to edit the description
	$language[158]			=	"Enter the description that you would like to be displayed in the chat room list.";	// Prompt for the mod/admin to edit the description
	$language[159]			=	"Message deleted by ";						// DISPLAYS USERNAME AFTER - Shown when a chat room message is deleted in replacement of the message
	$language[160]			=	"Delete Message";							// Tooltip to show on the delete message icon
	$language[161]			=	"Silence User";								// Option to silence a user
	$language[162]			=	"Enter the number in seconds that the user should be silenced for.  Maximum time is 300 seconds.";	// Message to show when silencing a user
	$language[163]			=	" has been silenced by ";					// DISPLAYS USERNAME FIRST/MODERATOR AFTER - Shown after a user is silenced
	$language[164]			=	"You are silenced for another ";			// DISPLAYS SECONDS AFTER - The first half of the user is silenced error message.
	$language[165]			=	" seconds.";								// DISPLAYS SECONDS BEFORE - The second half of the user is silenced error message.
	$language[169]			=	"Slow mode is enabled. Please wait ";		// DISPLAYS SECONDS AFTER - The first half of the flood message
	$language[170]			=	" more seconds to chat.";					// DISPLAYS SECONDS BEFORE - The second half of the flood message
	$language[171]			=	"Edit Chat Flood...";						//	Option to change the chat room flood limits
	$language[172]			=	"Set room flood settings";					// Text to display in the flood menu
	$language[173]			=	"Save";										// Button text for the flood menu save
	$language[174]			=	"message(s) every";							// In between the flood message and time
	$language[175]			=	"second(s)";								// After the flood time
	$language[210]			=	"Your user group is not permitted to enter this room.";	// Error displayed when a disallowed user group enters the chat room
	$language[217]			=	"Join another chat room";					// Tooltip for the button to add another chat room
	$language[218]			=	"You may only be in 3 chat rooms at once.";	// Error message to display when trying to enter a 4th chat room
	$language[223]			=	"Options +";								// Options link for user created chat rooms
	$language[224]			=	"Password";									// Label for the password on user created chat rooms
	$language[225]			=	"Description";								// Label for the description on user created chat rooms
	$language[226]			=	"Welcome Message";							// Label for the welcome message on user created chat rooms
	$language[227]			=	"Recommended";								// Text for featured chat rooms
	$language[228]			=	"More Chat Rooms";							// Text for other chat rooms
	$language[312]			=	"View room user list";						// Tooltip for chat room user list
	$language[313]			=	"Reply";									// Tooltip to reply to chat room user
	$language[318]    		=   "<i class='fas fa-users-slash'></i>No chat rooms are available."; 	// Displayed with no chat rooms are created
	$language[319]			=	"Invisible in User List";					// Option to enable invisible mode in chat room
	
	// ########################## MODERATORS #############################
	$language[166]			=	"Moderation";								// Displayed in the moderators popup and tab
	$language[167]			=	"Report Spam/Abuse";						// Option in 1-on-1 chat settings to report a person
	$language[168]			=	"Thank you for your report.";				// Message after a report is filed
	$language[177]			=	"Report From";								// Header for the reports from column
	$language[178]			=	"Report About";								// Header for the reports about column
	$language[179]			=	"Report Time";								// Header for the reports time column
	$language[180]			=	"Total Reports";							// DISPLAYS # OF REPORTS FIRST - Used in the title to show total number of reports
	$language[181]			=	"Reports On User";							// The header for the reports list
	$language[182]			=	"Someone else is already working on this report.";	// Error message when a report is already being worked on
	$language[183]			=	"Ban User";									// Option to ban the user
	$language[184]			=	"Warn User";								// Option to warn the user
	$language[185]			=	"Close Report";								// Option to close the report
	$language[186]			=	"Back to Lobby";							// Option to go back to the reports list
	$language[187]			=	"Report #";									// DISPLAYS REPORT NUMBER AFTER - shown in the reports on user list
	$language[188]			=	"No additional reports";				 	// Displays when no other reports are available
	$language[189]			=	"There are no reports, hooray!";			// Displays in the lobby when there are no reports
	$language[190]			=	"About: ";									// DISPLAYS ABOUT USER AFTER - The pretext for the user the report is about
	$language[191]			=	"From: ";									// DISPLAYS FROM USER AFTER - The pretext for the user the report is from
	$language[192]			=	"Previous Warnings: ";						// DISPLAYS WARNINGS AFTER - The pretext for the number of previous warnings
	$language[193]			=	"Time: ";									// DISPLAYS TIME AFTER - The pretext for the time of the report
	$language[194]			=	"The user was reported here";				// Displays in the report history where the user was reported
	$language[195]			=	"Are you sure you want to PERMANENTLY ban this user?  The user will have to be unbanned from the ArrowChat admin panel.";	// Prompt when the ban user option is clicked
	$language[196]			=	"Enter a reason for the warning.  THIS WILL BE SHOWN TO THE WARNED USER.";	// Prompt when after to enter a warning reason
	$language[197]			=	"This user has been warned in the past 24 hours.  Are you sure you want to warn again?";	// Prompt when the user has been warning in the past 24 hours
	$language[198]			=	"I Understand";								// The text that closes a warning notification
	$language[199]			=	"You have been warned by a moderator.";		// Displays to user when they are warned by a moderator
	
	// ######################### MOBILE CHAT #############################
	$language[116]			=	"You must login before using mobile chat";	// Text to display when user is not logged in using mobile
	$language[145]			=	"Mobile Chat";			// The text to appear in the website title
	$language[208]			=	"Aa";					// Text placeholder for the message inputs
	$language[216]			=	"Mobile";				// Status displayed when a user is on a mobile device
	$language[239]			=	"Login";				// Text to display on a login button
	$language[245]			=	"No recent chat. Say hello!";	// Default message when user has not chatted
	$language[246]			=	"*Attachment Sent*";	// Notification when attachment is received
	$language[260]			=	"Chats";				// Title for chat tab
	$language[261]			=	"Online";				// Title for online tab
	$language[262]			=	"Chat Rooms";			// Title for chat rooms tab
	$language[126]			=	"Home";					// Displays as link to return to the main website
	$language[263]			=	"Mod Panel";			// Displays as link to admin panel for admins only
	$language[264]			=	"Settings";				// Displays as link to open up mobile chat settings
	$language[138]			=	"Enter the password";	// Palceholder for the chat room password input
	$language[265]			=	"Enter";				// Button to enter chat room for password locked room
	$language[266]			=	"Cancel";				// Button to cancel entering password locked room
	$language[211]			=	"Hide Mobile Chat Tab";	// Setting to hide the mobile chat tab on website
	$language[267]			=	"Chat Sounds";			// Setting to enable chat sounds
	$language[268]			=	"Disable Avatars";		// Setting to disable avatars
	$language[269]			=	"Unblock Users";		// Setting to unblock users
	$language[270]			=	"Done";					// Button to save settings and close, default: Done
	$language[271]			=	"Search";				// Placeholder for the search user input
	$language[272]			=	"Enter a room name";	// Placeholder for the name on create chat room prompt
	$language[273]			=	"Password (optional)";	// Placeholder for the password on create chat room prompt
	$language[274]			=	"Create";				// Button to create a chat room
	$language[275]			=	"Cancel";				// Button to cancel creating a chat room
	$language[276]			=	"Edit Name";			// Link to edit a user's guest name
	$language[277]			=	"Edit Description";		// Link to edit a chat room's description
	$language[278]			=	"Edit Welcome Message";	// Link to edit a chat room's welcome message
	$language[279]			=	"Block Private Chat";	// Setting to block private chat in chat rooms
	$language[280]			=	"Chat Room Sounds";		// Setting to enable chat room sounds
	$language[281]			=	"Leave Chat Room";		// Button to leave a chat room
	$language[282]			=	"OK";					// OK button on prompts
	$language[283]			=	"No recent conversations"; // Message to display when no chats are open
	$language[284]			=	"Start Chatting";		// Button to take user to online list when no chats are open
	$language[285]			=	"No online users"; 		// Message to display when no users are online
	$language[286]			=	"Search users";			// Button to take user to search list when no users are online
	$language[287]			=	"Enter the new chat room description"; // Message to enter new chat room description
	$language[288]			=	"Enter the new chat room welcome message"; // Message to enter new chat room welcome message
	$language[289]			=	"Enter your new name";	// Message to enter a new guest name
	$language[290]			=	"Name Change Failed: ";	// Error message when the name change fails, error displayed after
	$language[291]			=	"Your username has been changed"; // Success message when user changes guest name
	$language[292]			=	"The user has been unblocked"; // Success message when a user is unblocked
	$language[293]			=	"Minimize Chat";		// Text on the bottom of the in-page chat to minimize the window
	$language[294]			=	"Joined Chat Rooms";	// Text to show joined chat rooms
	$language[297]			=	"Copy";					// Text to show to copy messages
	
	// ######################### VIDEO CHAT #############################
	$language[200]			=	"Video Chat";					// The title for the video chat page
	$language[201]			=	"Invite to Video Chat";			// The title for the invite user to chat page
	$language[202]			=	"Invite someone else to video chat with you by sending them this link.";	// Directions to invite a user to video
	$language[203]			=	"The video chat has ended.";	// Tokbox/Agora - Displays when the session ends
	$language[204]			=	"End Call";						// Tokbox/Agora - Text to end the call
	$language[205]			=	"Invite";						// Tokbox/Agora - Text to invite a user
	$language[206]			=	"Turn On Video";				// Tokbox/Agora - Text to turn on video
	$language[207]			=	"Turn Off Video";				// Tokbox/Agora - Text to turn off video
	$language[247]			=	"Screen Share";					// Tokbox/Agora - Text to screen share
	$language[248]			=	"Turn Off Screen Share";		// Tokbox/Agora - Text to turn off screen share
	$language[249]			=	"Hide Small Windows";			// Tokbox/Agora - Text to hide small video chat windows
	$language[250]			=	"Show Small Windows";			// Tokbox/Agora - Text to show small video chat windows
	$language[251]			=	"Invite Participants";			// Tokbox/Agora - Title for the invite user button
	$language[252]			=	"Copied to Clipboard!";			// Tokbox/Agora - Text to indicate copied to clipboard
	$language[253]			=	"Unmute Microphone";			// Tokbox/Agora - Text to unmute microphone
	$language[254]			=	"Mute Microphone";				// Tokbox/Agora - Text to mute microphone
	$language[255]			=	"Show Options";					// Tokbox/Agora - Text to show options
	$language[256]			=	"Hide Options";					// Tokbox/Agora - Text to hide options
	$language[257]			=	"Viewers";						// Tokbox/Agora - Text to indicate number of viewers
	$language[258]			=	"Failed";						// Tokbox/Agora - Title for error notification
	$language[259]			=	"We could not retrieve your camera or microphone. Please make sure the browser has permission."; // Tokbox/Agora - Text to indicate camera/mic failed

?>