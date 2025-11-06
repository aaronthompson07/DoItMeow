(function (a) {
    a.arrowchat = function () {
	
		var $chatroom_tab = {};
		
		function processMessage(text) {
			if (text.substring(0,4) == "<div" || text.substring(0,5) == "<span") {
				text = lang[246];
			}

			var map = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;'
			};

			return text.replace(/[&<>"']/g, function(m) { return map[m]; });
		}
		function showToast(language, time) {
			ons.notification.toast(language, {
				timeout: time,
				animation: 'fall',
				force: true
			});
			a('.toast').one('swipe', function() {
				var height = a(this).outerHeight();
				a(this).animate({'top': '-'+height+'px'}, 300, function() {
					a('ons-toast').hide();
					a('ons-toast').remove();
				});
			});
			a('.toast').one('click', function() {
				var height = a(this).outerHeight();
				a(this).animate({'top': '-'+height+'px'}, 300, function() {
					a('ons-toast').hide();
					a('ons-toast').remove();
				});
			});
		}
        function loadBuddyList() {
			clearTimeout(Z);
            a.ajax({
                url: c_ac_path + "includes/json/receive/receive_buddylist.php?mobile=1&_="+new Date().getTime(),
                cache: false,
                type: "post",
                dataType: "json",
                success: function (b) {
                    buildBuddyList(b);
                }
            });
			if (typeof c_list_heart_beat != "undefined") {
				var BLHT = c_list_heart_beat * 1000;
			} else {
				var BLHT = 60000;
			}
            Z = setTimeout(function () {
                loadBuddyList()
            }, BLHT)
        }
        function Sa(b) {
            var c = b.substr(19);
            I(c, uc_name[c], uc_status[c], uc_avatar[c], uc_link[c], uc_message[c], 1)
        }
		function Satwo(b) {
            var c = b.substr(26);
            I(c, uc_name[c], uc_status[c], uc_avatar[c], uc_link[c], uc_message[c], 1)
        }
        function buildBuddyList(b) {
            var c = {},
                d = "";
            c.available = "";
            c.busy = "";
            c.offline = "";
            c.away = "";
            onlineNumber = buddylistreceived = 0;
            b && a.each(b, function (i, e) {
                if (i == "buddylist") {
                    buddylistreceived = 1;
                    totalFriendsNumber = onlineNumber = 0;
                    a.each(e, function (l, f) {
						status_icon = "";
						status_color = "#42b72a";
                        longname = renderHTMLString(f.n);
                        if (f.s == "available" || f.s == "away" || f.s == "busy") onlineNumber++;
                        totalFriendsNumber++;
						if (f.s == "available" || f.s == "offline") {
							status_icon = "circle";
							if (f.s == "offline")
								status_color = "#cecece";
						} else if (f.s == "busy") {
							status_icon = "mobile-alt";
						} else {
							status_icon = "moon";
							status_color = "#5485e8";
						}
						var noAvatarColor = getRandomColor(longname);
                        c[f.s] += '<ons-list-item id="arrowchat_userlist_' + f.id + '" class="user-window"><div class="left" style="position:relative;"><div class="buddylist-avatar-wrapper arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter">' + noAvatarColor["character"] + '</span><img class="list-item__thumbnail" src="'+f.a+'" /></div><div class="online-list-icon"><ons-icon size="12px" style="color: '+status_color+'" icon="fa-'+status_icon+'"></ons-icon></div></div><div class="center">' +longname + '</div><div class="right"><span class="notification"></span></div></ons-list-item>';
                        uc_status[f.id] = f.s;
                        uc_name[f.id] = f.n;
                        uc_avatar[f.id] = f.a;
                        uc_link[f.id] = f.l;
						if (typeof uc_message[f.id] == "undefined")
							uc_message[f.id] = lang[245];

						if (a("#arrowchat_user_" + f.id).length > 0) {
							var status = f.s;
							if (status == "available")
								status = lang[1];
							if (status == "away")
								status = lang[240];
							if (status == "offline")
								status = lang[241];
							if (status == "busy")
								status = lang[216];
								
							var noAvatarColor = getRandomColor(uc_name[f.id]);
							a("#username-header").html('<div class="avatar-header arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img alt="" src="' + uc_avatar[f.id] + '"></div><div class="username-header-text"><div id="name-header">' + uc_name[f.id] + '</div><div id="status-header">' + status + '</div></div>');
							if (c_video_chat == 1 && f.s != "offline" && (c_video_select == 2 || c_video_select == 3))
								a("#start-video-chat").show();
							else
								a("#start-video-chat").hide();
						}
						if (a("#arrowchat_userlist_recent_" + f.id).length > 0) {
							a("#arrowchat_userlist_recent_" + f.id + " ons-icon").css('color', status_color);
							a("#arrowchat_userlist_recent_" + f.id + " ons-icon").prop('class', 'ons-icon fa fa-'+status_icon);
						}
                    })
                }
                if (buddylistreceived == 1) {
					a("#buddylist-container-available").html('');
					for (buddystatus in c) {
						if (c.hasOwnProperty(buddystatus)) {
							if (c[buddystatus] != "") {
								a("#buddylist-container-available").html(c['available'] + c['busy'] + c['away']);
							}
						}
					}
					setAvatarVisibility();
					updateChatTabTotal();
					a(".user-window").unbind("click");
                    a(".user-window").click(function (l) {
                        Sa(a(this).attr("id"));
						var c = a(this).attr("id").substr(19);
                    });
                    R = onlineNumber;
					a("#online-users-tab .tabbar__button .tabbar__badge").remove();
					a("#online-users-tab .tabbar__button").append('<div class="tabbar__badge notification">'+onlineNumber+'</div>');
					a('#no-online-users').remove();
                    if (onlineNumber == 0) {
						a("#buddylist-container-available").parent().append('<p id="no-online-users">'+lang[285]+'<br /><br /><ons-button id="search-users-button">'+lang[286]+'</ons-button></p>');
						a('#search-users-button').unbind('click');
						a('#search-users-button').click(function() {
							a('#add-icon').click();
						});
					}
                    buddylistreceived = 0
                }
            })
        }
		function cancelJSONP() {
			if (typeof CHA != "undefined") {
				clearTimeout(CHA);
			}
			if (typeof xOptions != "undefined") {
				xOptions.abort();
			}
		}
		function loadChatroomList() {
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_chatroom_list.php?_="+new Date().getTime(),
				cache: false,
				type: "post",
				dataType: "json",
				success: function (b) {
					buildChatroomList(b);
				}
			});
		}
		function buildChatroomList(b) {
			a("#buddylist-container-chatroom").html("");
			var c = {},
			code = "",
			featured_list = "",
			other_list = "";
			b && a.each(b, function (i, e) {
				if (i == "chatrooms") {
					a.each(e, function (l, f) {
						if (f.t == 2) {
							password_class = 'password-protected';
						} else {
							password_class = 'chatroom-window';
						}

						code = '<ons-list-item class="'+password_class+'" modifier="nodivider" id="arrowchat_chatroom_' + f.id + '"><div class="left" style="position:relative"><img class="list-item__thumbnail" src="' + c_ac_path + "themes/" + u_theme + '/images/icons/' + f.img + '" /><div class="online-list-icon"><div class="chatroom_count tabbar__badge notification">'+f.c+'</div></div></div><div class="center"><span class="list-item__title">' +f.n + '</span><span class="list-item__subtitle">' + f.d + '</span></div><div class="right chatroom_list_right"><span class="notification"></span></div></ons-list-item>';
						if (f.o == 1) {
							other_list += code;
						} else {
							featured_list += code;
						}
						cr_type[f.id] = f.t;
						cr_name[f.id] = f.n;
						cr_desc[f.id] = f.d;
						cr_img[f.id] = f.img;
						cr_count[f.id] = f.c;
						cr_other[f.id] = f.o;
						
						if (a("#arrowchat_chatroom_joined_"+f.id).length > 0) {
							a("#arrowchat_chatroom_joined_"+f.id+" .chatroom_count").html(f.c);
							a("#arrowchat_chatroom_joined_"+f.id+" .list-item__title").html(f.n);
							a("#arrowchat_chatroom_joined_"+f.id+" .list-item__subtitle").html(f.d);
						}
					});
				}
			});
			if (featured_list != "") {
				a('<ons-list-header>' + lang[227] + '</ons-list-header>').appendTo(a("#buddylist-container-chatroom"));
				a(featured_list).appendTo(a("#buddylist-container-chatroom"));
			}
			if (other_list != "") {
				a('<ons-list-header>' + lang[228] + '</ons-list-header>').appendTo(a("#buddylist-container-chatroom"));
				a(other_list).appendTo(a("#buddylist-container-chatroom"));
			}
			chatroomreceived = 1;
			a(".chatroom-window").unbind('click');
			a(".chatroom-window").click(function (l) {
				var id = a(this).attr('id').substr(19);
				chatroomListClicked(a(this).attr('id'));
				loadChatroom(Ccr, cr_type[Ccr]);
			});
			a(".password-protected").unbind('click');
			a(".password-protected").click(function (l) {
				var id = a(this).attr('id').substr(19);
				chatroomListClicked(a(this).attr('id'));
				fn.passwordDialog();
			});
			clearTimeout(crtimeout);
			crtimeout = setTimeout(function () {
				loadChatroomList()
			}, 6E4);
		}
		function chatroomListClicked(b) {
			var c = b.substr(19);
			a("#arrowchat_chatroom_joined_" + c + " .list-item__title").removeClass('new-message-bold');
			a("#arrowchat_chatroom_joined_" + c + " .list-item__subtitle").removeClass('new-message-bold');
			Ccr = c;
			Ccr2 = c;
		}
		function changePushChannel(id, connect) {
			if (connect == 1) {
				if (c_push_engine == 1) {
					push_room[id] = push.subscribe(c_push_encrypt + "_chatroom" + id);
					push_room[id].on('data', function (data) {
						pushReceive(data);
					});
				}
				chatroom_list[id] = id;
			} else {
				if (c_push_engine == 1) {
					push_room[id].unsubscribe();
				}
				if (typeof(chatroom_list[id]) != "undefined") {
					delete chatroom_list[id];
				}
			}
		}
		function loadChatroom(b, c, pass) {
			a('#myNavigator')[0].pushPage('chatroom-chat-splitter', {data: {title: cr_name[b]}, callback:function(){}});
			document.querySelector('#myNavigator').addEventListener('postpush', function myListener(e) {
				message_chatroom_count[b] = 0;
				updateChatTabTotal();
				hideSmileyButton();
				hideGiphyButton();
				a('.arrowchat_chatroom_chat_content').html('<div id="recent-loader" class="loader-div"><ons-progress-circular indeterminate></ons-progress-circular></div>');
				retain_ccr = b;
				var global_mod = 0,
					global_admin = 0,
					admin_markup = "",
					msg_history = "",
					message = "";
				chatroom_mod[b] = 0;
				chatroom_admin[b] = 0;
				chatroomreceived = 1;
				a.ajax({
					url: c_ac_path + "includes/json/receive/receive_chatroom_room.php?_="+new Date().getTime(),
					data: {
						chatroomid: b,
						chatroom_pw: pass
					},
					type: "post",
					cache: false,
					dataType: "json",
					success: function (o) {
						a("#recent-loader").remove();
						if (o) {
							var no_error = true;
							var error_received = 0;
							o && a.each(o, function (i, e) {
								if (i == "error") {
									a.each(e, function (l, f) {
										no_error = false;
										Ccr = 0;
										retain_ccr = 0;
										chatroomreceived = 0;
										joinedChangePassword = 1;
										if (error_received  == 0) {
											a('#chatroom-chat-page .back-button').click();
											showToast(f.m, 5000);
										}
										error_received = 1;
									});
								}
							});
							if (no_error) {
								if (typeof(init_open_room[b]) == "undefined")
									init_open_room[b] = 1;
								if (init_open_room[b] == 1) {
									a.post(c_ac_path + "includes/json/send/send_settings.php", {
										focus_chat: 'r'+b
									}, function () {});
								}
								addChatroomJoined(b, false);
								var resort = a("#arrowchat_chatroom_joined_" + b).clone(true);
								a("#arrowchat_chatroom_joined_" + b).remove();
								a("#buddylist-container-recent").prepend(resort);
								init_open_room[b] = 1;
								if (Crref2 == -1) {
									Crref2 = setTimeout(function () {
										receiveChatroom()
									}, 30000);
								}
								if (c_push_engine != 1) {
									cancelJSONP();
									changePushChannel(b, 1);
									receiveCore();
								} else {
									changePushChannel(b, 1);
								}
								if (c_chatroom_transfer == 1) {uploadProcessing(1);}else{a("#arrowchat_upload_button2").hide()}
								if (c_giphy_chatroom != 1) {initGiphy(1);}else{a(".arrowchat_giphy_button2").hide()}
								if (c_disable_smilies != 1) {initEmoji(1);}else{a(".arrowchat_smiley_button2").hide();a(".arrowchat_smiley_button").hide()}
								a("#chatroom-header").html('<div class="avatar-header arrowchat_white_background"><img alt="" src="' + c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[b] + '" /><div class="online-list-icon online-list-icon-small"><div class="chatroom_count tabbar__badge notification">' + cr_count[b] + '</div></div></div><div class="username-header-text"><div id="name-header">' + cr_name[b] + '</div><div id="status-header">' + cr_desc[b] + '</div></div>');
								a("#textinput2").keydown(function (h) {
									return chatroomKeydown(h, a(this), Ccr)
								});
								a("#textinput2").keyup(function (h) {
									return chatroomKeyup(h, a(this), Ccr)
								});
								a(".back-count-wrapper").unbind('click');
								a(".back-count-wrapper").click(function() {
									a("#chatroom-chat-page ons-back-button").click();
								});
								a("#send_button_chatroom").unbind('click');
								a("#send_button_chatroom").click(function () {
									hideSmileyButton();
									scrollOnPage3();
									sendChatroomMessage(a("#textinput2"), Ccr);
								});
								o && a.each(o, function (i, e) {
									if (i == "user_title") {
										a.each(e, function (l, f) {
											if (f.admin == 1) {
												global_admin = 1;
												chatroom_admin[b] = 1;
											}
											if (f.mod == 1) {
												global_mod = 1;
												chatroom_mod[b] = 1;
											}
										});
									}
									if (i == "chat_name") {
										a.each(e, function (l, f) {
											cr_name[b] = f.n;
											a("#chatroom-header #name-header").html(cr_name[b]);
											a("#arrowchat_chatroom_joined_" + b + " .center .list-item__title").html(cr_name[b]);
										});
									}
									if (i == "chat_users") {
										var longname,userCount=0;
										a("#chatroom-users-list").html('');
										a.each(e, function (l, f) {
											if (f.status != 'invisible') {
												userCount++;
												if ((global_admin == 1 || global_mod == 1) && (f.t == 1 || f.t == 4)) {
													admin_markup = '<div class="arrowchat_chatroom_options_padding"><div id="arrowchat_chatroom_make_mod_' + f.id + '" class="arrowchat_chatroom_flyout_text">' + lang[52] + '</div></div><div class="arrowchat_chatroom_options_padding"><div id="arrowchat_chatroom_silence_user_' + f.id + '" class="arrowchat_chatroom_flyout_text">' + lang[161] + '</div></div><div class="arrowchat_chatroom_options_padding"><div id="arrowchat_chatroom_ban_user_' + f.id + '" class="arrowchat_chatroom_flyout_text">' + lang[53] + '</div></div>';
												}
												if (global_admin == 1 && f.t == 2) {
													admin_markup = '<div class="arrowchat_chatroom_options_padding"><div id="arrowchat_chatroom_remove_mod_' + f.id + '" class="arrowchat_chatroom_flyout_text">' + lang[54] + '</div></div>';
												}
												longname = renderHTMLString(f.n);
												usertitle = lang[43];
												f.n = renderHTMLString(f.n);
												if (f.t == 2) {
													usertitle = lang[44];
												} else if (f.t == 3) {
													usertitle = lang[45];
												} else if (f.t == 4) {
													usertitle = lang[212];
												}
												var noAvatarColor = getRandomColor(longname);
												a("#chatroom-users-list").append('<ons-list-item class="arrowchat_chatroom_admin_' + f.t + '" id="arrowchat_chatroom_user_' + f.id + '"><div class="left list-item__left" style="position:relative;"><div class="buddylist-avatar-wrapper buddylist-avatar-wrapper-xsmall arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img class="list-item__thumbnail" src="'+f.a+'" /></div></div><div class="center list-item__center"><span class="list-item__title">' +f.n + '</span><span class="list-item__subtitle">'+usertitle+'</span></div></ons-list-item>');
											}
											uc_status[f.id] = f.status;
											uc_name[f.id] = f.n;
											uc_avatar[f.id] = f.a;
											uc_link[f.id] = f.l;
											if (f.id != u_id) {
												setUserOptions(f, global_admin, global_mod);
											}
										});
										a("#chatroom-header .chatroom_count").html(userCount);
										var sort_by_name = function(a, b) {
											return a.querySelector('.list-item__title').innerHTML.toLowerCase().localeCompare(b.querySelector('.list-item__title').innerHTML.toLowerCase());
										};
										var list = a("#chatroom-users-list > ons-list-item").get();
										list.sort(sort_by_name);
										for (var i = 0; i < list.length; i++) {
											list[i].parentNode.appendChild(list[i]);
										}
										setAvatarVisibility();
										a("#user-options").bind("pagehide",function(){
											scrollOnPage3();
										});
									}
									if (i == "chat_history") {
										msg_history = "";
										a.each(e, function (l, f) {
											var regex = new RegExp('(^|\\s)(@' + u_name + ')(\\s|$)', 'i');
											f.m = f.m.replace(regex, '$1<span class="arrowchat_at_user">$2</span>$3');
											if (typeof(blockList[f.userid]) == "undefined") {
												var title = "", important = "";
												if (f.mod == 1) {
													title = lang[137];
													important = " arrowchat_chatroom_important";
												}
												if (f.admin == 1) {
													title = lang[136];
													important = " arrowchat_chatroom_important";
												}
												l = "";
												var image_msg = "";
												fromname = f.n;
												var pending_icon = "";
												if (f.n == u_name) {
													l = " arrowchat_self";
													pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
												}
												if (f.m.substr(0, 4) == "<div") {
													image_msg = " arrowchat_image_msg";
												}
												var sent_time = new Date(f.t * 1E3);
												if (f.global == 1) {
													msg_history += '<div class="arrowchat_message_wrapper arrowchat_clearfix"><div class="arrowchat_chatroom_box_message arrowchat_chatroom_global" id="arrowchat_chatroom_message_' + f.id + '"><div class="arrowchat_chatroom_message_content arrowchat_global_chatroom_message">' + f.m + "</div></div></div>"
												} else {
													var noAvatarColor = getRandomColor(fromname);
													msg_history += '<div class="arrowchat_message_wrapper' + l + image_msg + important + '" id="arrowchat_chatroom_message_' + f.id + '"><div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + fromname + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_sender_avatar arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_small">' + noAvatarColor["character"] + '</span><img alt="' + fromname + '" src="' + f.a + '" /></div><div class="arrowchat_chatroom_box_message"><div class="arrowchat_chatroom_message_content"><span class="arrowchat_chatroom_msg">' + f.m + '</span></div></div></div>' + pending_icon + '</div>';
												}
											}
										});
									}
									if (i == "room_info") {
										a.each(e, function (l, f) {
											if (f.welcome_msg != "") {
												message = stripslashes(f.welcome_msg);
												cr_welcome[b] = message;
												message = replaceURLWithHTMLLinks(message);
											}
											cr_desc[b] = f.desc;
											cr_img[b] = f.image;
											room_limit_msg[b] = f.limit_msg;
											room_limit_sec[b] = f.limit_sec;
											
											a("#chatroom-header .avatar-header img").prop('src', c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[b]);
											a("#chatroom-header #status-header").html(cr_desc[b]);
											a("#arrowchat_chatroom_joined_" + b + " .left img").prop('src', c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[b]);
											a("#arrowchat_chatroom_joined_" + b + " .center .list-item__subtitle").html(cr_desc[b]);
										});
									}
								});
								a(".arrowchat_chatroom_chat_content").prepend(msg_history);
								showTimeAndTooltip();
								a(".arrowchat_chatroom_chat_content").append('<div class="arrowchat_message_wrapper arrowchat_clearfix"><div class="arrowchat_chatroom_box_message arrowchat_chatroom_global" id="arrowchat_chatroom_welcome_msg"><div class="arrowchat_chatroom_message_content arrowchat_global_chatroom_message">' + message + "</div></div></div>");
								a(".arrowchat_pending_icon:not(.arrowchat_pending_error)", "#chatroom-chat-page").hide();
								a("div .arrowchat_pending_icon:last", "#chatroom-chat-page").show();
								scrollOnPage3();
								a(".arrowchat_chatroom_msg>div>img,.arrowchat_emoji_text>img").one("load", function() {
									a('#page3').bind('pageshow',function(){scrollOnPage3();});
									scrollOnPage3();
								}).each(function() {
									if(this.complete) a(this).trigger('load');
								});
								setAvatarVisibility();
							}
						}
					}
				});
				a("#chatroom-chat-page ons-back-button").unbind("click");
				a("#chatroom-chat-page ons-back-button").click(function () {
					retain_ccr = Ccr;
					Ccr = 0;
					emoji_loaded_chatroom = 0;
					emoji_loaded = 0;
				});
				a("#arrowchat_chatroom_joined_" + b + " .list-item__title").removeClass('new-message-bold');
				a("#arrowchat_chatroom_joined_" + b + " .list-item__subtitle").removeClass('new-message-bold');
				document.querySelector('#myNavigator').removeEventListener('postpush', myListener);
			});
		}
		function setUserOptions(f, global_admin, global_mod) {
			var button = [];
			var indexNumber = 0, 
				profileIndex = -1,
				messageIndex = -1,
				moderationIndex = -1,
				blockIndex = -1,
				modIndex = -1,
				silenceIndex = -1,
				kickIndex = -1,
				removeModIndex = -1;
			
			if (f.l != "" && f.l != "#") {
				button.push({label:lang[42],icon:'fa-external-link-alt'});
				profileIndex = indexNumber;
				indexNumber++;
			}
			if (f.b != 1 || global_admin == 1) {
				button.push(lang[41]);
				messageIndex = indexNumber;
				indexNumber++;
			}
			if (c_enable_moderation == 1) {
				button.push(lang[167]);
				moderationIndex = indexNumber;
				indexNumber++;
			}
			button.push({label:lang[84],modifier:'destructive'});
			blockIndex = indexNumber;
			indexNumber++;
			if ((global_admin == 1 || global_mod == 1) && (f.t == 1 || f.t == 4)) {
				button.push({label:lang[52],modifier:'mod'});
				modIndex = indexNumber;
				indexNumber++;
				
				button.push({label:lang[161],modifier:'mod'});
				silenceIndex = indexNumber;
				indexNumber++;
				
				button.push({label:lang[53],modifier:'mod'});
				kickIndex = indexNumber;
				indexNumber++;
			}
			if (global_admin == 1 && f.t == 2) {
				button.push({label:lang[54],modifier:'mod'});
				removeModIndex = indexNumber;
				indexNumber++;
			}
			button.push(lang[222]);

			a('#arrowchat_chatroom_user_' + f.id).unbind("click");
			a('#arrowchat_chatroom_user_' + f.id).click(function() {
				ons.openActionSheet({
				  cancelable: true,
				  maskColor: 'rgba(0, 0, 0, 0.6)',
				  buttons: button
				}).then(function (index) {
					if (index == profileIndex && profileIndex != -1) {
						window.top.location.href= uc_link[f.id];
					}
					if (index == messageIndex && messageIndex != -1) {
						I(f.id, uc_name[f.id], uc_status[f.id], uc_avatar[f.id], uc_link[f.id], uc_message[f.id], 1, 0, 0)
					}
					if (index == moderationIndex && moderationIndex != -1) {
						showToast(lang[168], 3000);
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							report_from: u_id,
							report_about: f.id
						}, function () {});
					}
					if (index == blockIndex && blockIndex != -1) {
						a.ajax({
							url: c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(),
							type: 'POST',
							data: {
								block_chat: f.id
							},
							success: function() {
								showToast(lang[103], 3000);
								if (typeof(blockList[f.id]) == "undefined") {
									blockList[f.id] = f.id;
								}
								loadBuddyList();
								a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
									close_chat: f.id,
									tab_alert: 1
								}, function () {});
								if (a("#arrowchat_userlist_recent_" + f.id).length != 0)
									a("#arrowchat_userlist_recent_" + f.id).remove();
							}
						});
					}
					if (index == modIndex && modIndex != -1) {
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							chatroom_mod: f.id,
							chatroom_id: Ccr
						}, function () {receiveChatroom();});
					}
					if (index == silenceIndex && silenceIndex != -1) {
						ons.notification.prompt(lang[162], {buttonLabels:lang[282]}).then(function(input) {
							if (input != null && input != "" && !(isNaN(input))) {
								a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
									chatroom_silence: f.id,
									chatroom_id: Ccr,
									chatroom_silence_length: input
								}, function () {});
							}
						});
					}
					if (index == kickIndex && kickIndex != -1) {
						ons.notification.prompt(lang[57], {buttonLabels:lang[282]}).then(function(input) {
							if (input != null && input != "" && !(isNaN(input))) {
								a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
									chatroom_ban: f.id,
									chatroom_id: Ccr,
									chatroom_ban_length: input
								}, function () {receiveChatroom();});
							}
						});
					}
					if (index == removeModIndex && removeModIndex != -1) {
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							chatroom_remove_mod: f.id,
							chatroom_id: Ccr
						}, function () {receiveChatroom();});
					}
				});
			});
		}
		function setPrivateUserOptions(b) {
			var button = [];
			if (c_enable_moderation == 1) {
				button = [{label:lang[42],icon:'fa-external-link-alt'}, lang[24], lang[167], {label:lang[84],modifier:'destructive'}, lang[222]];
			} else {
				button = [{label:lang[42],icon:'fa-external-link-alt'}, lang[24], {label:lang[84],modifier:'destructive'}, lang[222]];
			}
			a("#show-private-actions").unbind("click");
			a("#show-private-actions").click(function() {
				ons.openActionSheet({
				  cancelable: true,
				  maskColor: 'rgba(0, 0, 0, 0.6)',
				  buttons: button
				}).then(function (index) {
					if (index == 0) {
						window.top.location.href= uc_link[b];
					}
					if (index == 1) {
						a("#arrowchat_user_" + b).html('');
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							clear_chat: b
						}, function () {});
					}
					if (index == 2 && c_enable_moderation == 1) {
						showToast(lang[168], 3000);
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							report_from: u_id,
							report_about: b
						}, function () {});
					}
					if ((index == 2 && c_enable_moderation == 0) || (index == 3 && c_enable_moderation == 1)) {
						a.ajax({
							url: c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(),
							type: 'POST',
							data: {
								block_chat: b
							},
							success: function() {
								showToast(lang[103], 3000);
								if (typeof(blockList[b]) == "undefined") {
									blockList[b] = b;
								}
								loadBuddyList();
								a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
									close_chat: b,
									tab_alert: 1
								}, function () {});
								a("#arrowchat_userlist_recent_" + b).remove();
								a("#private-chat-page ons-back-button").click();
							}
						});
					}
				});
			});
		}
		function addMessageToChatroom(b, c, d, multi_tab, id, pending_msg) {
			if (typeof(multi_tab) == "undefined") multi_tab = 0;
			var title = "",important = "", image_msg = "";
			if (chatroom_mod[id] == 1) {
				title = lang[137];
				important = " arrowchat_chatroom_important";
			}
			if (chatroom_admin[id] == 1) {
				title = lang[136];
				important = " arrowchat_chatroom_important";
			}
			if (multi_tab != 1)
				d = d.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br>").replace(/\"/g, "&quot;");
			d = youTubeEmbed(d);
			d = replaceURLWithHTMLLinks(d);
			d = smileyreplace(d);
			if (d.substr(0, 4) == "<div") {
				image_msg = " arrowchat_image_msg";
			}
			var pending_icon = "<div class='arrowchat_pending_icon'></div>";
			if (multi_tab == 1) {
				pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
			}
			var pending_class = "";
			var pending_class_id = "";
			if (pending_msg != 0) {
				pending_class = " arrowchat_pending_msg";
				pending_class_id = ' arrowchat_pending_msg_room_' + pending_msg;
			}
			if (a("#arrowchat_chatroom_message_" + b).length > 0) {
			} else {
				if (b != 0) {
					var id_element = ' id="arrowchat_chatroom_message_' + b + '" ';
				} else {
					var id_element = "";
				}
				a(".arrowchat_chatroom_chat_content").append('<div class="arrowchat_message_wrapper arrowchat_self' + important + image_msg + pending_class + pending_class_id + '" ' + id_element + '><div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + c + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_sender_avatar"><img alt="' + c + '" src="' + u_avatar + '" /></div><div class="arrowchat_chatroom_box_message"><div class="arrowchat_chatroom_message_content"><span class="arrowchat_chatroom_msg">' + d + '</span></div></div></div>' + pending_icon + '</div>');
				scrollOnPage3();
			}
			if (id != 0) {
				showTimeAndTooltip();
				a("#textinput2").focus();
			}
		}
		function receiveChatroom() {
			if (c_chatrooms != 1) {
				return false;
			}
			clearTimeout(Crref2);
			var admin_markup = "";
			var rooms_string = "";
			for (var i in chatroom_list) {
				rooms_string += i + ",";
			}
			rooms_string = rooms_string.slice(0, -1);
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_chatroom.php?_="+new Date().getTime(),
				cache: false,
				type: "post",
				data: {
					chatrooms: rooms_string
				},
				dataType: "json",
				success: function (data) {
					if (data.room_data) {
						var no_error = true;
						data.room_data && a.each(data.room_data, function (i, room) {
							if (room.id == Ccr) {
								if (room.error != 0) {
									no_error = false;	
									Ccr = 0;
									chatroomreceived = 0;
									a("#chatroom-chat-page .back-button").click();
									showToast(f.m, 5000);
								}
								
								chatroom_admin[room.id] = 0;
								chatroom_mod[room.id] = 0;
								
								if (no_error) {
									if (room.user_title.admin == 1) {
										chatroom_admin[room.id] = 1;
									}
									if (room.user_title.mod == 1) {
										chatroom_mod[room.id] = 1;
									}
									
									var adminCount=0,modCount=0,userCount=0,totalCount=0;
									a("#chatroom-users-list").html("");
									
									a.each(room.chat_users, function (l, user) {
										if (user.status != 'invisible') {
											var usertitle = lang[43];
											totalCount++;
											user.n = renderHTMLString(user.n);
											if (user.t == 2) {
												usertitle = lang[44];
											} else if (user.t == 3) {
												usertitle = lang[45];
											} else if (user.t == 4) {
												usertitle = lang[212];
											}
											
											a("#chatroom-users-list").append('<ons-list-item class="arrowchat_chatroom_admin_' + user.t + '" id="arrowchat_chatroom_user_' + user.id + '"><div class="left list-item__left" style="position:relative;"><img class="list-item__thumbnail" src="'+user.a+'" /></div><div class="center list-item__center"><span class="list-item__title">' +user.n + '</span><span class="list-item__subtitle">'+usertitle+'</span></div></ons-list-item>');
										}
										uc_status[user.id] = user.status;
										uc_name[user.id] = user.n;
										uc_avatar[user.id] = user.a;
										uc_link[user.id] = user.l;
										uc_cr_block[user.id] = user.b;
										
										if (user.id != u_id) {
											setUserOptions(user, chatroom_admin[room.id], chatroom_mod[room.id]);
										}
									});
									var sort_by_name = function(a, b) {
										return a.querySelector('.list-item__title').innerHTML.toLowerCase().localeCompare(b.querySelector('.list-item__title').innerHTML.toLowerCase());
									};
									var list = a("#chatroom-users-list > ons-list-item").get();
									list.sort(sort_by_name);
									for (var i = 0; i < list.length; i++) {
										list[i].parentNode.appendChild(list[i]);
									}
									a("#chatroom-header .chatroom_count").html(totalCount);
									a("#arrowchat_chatroom_joined_"+room.id+" .chatroom_count").html(totalCount);
								}
							} else {
								var totalCount=0;
								
								if (room.error != 0) {
									a("#arrowchat_chatroom_leave_"+room.id).click();
									showToast(f.m, 5000);
								} else {
									a.each(room.chat_users, function (l, user) {
										totalCount++;
									});
									
									a("#arrowchat_chatroom_joined_"+room.id+" .chatroom_count").html(totalCount);
								}
							}
						});
						
						if (no_error) {
							setAvatarVisibility();
						}
					}
				}
			});
			clearTimeout(Crref2);
			Crref2 = setTimeout(function () {
				receiveChatroom()
			}, 6E4);
		}
        function DTitChange(name) {
            if (dtit2 != 2) {
                document.title = lang[30] + " " + name + "!";
                dtit2 = 2
            } else {
                document.title = dtit;
                dtit2 = 1
            }
            if (window_focus == false) {
                dtit3 = setTimeout(function () {
                    DTitChange(name)
                }, 1000)
            } else {
                document.title = dtit;
                clearTimeout(dtit3);
            }
        }
        function replaceURLWithHTMLLinks(text) {
           return anchorme.js(text);
        }
		RegExp.escape = function(text) {
			return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		};
		function youTubeEmbed(mess) {
			var regExp = /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?([^\s]+)$/mi;
			var match = mess.match(regExp);
			if (match && match[1].length == 11) {
				mess = '<span style="width:160px;margin-bottom:5px;display:block">' + match[0] + '</span><div style="margin-bottom:5px"></div><iframe style="width:160px;height:140px" src="https://www.youtube.com/embed/' + match[1] + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
			}
			return mess;
		}
		function smileyreplace(mess) {
			if (c_disable_smilies != 1) {
				mess = mess.replace(/^(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff])[\ufe0e\ufe0f]?(?:[\u0300-\u036f\ufe20-\ufe23\u20d0-\u20f0]|\ud83c[\udffb-\udfff])?(?:\u200d(?:[^\ud800-\udfff]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff])[\ufe0e\ufe0f]?(?:[\u0300-\u036f\ufe20-\ufe23\u20d0-\u20f0]|\ud83c[\udffb-\udfff])?)*$/g, function(match, contents, offset, s) 
					{
						return '<span class="arrowchat_emoji_text arrowchat_emoji_32">' + match + '</span>';
					}
				);
				for (var i = 0; i < Smiley.length; i++) {
					var smiley_test = Smiley[i][1].replace(/</g, "&lt;").replace(/>/g, "&gt;");
					var check_emoticon = mess.lastIndexOf(smiley_test);
					if (check_emoticon != -1) {
						mess = mess.replace(
							new RegExp(RegExp.escape(smiley_test), 'g'),
							'<span class="arrowchat_emoji_text"><img src="' + c_ac_path + 'includes/emojis/img/16/' + Smiley[i][0] + '" alt="" /> </span>'
						);
					}
				}
				for (var i = 0; i < premade_smiley.length; i++) {
					var smiley_test = premade_smiley[i][0].replace(/</g, "&lt;").replace(/>/g, "&gt;");
					var check_emoticon = mess.lastIndexOf(smiley_test);
					if (check_emoticon != -1) {
						if (mess == smiley_test) {
							mess = mess.replace(
								new RegExp(RegExp.escape(smiley_test), 'g'),
								premade_smiley[i][1]
							);
						} else {
							mess = mess.replace(
								new RegExp(RegExp.escape(" " + smiley_test), 'g'),
								' ' + premade_smiley[i][1]
							);
						}
					}
				}
			}
			return mess;
		}
        function Oa(b) {
            I(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], uc_message[b], 0)
        }
        function sa(b, c, d) {
            if (uc_name[b] == null || uc_name[b] == "") setTimeout(function () {
                sa(b, c, d)
            }, 500);
            else {
                Oa(b);
                y[b] = c;
            }
        }
        function S() {
            var b = "",
                c = 0;
            for (chatbox in y) if (y.hasOwnProperty(chatbox)) if (y[chatbox] != null) {
                b += chatbox + "|" + y[chatbox] + ",";
                if (y[chatbox] > 0) c = 1
            }
            Ka = c;
            b.slice(0, -1)
        }
        function receiveCore() {
			cancelJSONP();
			var chatroom_string = "";
			if (!a.isEmptyObject(chatroom_list)) {
				for (var i in chatroom_list) {
					chatroom_string = chatroom_string + "&room[]=" + chatroom_list[i];
				}
			}
            var url = c_ac_path + "includes/json/receive/receive_core.php?hash=" + u_hash_id + "&init=" + acsi + chatroom_string + "&_="+new Date().getTime();
            xOptions = a.ajax({
                url: url,
				dataType: "jsonp",
                success: function (b) {
                    V.timestamp = ma;
                    var c = "",
                        d = {};
                    d.available = "";
                    d.busy = "";
                    d.offline = "";
                    d.away = "";
                    onlineNumber = buddylistreceived = 0;
                    if (b && b != null) {
                        var i = 0;
                        a.each(b, function (e, l) {
							if (e == "popout") {
								window.close();
							}
                            if (e == "typing") {
                                a.each(l, function (f, h) {
                                    if (h.is_typing == "1") {
                                        receiveTyping(h.typing_id);
                                    } else {
                                        receiveNotTyping(h.typing_id);
                                    }
                                });
                            }
							if (e == "warnings") {
								a.each(l, function (f, h) {
									receiveWarning(h);
								});
							}
							if (e == "chatroom") {
								var d1 = 0,
									d2 = "";
								a.each(l, function (f, h) {
									if (h.action == 1) {
										a("#arrowchat_chatroom_message_" + h.m + " .arrowchat_chatroom_msg").html(lang[159] + h.n);
									} else {
										if (typeof(blockList[h.userid]) == "undefined") {
											addChatroomMessage(h.id, h.n, h.m, h.userid, h.t, h.global, h.mod, h.admin, h.chatroomid);
										}
										d2 = h;
										d1++;
									}
								});
								updateChatTabTotal();
								showTimeAndTooltip();
								if (typeof d2 != "undefined" && d1 > 0) {
									if (typeof(blockList[d2.userid]) == "undefined") {
										if (d2.userid != u_id) {
											u_chatroom_sound == 1 && Ua();
										}
									}
								}
							}
                            if (e == "messages") {
								var play_sound = 0;
                                a.each(l, function (f, h) {
									receiveMessage(h.id, h.from, h.message, h.sent, h.self, h.old, 0, 0);
									play_sound = 1;
                                });
								
								updateChatTabTotal();
								
                                K = 1;
                                d != 1 && u_sounds == 1 && play_sound == 1 && acsi != 1 &&  Ua();
                                D = E;
								showTimeAndTooltip();
                            }
                        });
                    }
                    if ($ != 1 && w != 1) {
                        K++;
                        if (K > 4) {
                            D *= 2;
                            K = 1
                        }
                        if (D > 12E3) D = 12E3
                    }
                    acsi++;
                    window.onblur = function () {
                        window_focus = false
                    };
                    window.onfocus = function () {
                        window_focus = true
                    };
					if (isAway == 1) {
						var CHT = c_heart_beat * 1000 * 3;
					} else {
						var CHT = c_heart_beat * 1000;
					}
					if (c_push_engine != 1) {
						CHA = setTimeout(function () {
							receiveCore()
						}, CHT);
					}
                }
            });
        }

		function copyToClipboard(string) {
			let textarea;
			let result;
			try {
				textarea = document.createElement('textarea');
				textarea.setAttribute('readonly', true);
				textarea.setAttribute('contenteditable', true);
				textarea.style.position = 'fixed';
				textarea.value = string;

				document.body.appendChild(textarea);

				textarea.focus();
				textarea.select();

				const range = document.createRange();
				range.selectNodeContents(textarea);

				const sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);

				textarea.setSelectionRange(0, textarea.value.length);
				result = document.execCommand('copy');
			} catch (err) {
				console.error(err);
				result = null;
			} finally {
				document.body.removeChild(textarea);
			}
			if (!result) {
				const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
				const copyHotkey = isMac ? '?C' : 'CTRL+C';
				result = prompt(`Press ${copyHotkey}`, string);
				if (!result) {
					return false;
				}
			}
			  return true;
		}
		function showCopy(target) {
			ons.openActionSheet({
			  cancelable: true,
			  modifier: 'copy',
			  maskColor: 'rgba(0, 0, 0, 0.6)',
			  buttons: [lang[297], lang[222]]
			}).then(function (index) {

			});
			a(".action-sheet--copy ons-action-sheet-button:first").unbind('click');
			a(".action-sheet--copy ons-action-sheet-button:first").click(function () {
			console.log(target);
				copyToClipboard(a(target).text());
			});
		}
		function showCopyChatRoom(target) {
			if (!a(target).hasClass('arrowchat_image_msg') || chatroom_mod[Ccr] == 1 || chatroom_admin[Ccr] == 1)
			{
				var button = [];
				if (!a(target).hasClass('arrowchat_image_msg')) {
					button.push(lang[297]);
				}
				button.push(lang[313]);
				if (chatroom_mod[Ccr] == 1 || chatroom_admin[Ccr] == 1) {
					button.push({label:lang[160],modifier:'destructive'});
				}
				button.push(lang[222]);
				ons.openActionSheet({
				  cancelable: true,
				  modifier: 'copy',
				  maskColor: 'rgba(0, 0, 0, 0.6)',
				  buttons: button
				}).then(function (index) {
				console.log(target);
					if ((index == 2 && (chatroom_mod[Ccr] == 1 || chatroom_admin[Ccr] == 1)) || (index == 1 && (chatroom_mod[Ccr] == 1 || chatroom_admin[Ccr] == 1) && a(target).hasClass('arrowchat_image_msg'))) {
						var msg_id = a(target).attr('id').substr(27);
						a.post(c_ac_path + "includes/json/send/send_settings.php", {
							delete_msg: msg_id,
							chatroom_id: Ccr,
							delete_name: u_name
						}, function () {
							a(target).removeClass('arrowchat_image_msg');
							a("#arrowchat_chatroom_message_" + msg_id + " .arrowchat_chatroom_msg").html(lang[159] + u_name);
						})
					}
					if ((index == 1 && !a(target).hasClass('arrowchat_image_msg')) || (index == 0 && a(target).hasClass('arrowchat_image_msg'))) {
						var mention_name = a(target).find('.arrowchat_sender_avatar img').attr("alt");
						if (mention_name == "")
							mention_name = a(target).find('.arrowchat_chatroom_message_name').text();
						if (mention_name.charAt(mention_name.length-1) == ":")
							mention_name = mention_name.substring(0, mention_name.length - 1);
						var mention_full = '@' + mention_name + ' ';
						var existing_text = a("#textinput2").val();
						if (existing_text != "") {
							if (existing_text.charAt(existing_text.length-1) != " ")
								mention_full = ' ' + mention_full;
						}
						a("#textinput2").focus().val('').val(existing_text + mention_full);
					}
				});
				a(".action-sheet--copy ons-action-sheet-button:first").unbind('click');
				a(".action-sheet--copy ons-action-sheet-button:first").click(function () {
					copyToClipboard(a(target).find('.arrowchat_chatroom_msg').text());
				});
			}
		}
		function showTimeAndTooltip() {
			a(".chat_user_content .arrowchat_message_wrapper:not(.arrowchat_image_msg)").unbind('touchstart');
			a(".chat_user_content .arrowchat_message_wrapper:not(.arrowchat_image_msg)").bind('touchstart', function(e) {
				copyTimeout = setTimeout(function (){showCopy(e.target)}, 500);
			});
			a(".chat_user_content .arrowchat_message_wrapper:not(.arrowchat_image_msg)").unbind('touchend touchmove');
			a(".chat_user_content .arrowchat_message_wrapper:not(.arrowchat_image_msg)").bind('touchend touchmove', function(e) {
				clearTimeout(copyTimeout);
			});
			a("#chatroom-chat-page .arrowchat_message_wrapper:not(.arrowchat_chatroom_global)").unbind('touchstart');
			a("#chatroom-chat-page .arrowchat_message_wrapper:not(.arrowchat_chatroom_global)").bind('touchstart', function(e) {
				copyTimeout = setTimeout(function (){showCopyChatRoom(e.currentTarget)}, 500);
			});
			a("#chatroom-chat-page .arrowchat_message_wrapper:not(.arrowchat_chatroom_global)").unbind('touchend touchmove');
			a("#chatroom-chat-page .arrowchat_message_wrapper:not(.arrowchat_chatroom_global)").bind('touchend touchmove', function(e) {
				clearTimeout(copyTimeout);
			});
			a(".arrowchat_lightbox").unbind('click');
			a(".arrowchat_lightbox").click(function () {
				a.slimbox(a(this).attr('data-id'), '<a target="_blank" href="'+a(this).attr('data-id')+'">'+lang[70]+'</a>', {resizeDuration:1, overlayFadeDuration:1, imageFadeDuration:1, captionAnimationDuration:1});
			});
		}
        function za(b, c) {
            if (uc_name[b] == null || uc_name[b] == "") setTimeout(function () {
                za(b, c)
            }, 500);
            else {
                a("#arrowchat_user_" + b).append(c);
                G[b] = 1;
            }
        }
        function ya(b) {
            if (uc_name[b] == null || uc_name[b] == "") setTimeout(function () {
                ya(b)
            }, 500);
            else j != b && a("#arrowchat_popout_user_" + b).click()
        }
        function Ua() {
            ion.sound.play("new_message_mobile");
        }
        function I(b, c, d, e, l, m, f, h, new_message) {
            if (!(b == null || b == "")) {
				if (uc_name[b] == null || uc_name[b] == "") {
					if (aa[b] != 1) {
						aa[b] = 1;
						a.ajax({
							url: c_ac_path + "includes/json/receive/receive_user.php?_="+new Date().getTime(),
							data: {
								userid: b
							},
							type: "post",
							cache: false,
							success: function (o) {
								if (o) {
									c = uc_name[b] = o.n;
									d = uc_status[b] = o.s;
									e = uc_avatar[b] = o.a;
									l = uc_link[b] = o.l;
									m = uc_message[b] = o.m;
									aa[b] = 0;
									if (c != null) {
										qa(b, c, d, e, l, m, f, h, new_message)
									} else {
										a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
											userid: u_id,
											unfocus_chat: b
										}, function () {})
									}
								}
							}
						});
					} else setTimeout(function () {
						I(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], uc_message[b], f, h, new_message)
					}, 500);
				} else {
					qa(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], uc_message[b], f, h, new_message);
				}
			}
        }
        function ha(b) {
            var c = "am",
                d = b.getHours(),
                i = b.getMinutes(),
                e = b.getDate();
            b = b.getMonth();
			var g = d;
            if (d > 11) c = "pm";
            if (d > 12) d -= 12;
            if (d == 0) d = 12;
            if (d < 10) d = d;
            if (i < 10) i = "0" + i;
            var l = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                f = "th";
            if (e == 1 || e == 21 || e == 31) f = "st";
            else if (e == 2 || e == 22) f = "nd";
            else if (e == 3 || e == 23) f = "rd";
			if (c_us_time != 1) {
				return e != Na ? '<span class="arrowchat_ts">' + l[b] + " " + e + ", " + g + ":" + i + "</span>" : '<span class="arrowchat_ts">' + g + ":" + i + "</span>"
			} else {
				return e != Na ? '<span class="arrowchat_ts">' + l[b] + " " + e + ", " + d + ":" + i + c + "</span>" : '<span class="arrowchat_ts">' + d + ":" + i + c + "</span>"
			}
        }
        function receiveHistory(b, times) {
			if (times) {} else times = 1;
			if (times > 1) {
				a('<div id="recent-loader" class="loader-div"><ons-progress-circular indeterminate></ons-progress-circular></div>').prependTo(a("#arrowchat_user_" + b));
			} else {
				hideSmileyButton();
				hideGiphyButton();
				a('.chat_user_content').html('<div id="recent-loader" class="loader-div"><ons-progress-circular indeterminate></ons-progress-circular></div>');
			}
            a.ajax({
                cache: false,
                url: c_ac_path + "includes/json/receive/receive_history.php?_="+new Date().getTime(),
                data: {
                    chatbox: b,
					history: times
                },
                type: "post",
				dataType: "json",
                success: function (c) {
					a(".arrowchat_message_history_loading").remove();
					history_ids[b] = 0;
					numMessages = 0;
                    if (c) {
						if (times == 1)
							a("#arrowchat_user_" + b).html("");
						last_sent[b] = null;
                        var d = "",
							sender_avatar = "",
							arrow = "",
                            i = uc_name[b],
							unhide_avatars = [];
                        a.each(c, function (e, l) {
                            e == "messages" && a.each(l, function (f, h) {
                                f = "";
								numMessages++;
								var pending_icon = "";
                                if (h.self == 1) {
                                    fromname = u_name;
									fromid = u_id;
                                    f = " arrowchat_self";
                                    _aa5 = _aa4 = "";
									sender_avatar = u_avatar;
									arrow = 'send';
									pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
                                } else {
                                    fromname = i;
									fromid = b;
                                    _aa4 = '<a target="_blank" href="' + uc_link[b] + '">';
                                    _aa5 = "</a>";
									sender_avatar = uc_avatar[h.from];
									arrow = 'from';
                                }
								if (last_name[h.from] != fromid && typeof(last_name[h.from]) != "undefined") {
									unhide_avatars.push(last_id[h.from]);
								}
								var full_name = fromid;
								var image_msg = "";
								var show_time_class = "";
                                var o = new Date(h.sent * 1E3);
								if (c_show_full_name != 1) {
									if (fromname.indexOf(" ") != -1) fromname = fromname.slice(0, fromname.indexOf(" "));
								}
								if (h.message.substr(0, 4) == "<div") {
									image_msg = " arrowchat_image_msg";
								}
								if (last_sent[h.from] == null || h.sent - last_sent[h.from] > 180) {
									show_time_class = " arrowchat_show_time";
								}
								if (a("#arrowchat_message_" + h.id).length > 0) {
									a("#arrowchat_message_" + h.id + " .arrowchat_chatboxmessagecontent").html(h.message);
								} else {
									var noAvatarColor = getRandomColor(fromname);
									d += '<div class="arrowchat_ts_wrapper' + show_time_class + '">' + ha(o) + '</div><div class="arrowchat_message_wrapper arrowchat_clearfix' + f + image_msg + show_time_class + '" id="arrowchat_message_' + h.id + '"><div class="arrowchat_sender_avatar arrowchat_single_avatar_hide arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_small">' + noAvatarColor["character"] + '</span><img alt="' + fromname + '" src="' + sender_avatar + '" /></div><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0">' + h.message + '</div></div>' + pending_icon + '</div>';
									N = full_name;
								}
								
								last_sent[h.from] = h.sent;
								last_name[h.from] = fromid;
								last_id[h.from] = h.id;
								
								if (times == 1) {
									uc_message[b] = h.message;
								}
                            });
                        });
						if (a("#arrowchat_userlist_recent_" + b).length > 0) {
							a("#arrowchat_userlist_recent_" + b + " .list-item__subtitle").html(processMessage(uc_message[b]));
						}
						a("#recent-loader").remove();
						var current_top_element = a("#arrowchat_user_" + b).children('div').children('div').first('div');
                        if (times > 1) {
							a(d).prependTo(a("#arrowchat_user_" + b).children('div'));
						} else {
							a("#arrowchat_user_" + b).html("<div>" + d + "</div>");
						}
						if (numMessages == 0 && times == 1) {
							a("#arrowchat_user_" + b).html('<p id="no-recent-convo">'+lang[245]+'</p>');
						}
						if (a("#arrowchat_message_" + last_id[b]).length) {
							a("#arrowchat_message_" + last_id[b]).children('.arrowchat_sender_avatar').removeClass('arrowchat_single_avatar_hide');
						}
						a.each(unhide_avatars, function(key, value) {
							if (a("#arrowchat_message_" + value).length) {
								a("#arrowchat_message_" + value).children('.arrowchat_sender_avatar').removeClass('arrowchat_single_avatar_hide');
							}
						});
						a(".arrowchat_pending_icon:not(.arrowchat_pending_error)", "#arrowchat_user_" + b).hide();
						a("div .arrowchat_pending_icon:last", "#arrowchat_user_" + b).show();
						setAvatarVisibility();
						showTimeAndTooltip();
						if (times == 1) {
							scrollOnPage2();
						}
						var previous_height = 0;
						current_top_element.prevAll().each(function() {
							previous_height += a(this).outerHeight();
						});
						if (times == 1)
							a('#private-chat-page .page__content').scrollTop(5E4);
						else {
							a('#private-chat-page .page__content').scrollTop(previous_height);
							setTimeout(function(){a('#private-chat-page .page__content').scrollTop(previous_height);}, 10);
						}
						
						a('#private-chat-page .page__content').unbind('scroll');
						a('#private-chat-page .page__content').on('scroll', function(e) {
							if (a('#private-chat-page .page__content').scrollTop() <= 50 && history_ids[b] != 1) {
								if (scroll_last >= (Date.now() - 700)){}else{
									history_ids[b] = 1;
									scroll_last = Date.now();
									if (numMessages == 20) {
										times++;
										receiveHistory(b, times);
									}
								}
							}
						});
						if (times == 1) {
							a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
								scrollOnPage2();
							}).each(function() {
								if(this.complete) a(this).trigger('load');
							});
						}
                    }
                }
            })
        }
		
		function chatroomKeyup(key, $element, id) {
			resizeChatfield($element);
		}
		
		function chatroomKeydown(key, $element, id) {
			if (key.keyCode == 13 && key.shiftKey == 0) {
				sendChatroomMessage($element, id);
				return false;
			}
		}
		
		function update_pending_room_status(id, pending_msg_count_local, pending_class) {
			if (a(".arrowchat_pending_msg_room_"+pending_msg_count_local).length > 0) {
				a(".arrowchat_pending_msg_room_"+pending_msg_count_local+" .arrowchat_pending_icon").addClass(pending_class);
				if (a.isNumeric(id) && id != 0)
					a(".arrowchat_pending_msg_room_"+pending_msg_count_local).attr('id', "arrowchat_chatroom_message_"+id);
			}
		}
		
		function sendChatroomMessage($element, to_id) {
			var i = $element.val();
			i = i.replace(/^\s+|\s+$/g, "");
			$element.val("");
			$element.css("height", "38px");
			$element.css("overflow-y", "hidden");
			a(".bottom-bar").css('height', '105px');
			a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
			$element.focus();
			if (c_send_room_msg == 1 && i != "") {
				showToast(lang[209], 5000);
			} else {
				var pending_msg_count_local = 0;
				i != "" && a.ajax({
					url: c_ac_path + "includes/json/send/send_message_chatroom.php?_="+new Date().getTime(),
					type: "post",
					cache: false,
					dataType: "json",
					data: {
						userid: u_id,
						username: u_name,
						chatroomid: to_id,
						message: i
					},
					beforeSend: function () {
						scrollOnPage3();
						u_chatroom_sound == 1 && ion.sound.play("send_mobile");
						
						pending_msg_room_count++;
						pending_msg_count_local = pending_msg_room_count;
						
						addMessageToChatroom(0, u_name, i, 0, to_id, pending_msg_count_local);
					},
					error: function () {
						showToast(lang[135], 5000);
						update_pending_room_status(0, pending_msg_count_local, "arrowchat_pending_error");
					},
					success: function (o) {
						if (o) {
							var is_json = true;
							if (a.isNumeric(o)) is_json = false;
							var no_error = true;
							if (is_json) {
								o && a.each(o, function (i, e) {
									if (i == "error") {
										a.each(e, function (l, f) {
											no_error = false;
											showToast(f.m, 5000);
											update_pending_room_status(o, pending_msg_count_local, "arrowchat_pending_error");
										});
									}
								});
							}

							if (no_error) {
								update_pending_room_status(o, pending_msg_count_local, "arrowchat_pending_delivered");
								var data_array = [o, u_name, i, to_id];
								lsClick(JSON.stringify(data_array), 'send_chatroom_message');
							}
						}
					}
				});
			}
			return false
		}
		
		function userchatKeydown(key, $element, typing) {
			clearTimeout(pa);
			pa = setTimeout(function () {
				a.post(c_ac_path + "includes/json/send/send_typing.php?_="+new Date().getTime(), {
					userid: u_id,
					typing: typing,
					untype: 1
				}, function () {});
				fa = -1
			}, 5E3);
			if (fa != typing) {
				a.post(c_ac_path + "includes/json/send/send_typing.php?_="+new Date().getTime(), {
					userid: u_id,
					typing: typing
				}, function () {});
				fa = typing
			}

			if (key.keyCode == 13 && key.shiftKey == 0) {
				sendUserMessage($element, typing);
				return false;
			}
		}
		
		function update_pending_status(id, pending_msg_count_local, pending_class) {
			if (a(".arrowchat_pending_msg_"+pending_msg_count_local).length > 0) {
				a(".arrowchat_pending_msg_"+pending_msg_count_local+" .arrowchat_pending_icon").addClass(pending_class);
				if (a.isNumeric(id) && id != 0)
					a(".arrowchat_pending_msg_"+pending_msg_count_local).attr('id', "arrowchat_message_"+id);
			}
		}
		
        function sendUserMessage($element, to_id) {
			var sent_msg = $element.val();
			sent_msg = sent_msg.replace(/^\s+|\s+$/g, "");
			$element.val("");
			$element.css("height", "38px");
			$element.css("overflow-y", "hidden");
			a(".bottom-bar").css('height', '105px');
			a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
			$element.focus();
			if (c_send_priv_msg == 1 && sent_msg != "") {
				showToast(lang[209], 5000);
			} else {
				var pending_msg_count_local = 0;
				var time = Math.floor((new Date).getTime() / 1E3);
				if (sent_msg != "") {
					scrollOnPage2();
					u_sounds == 1 && ion.sound.play("send_mobile");
					a.ajax({
						url: c_ac_path + "includes/json/send/send_message.php?_="+new Date().getTime(),
						type: "post",
						cache: false,
						dataType: "json",
						data: {
							userid: u_id,
							to: to_id,
							message: sent_msg
						},
						beforeSend: function () {
							pending_msg_count++;
							pending_msg_count_local = pending_msg_count;
							
							clearTimeout(pa);
							fa = -1;
							
							receiveMessage(0, to_id, sent_msg, time, 1, 0, 1, pending_msg_count_local);
						},
						error: function () {
							showToast(lang[135], 5000);
							update_pending_status(0, pending_msg_count_local, "arrowchat_pending_error");
						},
						success: function (e) {
							if (e) {
								if (!a.isNumeric(e)) {
									a.each(e, function (i, q) {
										if (i == "error") {
											a.each(q, function (l, f) {
												showToast(f.m, 5000);
												update_pending_status(e, pending_msg_count_local, "arrowchat_pending_error");
											});
										}
									});
								} else {
									last_id[to_id] = e;
									update_pending_status(e, pending_msg_count_local, "arrowchat_pending_delivered");
									var data_array = [e, to_id, sent_msg, time, 1, 1];
									lsClick(JSON.stringify(data_array), 'private_message');
								}
							}
							K = 1;
						}
					});
				}
			}
			return false
        }
		
		function setAvatarVisibility() {
			if (c_disable_avatars == 1 || u_no_avatars == 1) { 
				a("#buddylist-container-recent img").addClass("arrowchat_hide_avatars");
				a(".chatroom_joined img").removeClass("arrowchat_hide_avatars");
				a("#settings-image").addClass("arrowchat_hide_avatars");
				a("#settings-image2").addClass("arrowchat_hide_avatars");
				a("#settings-open-image").addClass("arrowchat_hide_avatars");
				a(".user-window .online-list-icon").addClass("arrowchat_hide_avatars_online_icons");
				a("#buddylist-container-available img").addClass("arrowchat_hide_avatars");
				a("#private-chat-page .avatar-header img").addClass("arrowchat_hide_avatars");
				a(".arrowchat_sender_avatar img").addClass("arrowchat_hide_avatars");
				a("#chatroom-users-list img").addClass("arrowchat_hide_avatars");
				a("#search-buddylist-list img").addClass("arrowchat_hide_avatars");
				a("#search-buddylist-list .online-list-icon").addClass("arrowchat_hide_avatars_search_icons");
				a(".arrowchat_sender_avatar").removeClass("arrowchat_white_background");
				a(".settings-open").removeClass("arrowchat_white_background");
				a(".settings-image-wrapper").removeClass("arrowchat_white_background");
				a(".buddylist-avatar-wrapper").removeClass("arrowchat_white_background");
				a("#private-chat-page .avatar-header").removeClass("arrowchat_white_background");
				a(".arrowchat_tab_letter").css('display', 'flex');
			} else {
				a("#buddylist-container-recent img").removeClass("arrowchat_hide_avatars");
				a(".chatroom_joined img").removeClass("arrowchat_hide_avatars");
				a("#settings-image").removeClass("arrowchat_hide_avatars");
				a("#settings-image2").removeClass("arrowchat_hide_avatars");
				a("#settings-open-image").removeClass("arrowchat_hide_avatars");
				a(".user-window .online-list-icon").removeClass("arrowchat_hide_avatars_online_icons");
				a("#buddylist-container-available img").removeClass("arrowchat_hide_avatars");
				a("#private-chat-page .avatar-header img").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_sender_avatar img").removeClass("arrowchat_hide_avatars");
				a("#chatroom-users-list img").removeClass("arrowchat_hide_avatars");
				a("#search-buddylist-list img").removeClass("arrowchat_hide_avatars");
				a("#search-buddylist-list .online-list-icon").removeClass("arrowchat_hide_avatars_search_icons");
				a(".arrowchat_sender_avatar").addClass("arrowchat_white_background");
				a(".settings-open").addClass("arrowchat_white_background");
				a(".settings-image-wrapper").addClass("arrowchat_white_background");
				a(".buddylist-avatar-wrapper").addClass("arrowchat_white_background");
				a("#private-chat-page .avatar-header").addClass("arrowchat_white_background");
				a(".arrowchat_tab_letter").hide();
			}
		}
		function resizeChatfield($element) {
			var height = $element[0].clientHeight;
			if (height < 94) {
				height = Math.max($element[0].scrollHeight, height);
				height = Math.min(94, height);
				if (height > $element[0].clientHeight) {
					$element.css("height", height + 3 + "px");
					a(".bottom-bar").css('height', height + 71 + "px");
					a(".page-with-bottom-toolbar>.page__content").css('bottom', height + 71 + "px");
				}
			} else {
				$element.css("overflow-y", "auto");
			}
		}
        function userchatKeyup(key, $element, d) {
			resizeChatfield($element);
        }
		function uploadProcessing(chatroom) {
			var ts67 = Math.round(new Date().getTime());
			var path = c_ac_path.replace("../", "/");
			var b1 = "#uploadifive-arrowchat_upload_button",
				b2 = "#arrowchat_upload_button",
				b3 = ".chat_user_content";
			if (chatroom == 1) {
				b1 = "#uploadifive-arrowchat_upload_button2";
				b2 = "#arrowchat_upload_button2";
				b3 = ".arrowchat_chatroom_chat_content";
			}
			if (a(b1).length > 0)
				a(b2).uploadifive('destroy');
			a(b2).uploadifive({
				'uploadScript': path + 'includes/classes/class_uploads.php?_='+new Date().getTime(),
				'buttonText': ' ',
				'buttonClass': 'arrowchat_upload_user_button',
				'removeCompleted' : true,
				'formData': {
					'unixtime': ts67,
					'user': u_id
				},
				'queueID' : 'arrowchat_user_upload_queue',
				'height': 25,
				'width': 24,
				'multi': false,
				'auto': true,
				'fileType': '.avi,.bmp,.doc,.docx,.gif,.ico,.jpeg,.jpg,.mp3,.mp4,.pdf,.png,.ppt,.pptx,.rar,.tar,.txt,.wav,.wmv,.xls,.xlsx,.zip,.7z',
				'fileSizeLimit' : c_max_upload_size + 'MB',
				'onError': function (file, errorCode, errorMsg, errorString) {

				},
				'onCancel': function (file) {

				},
				'onUploadComplete': function (file) {
					var uploadType = "file",
						fileType = file.type.toLowerCase();
					if (fileType == "image/jpeg" || fileType == "image/gif" || fileType == "image/jpg" || fileType == "image/png")
						uploadType = "image";

					var sendUrl = "includes/json/send/send_message.php?_="+new Date().getTime();
					var messageID = "arrowchat_message_";
					if (chatroom == 1) {
						sendUrl = "includes/json/send/send_message_chatroom.php?_="+new Date().getTime();
						messageID = "arrowchat_chatroom_message_";
					}

					a.post(c_ac_path + sendUrl, {
						userid: u_id,
						username: u_name,
						chatroomid: Ccr,
						to: j,
						message: uploadType + "{" + ts67 + "}{" + file.name + "}"
					}, function (e) {
						if (!a.isNumeric(e)) {
							a.each(e, function (i, q) {
								if (i == "error") {
									a.each(q, function (l, f) {
										showToast(f.m, 5000);
									});
								}
							});
						} else {
							if (a("#" + messageID + e).length) {} else {
								if (uploadType == "image") {
									a(b3).append('<div class="arrowchat_message_wrapper arrowchat_self arrowchat_image_msg" id="' + messageID + e + '"><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0"><div class="arrowchat_image_message"><img data-id="' + c_ac_path + 'public/download.php?file=' + ts67 + '" src="' + c_ac_path + 'public/download.php?file=' + ts67 + '_t" alt="Image" class="arrowchat_lightbox"></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
									a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
										scrollOnPage2();
										scrollOnPage3();
									}).each(function() {
										if(this.complete) a(this).trigger('load');
									});
								} else {
									a(b3).append('<div class="arrowchat_message_wrapper arrowchat_self arrowchat_image_msg" id="' + messageID + e + '"><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0"><div class="arrowchat_action_message"><div class="arrowchat_action_message_wrapper">' + lang[69] + '</div><div class="arrowchat_action_message_action"><a href="' + c_ac_path + 'public/download.php?file=' + ts67 + '">' + file.name + '</a></div></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
								}
							}
						}
						if (a(".arrowchat_smiley_button").hasClass('emoji_clicked')) {
							a(".arrowchat_smiley_button").removeClass('emoji_clicked').addClass('far');
							a(".smiley_box_wrapper").hide();
						}
						showTimeAndTooltip();
						hideSmileyButton();
						hideGiphyButton();
						a("#page2").css("padding-bottom", a("#page2 .ui-footer").outerHeight() + "px");
						a("#page3").css("padding-bottom", a("#page3 .ui-footer").outerHeight() + "px");
						scrollOnPage2();
						scrollOnPage3();
					});
					uploadProcessing(chatroom);
				}
			});
		}
		function hideSmileyButton() {
			if (a("#textinput1").length != 0)
				a("#textinput1").removeAttr('readonly');
			if (a("#textinput2").length != 0)
				a("#textinput2").removeAttr('readonly');
			if (a(".arrowchat_smiley_button").hasClass('emoji_clicked')) {
				a(".arrowchat_smiley_button").removeClass('emoji_clicked').addClass('far');
				a(".smiley_box_wrapper").hide();
				a("#textinput1").css('height', '38px');
				a(".bottom-bar").css('height', '105px');
				a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
				resizeChatfield(a("#textinput1"));
			}
			if (a(".arrowchat_smiley_button2").hasClass('emoji_clicked')) {
				a(".arrowchat_smiley_button2").removeClass('emoji_clicked').addClass('far');
				a(".smiley_box_wrapper").hide();
				a("#textinput2").css('height', '38px');
				a(".bottom-bar").css('height', '105px');
				a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
				resizeChatfield(a("#textinput2"));
			}
		}
		function hideGiphyButton() {
			if (a(".arrowchat_giphy_button").hasClass('giphy_clicked')) {
				a(".arrowchat_giphy_button").removeClass('giphy_clicked');
				a(".arrowchat_giphy_image_wrapper").hide();
				a(".arrowchat_giphy_search_wrapper").hide();
				a("#textinput1").css('height', '38px');
				a(".bottom-bar").css('height', '105px');
				a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
				a("#send_button").show();
				a("#textinput1").show();
				resizeChatfield(a("#textinput1"));
				a("#textinput1").focus();
			}
			if (a(".arrowchat_giphy_button2").hasClass('giphy_clicked')) {
				a(".arrowchat_giphy_button2").removeClass('giphy_clicked');
				a(".arrowchat_giphy_image_wrapper2").hide();
				a(".arrowchat_giphy_search_wrapper2").hide();
				a("#textinput2").css('height', '38px');
				a(".bottom-bar").css('height', '105px');
				a(".page-with-bottom-toolbar>.page__content").css('bottom', '105px');
				a("#send_button_chatroom").show();
				a("#textinput2").show();
				resizeChatfield(a("#textinput2"));
				a("#textinput2").focus();
			}
		}
		function initGiphy(chatroom) {
			var b1 = ".arrowchat_giphy_button",
				b2 = ".arrowchat_giphy_image_wrapper",
				b3 = ".arrowchat_giphy_search_wrapper",
				b4 = ".arrowchat_giphy_search",
				b5 = "#send_button",
				b6 = "#textinput1",
				b7 = ".giphy_cancel",
				b8 = ".bottom-bar",
				b9 = ".page-with-bottom-toolbar>.page__content",
				selector = 1;
			if (chatroom == 1) {
				b1 = ".arrowchat_giphy_button2";
				b2 = ".arrowchat_giphy_image_wrapper2";
				b3 = ".arrowchat_giphy_search_wrapper2";
				b4 = ".arrowchat_giphy_search2";
				b5 = "#send_button_chatroom";
				b6 = "#textinput2";
				b7 = ".giphy_cancel2";
				b8 = ".bottom-bar",
				b9 = ".page-with-bottom-toolbar>.page__content",
				selector = 2;
			}
			a(b1).unbind('click');
			a(b1).click(function() {
				if (a(this).hasClass('giphy_clicked')) {
					a(b1).removeClass('giphy_clicked');
					a(b8).css('height', '105px');
					a(b9).css('bottom', '105px');
					a(b6).css('height', '38px');
					a(b2).hide();
					a(b3).hide();
					a(b5).show();
					a(b6).show();
					a(b6).focus();
				} else {
					hideSmileyButton();
					a(b4).val('');
					a(b1).addClass('giphy_clicked');
					a(b9).css('transition', 'bottom 0.15s ease-out');
					a(b8).css('height', '180px');
					a(b9).css('bottom', '180px');
					a(b6).css('height', '38px');
					a(b2).show();
					a(b3).show();
					a(b5).hide();
					a(b6).hide();
					a(b4).focus();
					loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', selector);
				}
				setTimeout(function () {
					if (chatroom == 1)
						scrollOnPage3();
					else
						scrollOnPage2();
				}, 100);
			});
			a(b4).unbind('keyup');
			a(b4).keyup(function () {
				a(b2).html('<div class="arrowchat_loading_icon"></div>');
				if (a(b4).val() == '')
					loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', selector);
				else
					loadGiphy('https://api.giphy.com/v1/gifs/search?api_key=IOYyr4NK5ldaU&limit=20&q=' + a(b4).val(), selector);
			});
			a(b7).click(function() {
				hideGiphyButton();
			});
		}
		function initEmoji(chatroom) {
			var b1 = ".arrowchat_smiley_button",
				b2 = "smiley_box_wrapper2",
				b3 = "#textinput1",
				b4 = ".bottom-bar",
				b5 = ".page-with-bottom-toolbar>.page__content";
			if (chatroom == 1) {
				b1 = ".arrowchat_smiley_button2";
				b2 = "smiley_box_wrapper3";
				b3 = "#textinput2",
				b4 = ".bottom-bar",
				b5 = ".page-with-bottom-toolbar>.page__content";
			}
			a(b1).unbind('click');
			a(b1).click(function() {
				if (a(this).hasClass('emoji_clicked')) {
					a(b1).removeClass('emoji_clicked').addClass('far');
					a(".smiley_box_wrapper").hide();
					a(b4).css('height', '105px');
					a(b5).css('bottom', '105px');
					a(b3).css('height', '38px');
					a(b3).removeAttr('readonly');
					resizeChatfield(a(b3));
				} else {
					hideGiphyButton();
					a(b1).addClass('emoji_clicked').removeClass('far');
					a(b4).css('height', '310px');
					a(b5).css('bottom', '310px');
					a(b3).css('height', '38px');
					a(".smiley_box_wrapper").show();
					document.getElementById(b2).scrollLeft = 0;
					var emoji_array = ["animals", "food", "activities", "travel", "objects", "symbols", "flags", "custom"];
					if (emoji_loaded != 1 || emoji_loaded_chatroom != 1) {
						a.ajax({
							url: c_ac_path + 'includes/emojis/emoji_smileys.php',
							type: "GET",
							cache: true,
							success: function(html) {
								if (chatroom == 1)
									emoji_loaded_chatroom = 1;
								else
									emoji_loaded = 1;
								a(".smiley_box_wrapper").html('<div class="emoji_box">' + html + '</div>');
								for (index = 0; index < emoji_array.length; ++index) {
									loadEmojis(emoji_array[index], chatroom);
								}
							}
						});
					}
					a(b3).attr('readonly','readonly');
				}
				setTimeout(function () {
					if (chatroom == 1)
						scrollOnPage3();
					else
						scrollOnPage2();
				}, 100);
			});
			a(b3).focus(function() {
			
			});
			a(b3).unbind('click');
			a(b3).click(function() {
				a(b3).removeAttr('readonly');
				a(b1).removeClass('emoji_clicked').addClass('far');
				a(".smiley_box_wrapper").hide();
				a(b3).css('height', '38px');
				a(b4).css('height', '105px');
				a(b5).css('bottom', '105px');
				resizeChatfield(a(b3));
				a(b3).focus();
			});
		}
		function loadEmojis(id, chatroom) {
			var b1 = "#textinput1";
			if (chatroom == 1) {
				b1 = "#textinput2";
			}
			a.ajax({
				url: c_ac_path + 'includes/emojis/emoji_' + id + '.php',
				type: "GET",
				cache: true,
				success: function(html) {
					a(".smiley_box_wrapper").append('<div class="emoji_box ' + id + '">' + html + '</div>');
					a(".arrowchat_emoji").unbind('click');
					a(".arrowchat_emoji").click(function () {
						if (a(this).hasClass("arrowchat_emoji_custom"))
							var smiley_code = a(this).children('img').attr("data-id");
						else
							var smiley_code = a(this).html();
						var existing_text = a(b1).val();
						a(b1).val('').val(existing_text + smiley_code);
					});
				}
			});
		}
		function loadGiphy(url, selector) {
			var b1 = ".arrowchat_giphy_image_wrapper";
			if (selector == 2) {
				b1 = ".arrowchat_giphy_image_wrapper2";
			}
			a.ajax({
				url: url,
				type: "get",
				cache: false,
				dataType: "json",
				success: function (results) {
					results && a.each(results, function (i, e) {
						if (i == "data") {
							a(b1).html('');
							var new_width = 0;
							a.each(e, function (l, f) {
								new_width = Math.round((110/(f.images.fixed_height_downsampled.height/f.images.fixed_height_downsampled.width)));
								a(b1).append('<img class="arrowchat_giphy_image" src="' + f.images.fixed_height_downsampled.url + '" alt="" style="height:110px;width:' + new_width + 'px" width="' + new_width + '" />');
							});
							a(".arrowchat_giphy_image").click(function () {
								var giphy_src = a(this).attr('src');
								if (selector == 2) {
									a.post(c_ac_path + "includes/json/send/send_message_chatroom.php?_="+new Date().getTime(), {
										userid: u_id,
										username: u_name,
										chatroomid: Ccr,
										message: "giphy{" + a(this).attr('height') + "}{" + a(this).attr('src') + "}"
									}, function (e) {
										if (!a.isNumeric(e)) {
											a.each(e, function (i, q) {
												if (i == "error") {
													a.each(q, function (l, f) {
														showToast(f.m, 5000);
													});
												}
											});
										} else {
											if (a("#arrowchat_chatroom_message_" + e).length) {} else {
												a(".arrowchat_chatroom_chat_content").append('<div class="arrowchat_message_wrapper arrowchat_self arrowchat_image_msg" id="arrowchat_chatroom_message_' + e + '"><div class="arrowchat_chatroom_box_message"><div class="arrowchat_chatroom_message_content"><div class="arrowchat_giphy_message"><img class="arrowchat_lightbox" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" style="width:179px"></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
											}
										}
										scrollOnPage3();
									});
								} else {
									a.post(c_ac_path + "includes/json/send/send_message.php?_="+new Date().getTime(), {
										userid: u_id,
										to: j,
										username: u_name,
										chatroomid: Ccr,
										message: "giphy{" + a(this).attr('height') + "}{" + a(this).attr('src') + "}"
									}, function (e) {
										if (!a.isNumeric(e)) {
											a.each(e, function (i, q) {
												if (i == "error") {
													a.each(q, function (l, f) {
														showToast(f.m, 5000);
													});
												}
											});
										} else {
											if (a("#arrowchat_message_" + e).length) {} else {
												a(".chat_user_content").append('<div class="arrowchat_message_wrapper arrowchat_self arrowchat_image_msg" id="arrowchat_message_' + e + '"><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0"><div class="arrowchat_giphy_message"><img class="arrowchat_lightbox" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" style="width:179px"></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
											}
										}
										scrollOnPage2();
									});
								}
								hideGiphyButton();
								if (selector == 2)
									a("#textinput2").focus();
								else
									a("#textinput1").focus();
							});
						}
					});
				}
			});
		}
        function qa(b, c, d, e, l, m, f, h, new_message) {
			a("#recent-loader").remove();
			a("#no-recent-convo").remove();
			if (a("#arrowchat_user_" + b).length > 0) {

			} else {
                longname = renderHTMLString(c);
				status_icon = "";
				status_color = "#42b72a";
				if (uc_status[b] == "available" || uc_status[b] == "offline") {
					status_icon = "circle";
					if (uc_status[b] == "offline")
						status_color = "#cecece";
				} else if (uc_status[b] == "busy") {
					status_icon = "mobile-alt";
				} else {
					status_icon = "moon";
					status_color = "#5485e8";
				}
				if (typeof(uc_message[b]) == "undefined")
					uc_message[b] = "";
				var recentmessage = uc_message[b];
				if(recentmessage.length > 40) recentmessage = recentmessage.substring(0,40) + '...';

				if (a("#arrowchat_userlist_recent_" + b).length > 0) {a("#arrowchat_userlist_recent_" + b).show();} else {
					var noAvatarColor = getRandomColor(longname);
					a("#buddylist-container-recent").prepend('<ons-carousel id="arrowchat_userlist_recent_' + b + '" swipeable auto-scroll-ratio="0.2" auto-scroll><ons-carousel-item><ons-list-item modifier="nodivider"><div class="left" style="position:relative;"><div class="buddylist-avatar-wrapper arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter">' + noAvatarColor["character"] + '</span><img class="list-item__thumbnail" src="'+e+'" /></div><div class="online-list-icon"><ons-icon size="12px" style="color: '+status_color+'" icon="fa-'+status_icon+'"></ons-icon></div></div><div class="center"><span class="list-item__title">' +longname + '</span><span class="list-item__subtitle">'+recentmessage+'</span></div><div class="right"></div></ons-list-item></ons-carousel-item><ons-carousel-item id="arrowchat_userlist_remove_'+b+'" class="arrowchat_userlist_remove">Remove</ons-carousel-item></ons-carousel>');
				}
				setAvatarVisibility();
				if (j != b && new_message == 1) {
					a("#arrowchat_userlist_recent_" + b + " .list-item__title").addClass('new-message-bold');
					a("#arrowchat_userlist_recent_" + b + " .list-item__subtitle").addClass('new-message-bold');
					var resort = a("#arrowchat_userlist_recent_" + b);
					a("#arrowchat_userlist_recent_" + b).remove();
					a("#buddylist-container-recent").prepend(resort);
				}
				a("#arrowchat_userlist_remove_" + b).unbind("click");
				a("#arrowchat_userlist_remove_" + b).click(function () {
					a("#arrowchat_userlist_recent_" + b).slideUp('fast');
					a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
						close_chat: b,
						tab_alert: 1
					}, function () {});
				});
				a("#arrowchat_userlist_recent_" + b).unbind("click");
				a("#arrowchat_userlist_recent_" + b).click(function (e) {
					if (e.target.id != "arrowchat_userlist_remove_"+b) {
						changeToPrivateChat(b);
						var resort = a("#arrowchat_userlist_recent_" + b).clone(true);
						a("#arrowchat_userlist_recent_" + b).remove();
						a("#buddylist-container-recent").prepend(resort);
					}
				});
				y[b] = 0;
				G[b] = 0;
			}
			if (f == 1) {
				var resort = a("#arrowchat_userlist_recent_" + b).clone(true);
				a("#arrowchat_userlist_recent_" + b).remove();
				a("#buddylist-container-recent").prepend(resort);
				changeToPrivateChat(b);
			}
        }
		function changeToPrivateChat(id) {
			a('#myNavigator')[0].pushPage('private-chat', {data: {title: uc_name[id]}, callback:function(){}});
			document.querySelector('#myNavigator').addEventListener('postpush', function myListener(e) {
				a(".chat_user_content").attr("id", "arrowchat_user_" + id);
				message_count[id] = 0;
				updateChatTabTotal();
				if (typeof(uc_name[id]) != "undefined") {
					setPrivateUserOptions(id);
					var status = uc_status[id];
					if (status == "available")
						status = lang[1];
					if (status == "away")
						status = lang[240];
					if (status == "offline")
						status = lang[241];
					if (status == "busy")
						status = lang[216];
					var noAvatarColor = getRandomColor(uc_name[id]);
					a("#username-header").html('<div class="avatar-header arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img alt="" src="' + uc_avatar[id] + '"></div><div class="username-header-text"><div id="name-header">' + uc_name[id] + '</div><div id="status-header">' + status + '</div></div>');
				}
				var tba = 0;
				if (j != "") {
					j = ""
				}
				receiveHistory(id);
				/*window.visualViewport.addEventListener('resize', function listener(e) {
					a("#private-chat-page ons-toolbar").css("top", window.visualViewport.offsetTop);
				});*/
				a("#private-chat-page ons-back-button").unbind("click");
				a("#private-chat-page ons-back-button").click(function () {
					j = "";
					emoji_loaded_chatroom = 0;
					emoji_loaded = 0;
					scrollOnPage3();
				});
				a("#textinput1").keydown(function (h) {
					return userchatKeydown(h, a(this), id)
				});
				a("#textinput1").keyup(function (h) {
					return userchatKeyup(h, a(this), id)
				});
				a("#textinput1").bind('blur', function() {
					scrollOnPage2();
				});
				a(".back-count-wrapper").unbind('click');
				a(".back-count-wrapper").click(function() {
					a("#private-chat-page ons-back-button").click();
				});
				a("#send_button").unbind('click');
				a("#send_button").click(function () {
					hideSmileyButton();
					scrollOnPage2();
					sendUserMessage(a("#textinput1"), j);
				});
				if (c_video_chat == 1 && uc_status[id] != "offline" && (c_video_select == 2 || c_video_select == 3)) {
					setTimeout(function () {a("#start-video-chat").show();}, 1);
					a("#start-video-chat").unbind('click');
					a("#start-video-chat").click(function () {
						if (uc_status[id] != 'offline' && (c_video_select == 2 || c_video_select == 3)) {
							var RN = Math.floor(Math.random() * 9999999999);
							while (String(RN).length < 10) {
								RN = '0' + RN;
							}
							if (c_video_select == 2) {
								a.ajax({
									type:"post",
									url: c_ac_path + "public/video/video_session.php?_="+new Date().getTime(),
									data: {
										room: RN
									},
									async: false,
									success: function(sess) {
										a.post(c_ac_path + "includes/json/send/send_message.php?_="+new Date().getTime(), {
											userid: u_id,
											to: id,
											message: "video{" + sess + "}"
										}, function (e) {
											if (!a.isNumeric(e)) {
												a.each(e, function (i, q) {
													if (i == "error") {
														a.each(q, function (l, f) {
															showToast(f.m, 5000);
														});
													}
												});
											} else {
												jqac.arrowchat.videoWith(sess);
												showToast(lang[63], 3000);
											}
											scrollOnPage2();
										});
									}
								});
							} else {
								a.post(c_ac_path + "includes/json/send/send_message.php?_="+new Date().getTime(), {
									userid: u_id,
									to: id,
									message: "video{" + RN + "}"
								}, function (e) {
								if (!a.isNumeric(e)) {
									a.each(e, function (i, q) {
											if (i == "error") {
												a.each(q, function (l, f) {
													showToast(f.m, 5000);
												});
											}
										});
									} else {
										jqac.arrowchat.videoWith(RN);
										showToast(lang[63], 3000);
									}
									scrollOnPage2();
								});
							}
						} else {
							a("#start-video-chat").hide();
						}
					});
				} else {
					a("#start-video-chat").hide();
				}
				if (c_giphy != 1) {initGiphy(0);}else{a(".arrowchat_giphy_button").hide()}
				if (c_file_transfer == 1) {uploadProcessing(0);}else{a("#arrowchat_upload_button").hide()}
				if (c_disable_smilies != 1) {initEmoji(0);}else{a(".arrowchat_smiley_button2").hide();a(".arrowchat_smiley_button").hide()}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					userid: u_id,
					focus_chat: id,
					tab_alert: tba
				}, function () {});
				a("#arrowchat_userlist_recent_" + id + " .list-item__title").removeClass('new-message-bold');
				a("#arrowchat_userlist_recent_" + id + " .list-item__subtitle").removeClass('new-message-bold');
				j = id;
				document.querySelector('#myNavigator').removeEventListener('postpush', myListener);
			});
		}
		function stripslashes(str) {
			str=str.replace(/\\'/g,'\'');
			str=str.replace(/\\"/g,'"');
			str=str.replace(/\\0/g,'\0');
			str=str.replace(/\\\\/g,'\\');
			return str;
		}
		function scrollOnPage2() {
			a('#private-chat-page .page__content').animate({scrollTop: 5E4}, 0);
		}
		function scrollOnPage3() {
			a('#chatroom-chat-page .page__content').animate({scrollTop: 5E4}, 0);
		}
		function updateChatTabTotal() {
			var private_total = 0;
			var chatroom_total = 0;
			var total = 0;
			for (var i in message_count) { 
				private_total += message_count[i];
				
				if (a("#arrowchat_userlist_"+i).length != "0") {
					a("#arrowchat_userlist_"+i+" .notification").html(message_count[i]);
					
					if (message_count[i] > 0)
						a("#arrowchat_userlist_"+i+" .notification").show();
					else
						a("#arrowchat_userlist_"+i+" .notification").hide();
				}
			}
			
			for (var i in message_chatroom_count) { 
				chatroom_total += message_chatroom_count[i];
			}
			
			total = chatroom_total + private_total;
			
			if (total > 0) {
				if (a("#recent-chat-tab .tabbar__button .notification").length == 0)
					a("#recent-chat-tab .tabbar__button").append('<div class="tabbar__badge notification">'+total+'</div>');
				else {
					a("#recent-chat-tab .tabbar__button .notification").html(total).show();
				}
			} else {
				a("#recent-chat-tab .tabbar__button .notification").html('0').hide();
			}
			
			if (total > 0) {
				a("#private-chat-back-notification").html(total).show();
				a("#chat-room-back-notification").html(total).show();
			} else {
				a("#private-chat-back-notification").html('').hide();
				a("#chat-room-back-notification").html('').hide();
			}
		}
		
		function getRandomColor(name) {
			const firstAlphabet = name.slice(0, 2).toLowerCase();
			const asciiCode = firstAlphabet.charCodeAt(0);
			const colorNum = asciiCode.toString() + asciiCode.toString() + asciiCode.toString();
			var num = Math.round(0xffffff * parseInt(colorNum));
			var r = num >> 16 & 255;
			var g = num >> 8 & 255;
			var b = num & 255;

			return {
				color: 'rgb(' + r + ', ' + g + ', ' + b + ', 0.7)',
				character: firstAlphabet.toUpperCase()
			};
		}
		
		function receiveMessage(id, from, message, sent, self, old, multi_tab, pending_msg) {
			if (pending_msg == 0 && self == 1 && multi_tab == 0) {
				return false;
			}
			aa[from] != 1 && I(from, uc_name[from], uc_status[from], uc_avatar[from], uc_link[from], uc_message[from], 0, 1);
			if (pending_msg == 0 && self != 1) {
				var data_array = [id, from, message, sent, self, old];
				acsi > 2 && lsClick(JSON.stringify(data_array), 'private_message');
			}
			if (j != from) {
				a("#arrowchat_userlist_recent_" + from + " .list-item__title").addClass('new-message-bold');
				a("#arrowchat_userlist_recent_" + from + " .list-item__subtitle").addClass('new-message-bold');
				var resort = a("#arrowchat_userlist_recent_" + from).clone(true);
				a("#arrowchat_userlist_recent_" + from).remove();
				a("#buddylist-container-recent").prepend(resort);
			}
			var c = "",
			sender_avatar = '',
			arrow = '',
			ma = id;
			clearTimeout(dtit3);
			a("#no-recent-convo").remove();
			if (uc_name[from] == null || uc_name[from] == "") setTimeout(function () {
				receiveMessage(id, from, message, sent, self, old, multi_tab, pending_msg)
			}, 500);
			else {
				if (j != from && self != 1 && multi_tab == 0) {
					if (typeof message_count[from] == "undefined")
						message_count[from] = 1;
					else
						message_count[from]++;
				}
				receiveNotTyping(from);
				if (a('#private-chat-page .page__content').length > 0) {
					var container = a('#private-chat-page .page__content')[0].scrollHeight - a('#private-chat-page .page__content').scrollTop() - 100;
					var container2 = a('#private-chat-page .page__content').outerHeight();
				} else {
					var container = 0;
					var container2 = 1;
				}
				var o = uc_name[from];
				if (uc_status[from] == "offline") {
					loadBuddyList();
				}
				f = "";
				if (self == 1) {
					fromname = u_name;
					fromid = u_id;
					f = " arrowchat_self";
					_aa5 = _aa4 = "";
					sender_avatar = u_avatar;
					arrow = 'send';
					var pending_icon = "<div class='arrowchat_pending_icon'></div>";
					if (multi_tab == 1) {
						pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
					}
				} else {
					DTitChange(uc_name[from]);
					fromname = o;
					fromid = from;
					_aa4 = '<a target="_blank" href="' + uc_link[from] + '">';
					_aa5 = "</a>";
					sender_avatar = uc_avatar[from];
					arrow = 'from';
					var pending_icon = "";
					
					a(".arrowchat_pending_icon:not(.arrowchat_pending_error)", "#arrowchat_user_" + from).hide();
					a("div .arrowchat_pending_icon:last", "#arrowchat_user_" + from).show();
				}
				var pending_class = "";
				var pending_class_id = "";
				if (pending_msg != 0) {
					pending_class = " arrowchat_pending_msg";
					pending_class_id = ' arrowchat_pending_msg_' + pending_msg;
				}
				var full_name = fromid;
				var image_msg = "";
				var show_time_class = "";
				message = stripslashes(message);
				message = youTubeEmbed(message);
				message = replaceURLWithHTMLLinks(message);
				message = smileyreplace(message);
				if (message.substr(0, 4) == "<div") {
					image_msg = " arrowchat_image_msg";
				}
				if (last_sent[from] == null || sent - last_sent[from] > 180) {
					show_time_class = " arrowchat_show_time";
				}
				if (a("#arrowchat_message_" + id).length > 0 && pending_msg == 0) {
					a("#arrowchat_message_" + id + " .arrowchat_chatboxmessagecontent").html(message);
				} else {
					o = new Date(sent * 1E3);
					if (c_show_full_name != 1) {
						if (fromname.indexOf(" ") != -1) fromname = fromname.slice(0, fromname.indexOf(" "));
					}
					if (id != 0) {
						var id_element = ' id="arrowchat_message_' + id + '" ';
					} else {
						var id_element = "";
					}
					
					var noAvatarColor = getRandomColor(fromname);
					c += '<div class="arrowchat_ts_wrapper' + show_time_class + '">' + ha(o) + '</div><div class="arrowchat_message_wrapper arrowchat_clearfix' + f + image_msg + pending_class + pending_class_id + '" ' + id_element + '><div class="arrowchat_sender_avatar arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_small">' + noAvatarColor["character"] + '</span><img alt="' + fromname + '" src="' + sender_avatar + '" /></div><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0">' + message + '</div></div>' + pending_icon + '</div>';
					N = full_name;
					za(from, c);
					
					if (a("#arrowchat_message_" + last_id[from]).length && self != 1) {
						a("#arrowchat_message_" + last_id[from]).children('.arrowchat_sender_avatar').addClass('arrowchat_single_avatar_hide');
					}
					
					last_sent[from] = sent;
					last_name[from] = fromid;
					
					if (pending_msg == 0) {
						last_id[from] = id;
					}
				}
				if (container <= container2) {
					scrollOnPage2();
					a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
						a('#page2').bind('pageshow',function(){scrollOnPage2();});
						scrollOnPage2();
					}).each(function() {
						if(this.complete) a(this).trigger('load');
					});
				} else {
					showToast(lang[134], 3000);
				}
			}
			setAvatarVisibility();
			if (a("#arrowchat_userlist_recent_" + from).length > 0) {
				a("#arrowchat_userlist_recent_" + from + " .list-item__subtitle").html(processMessage(message));
			}
		}
		function receiveTyping(id) {
			var container = 0,container2 = 0;
			if (a('#private-chat-page .page__content').length != 0) {
				container = a('#private-chat-page .page__content')[0].scrollHeight - a('#private-chat-page .page__content').scrollTop() - 100;
				container2 = a('#private-chat-page .page__content').outerHeight();
			}
			if (a("#arrowchat_typing_message_" + id, "#arrowchat_user_" + id).length) {
				a("#arrowchat_typing_message_" + id, "#arrowchat_user_" + id).remove();
			}
			if (a("#arrowchat_user_" + id).length) {
				var noAvatarColor = getRandomColor(uc_name[id]);
				a("#arrowchat_user_" + id).append('<div class="arrowchat_message_wrapper arrowchat_clearfix" id="arrowchat_typing_message_' + id + '"><div class="arrowchat_sender_avatar arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_small">' + noAvatarColor["character"] + '</span><img alt="" src="' + uc_avatar[id] + '"></div><div class="arrowchat_chatboxmessage"><div class="arrowchat_chatboxmessagecontent" style="margin-left:0"><div class="arrowchat_is_typing arrowchat_is_typing_chat"><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div></div></div></div></div>');
				if (container <= container2) {
					scrollOnPage2();
				}
				clearTimeout(typingTimeout);
				typingTimeout = setTimeout(function () {
					receiveNotTyping(id)
				}, 30000);
			}
			if (a("#arrowchat_userlist_recent_" + id).length > 0) {
				a("#arrowchat_userlist_recent_" + id + " .list-item__subtitle").html('<div class="arrowchat_is_typing_list"><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div></div>');
			}
			setAvatarVisibility();
		}
		function receiveWarning(h) {
			if (h.read == 0 && h.data != "") {
				ons.createElement('warnings-page', { append: true }).then(function(dialog) {
					a(".warning-reason").html(h.data);
					dialog.show();
					a(".warnings_close").unbind('click');
					a(".warnings_close").click(function () {
						dialog.hide();
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							warning_read: 1
						}, function () {});
					});
				});
			}
		}
		function receiveNotTyping(id) {
			if (a("#arrowchat_user_" + id).length) {
				clearTimeout(typingTimeout);
				if (a("#arrowchat_typing_message_" + id).length) {
					a("#arrowchat_typing_message_" + id).remove();
				}
			}
			if (a("#arrowchat_userlist_recent_" + id).length > 0) {
				a("#arrowchat_userlist_recent_" + id + " .list-item__subtitle").html(processMessage(uc_message[id]));
			}
		}
		function pushSubscribe() {
			if (c_push_engine == 1) {
				push_uid = push.subscribe(c_push_encrypt + "_u"+u_id);

				push_uid.on('data', function (data) {
					pushReceive(data);
				});
			}
		}
		function pushReceive(data) {
			if ("warning" in data) {
				receiveWarning(data.warning);
			}
			if ("typing" in data) {
				receiveTyping(data.typing.id);
			}
			if ("nottyping" in data) {
				receiveNotTyping(data.nottyping.id);
			}
			if ("messages" in data) {
				receiveMessage(data.messages.id, data.messages.from, data.messages.message, data.messages.sent, data.messages.self, data.messages.old, 0, 0);
				updateChatTabTotal();
				data.messages.self != 1 && u_sounds == 1 && !a("#textinput1").is(":focus") && Ua();
				showTimeAndTooltip();
				K = 1;
				D = E;
			}
			if ("chatroommessage" in data) {
				if (typeof(blockList[data.chatroommessage.userid]) == "undefined")
				{
					addChatroomMessage(data.chatroommessage.id, data.chatroommessage.name, data.chatroommessage.message, data.chatroommessage.userid, data.chatroommessage.sent, data.chatroommessage.global, data.chatroommessage.mod, data.chatroommessage.admin, data.chatroommessage.chatroomid);
					updateChatTabTotal();
					if (data.chatroommessage.name != 'Delete' && data.chatroommessage.global != 1) {
						if (data.chatroommessage.userid != u_id) {
							u_chatroom_sound == 1 && !a("#textinput2").is(":focus") && Ua();
						}
					}
				}
			}
		}
		function addChatroomMessage(id, name, message, userid, sent, global, mod, admin, chatroomid) {
			if (userid == u_id) {
				uc_avatar[u_id] = u_avatar;
			}
			message = stripslashes(message);
			message = replaceURLWithHTMLLinks(message);
			var sent_time = new Date(sent * 1E3);
			if (typeof(uc_avatar[userid]) == "undefined") {
				a.ajax({
					url: c_ac_path + "includes/json/receive/receive_user.php?_="+new Date().getTime(),
					data: {
						userid: userid
					},
					type: "post",
					cache: false,
					dataType: "json",
					success: function (data) {
						if (data) {
							uc_avatar[userid] = data.a;
							chatroomDiv(id, uc_avatar[userid], name, sent_time, message, global, mod, admin, userid, chatroomid);
						}
					}
				});
			} else {
				chatroomDiv(id, uc_avatar[userid], name, sent_time, message, global, mod, admin, userid, chatroomid);
			}
			count++;
		}

		function chatroomDiv(id, image, name, time, message, global, mod, admin, userid, chatroomid) {
			if (userid == u_id && global == 0) {
				return false;
			}
			if (Ccr != chatroomid && global != 1) {
				a("#arrowchat_chatroom_joined_" + chatroomid + " .list-item__title").addClass('new-message-bold');
				a("#arrowchat_chatroom_joined_" + chatroomid + " .list-item__subtitle").addClass('new-message-bold');
				
				if (typeof message_chatroom_count[chatroomid] == "undefined")
					message_chatroom_count[chatroomid] = 1;
				else
					message_chatroom_count[chatroomid]++;
			} else {
				var container = 0,container2 = 0;
				if (a('#chatroom-chat-page .page__content').length != 0) {
					container = a('#chatroom-chat-page .page__content')[0].scrollHeight - a('#chatroom-chat-page .page__content').scrollTop() - 100;
					container2 = a('#chatroom-chat-page .page__content').outerHeight();
				}
				var title = "", l = "", important = "", image_msg = "";
				if (mod == 1) {
					title = lang[137];
					important = " arrowchat_chatroom_important";
				}
				if (admin == 1) {
					title = lang[136];
					important = " arrowchat_chatroom_important";
				}
				if (userid == u_id) {
					l = " arrowchat_self";
				}
				if (message.substr(0, 4) == "<div") {
					image_msg = " arrowchat_image_msg";
				}
				a(".arrowchat_pending_icon:not(.arrowchat_pending_error)", "#chatroom-chat-page").hide();
				a("div .arrowchat_pending_icon:last", "#chatroom-chat-page").show();
				var regex = new RegExp('(^|\\s)(@' + u_name + ')(\\s|$)', 'i');
				message = message.replace(regex, '$1<span class="arrowchat_at_user">$2</span>$3');
				if (a("#arrowchat_chatroom_message_" + id).length > 0) {
					a("#arrowchat_chatroom_message_" + id + " .arrowchat_chatroom_msg").html(message);
					if (userid == u_id) {
						a("#arrowchat_chatroom_message_" + id).addClass(l);
					}
				} else {
					if (global == 1) {
						a("<div/>").addClass("arrowchat_message_wrapper").addClass("arrowchat_clearfix").html('<div class="arrowchat_chatroom_box_message arrowchat_chatroom_global" id="arrowchat_chatroom_message_' + id + '"><div class="arrowchat_chatroom_message_content arrowchat_global_chatroom_message">' + message + "</div></div>").appendTo(a(".arrowchat_chatroom_chat_content"));
						receiveChatroom();
					} else {
						var noAvatarColor = getRandomColor(name);
						a("<div/>").attr("id", "arrowchat_chatroom_message_" + id).addClass(important).addClass(image_msg).addClass(l).addClass("arrowchat_message_wrapper").html('<div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + name + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_sender_avatar arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_small">' + noAvatarColor["character"] + '</span><img alt="' + name + '" src="' + image + '" /></div><div class="arrowchat_chatroom_box_message"><div class="arrowchat_chatroom_message_content"><span class="arrowchat_chatroom_msg">' + message + '</span></div></div></div>').appendTo(a(".arrowchat_chatroom_chat_content"));
					}
				}
				
				if (container <= container2) {
					a(".arrowchat_chatroom_msg>div>img,.arrowchat_emoji_text>img").one("load", function() {
						a('#page3').bind('pageshow',function(){scrollOnPage3();});
						scrollOnPage3();
					}).each(function() {
						if(this.complete) a(this).trigger('load');
					});
					scrollOnPage3();
				} else {
					showToast(lang[134], 3000);
				}
				setAvatarVisibility();
				showTimeAndTooltip();
			}
		}
		function receiveBlockList() {
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_block_list.php?_="+new Date().getTime(),
				type: "get",
				cache: false,
				dataType: "json",
				success: function (b) {
					if (b && b != null) {
						a("#unblock-mobile").children('select').html("");
						a("<option/>").attr("value", "0").html(lang[118]).appendTo(a("#unblock-mobile").children('select'));
						a.each(b, function (e, l) {
							a.each(l, function (f, h) {
								a("<option/>").attr("value", h.id).html(h.username).appendTo(a("#unblock-mobile").children('select'));
							});
						});
					}
				}
			});
		}
		function getChatroomSettings() {
			a("#chatroom-settings-image").prop('src', c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[Ccr]);
			a("#chatroom-settings-name").html(cr_name[Ccr]);
			
			if (u_chatroom_block_chats == 1) {
				a('#chatroom-flip-block-private').prop('checked', true);
			} else {
				a('#chatroom-flip-block-private').prop('checked', false);
			}
			
			if (u_chatroom_invisible == 1) {
				a('#chatroom-flip-is-invisible').prop('checked', true);
			} else {
				a('#chatroom-flip-is-invisible').prop('checked', false);
			}
			
			if (u_chatroom_sound == 1) {
				a('#chatroom-flip-sounds').prop('checked', true);
			} else {
				a('#chatroom-flip-sounds').prop('checked', false);
			}
			
			if (chatroom_admin[Ccr] == 1 || chatroom_mod[Ccr] == 1) {
				a("#change-description").css('display', 'block');
				a("#change-welcome").css('display', 'block');
				if (chatroom_admin[Ccr] == 1) {
					a("#chatroom-is-invisible").css('display', 'flex');
				} else {
					a("#chatroom-is-invisible").hide();
				}
				a("#chatroom-settings-dialog .settings-name-wrapper").addClass('chat-admin-controls');
			} else {
				a("#change-description").hide();
				a("#change-welcome").hide();
				a("#chatroom-is-invisible").hide();
				a("#chatroom-settings-dialog .settings-name-wrapper").removeClass('chat-admin-controls');
			}
			
			window.fn.showDescriptionPrompt = function() {
				ons.notification.prompt(lang[287], {placeholder:cr_desc[Ccr],buttonLabels:lang[282]}).then(function(input) {
					if (input != "") {
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							chatroom_description: input,
							chatroom_id: Ccr
						}, function (data) {
							showToast(lang[155], 3000);
							cr_desc[Ccr] = input;
							a("#chatroom-header #status-header").html(cr_desc[Ccr]);
						});
					}
				});
			};
			
			window.fn.showWelcomePrompt = function() {
				ons.notification.prompt(lang[288], {placeholder:cr_welcome[Ccr],buttonLabels:lang[282]}).then(function(input) {
					if (input != "") {
						a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
							chatroom_welcome_msg: input,
							chatroom_id: Ccr
						}, function (data) {
							showToast(lang[155], 3000);
							cr_welcome[Ccr] = input;
						});
					}
				});
			};
			
			a("#chatroom-flip-is-invisible").bind("change", function() {
				if (a("#chatroom-flip-is-invisible").prop("checked")) {
					_chatroominvis = 1;
				} else {
					_chatroominvis = -1;
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					chatroom_invisible: _chatroominvis
				}, function () {
				});
			});
			
			a("#chatroom-flip-block-private").bind("change", function() {
				if (a("#chatroom-flip-block-private").prop("checked")) {
					_chatroomblock = 1;
				} else {
					_chatroomblock = -1;
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					chatroom_block_chats: _chatroomblock
				}, function () {
				});
			});
			
			a("#chatroom-flip-sounds").bind("change", function() {
				if (a("#chatroom-flip-sounds").prop("checked")) {
					_chatroomsound = 1;
					u_chatroom_sound = 1;
				} else {
					_chatroomsound = -1;
					u_chatroom_sound = 0;
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					chatroom_sound: _chatroomsound
				}, function () {
				});
			});
			a("#chatroom-leave-button").click(function() {
				document.getElementById('chatroom-settings-dialog').hide();
				leaveChatroom(Ccr);
				a("#chatroom-chat-page .back-button").click();
			});
		}
		
		function leaveChatroom(id) {
			a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
				close_chat: 'r'+ id
			}, function () {});
			a("#arrowchat_chatroom_joined_" + id).hide();
			var chatroomCount = 0;
			a(".chatroom_joined").each(function() {
				if (a(this).is(":visible")) {
					chatroomCount++;
				}
			});
			if (chatroomCount == 0) {
				clearTimeout(Crref2);
				Crref2 = -1;
			}
			if (c_push_engine != 1) {
				cancelJSONP();
				receiveCore();
			}
			changePushChannel(id, 0);
		}
		
		function getSettingsCookies() {
			a("#settings-image").prop('src', u_avatar);
			a("#settings-name").html(u_name);
			var noAvatarColor = getRandomColor(u_name);
			a(".settings-image-wrapper").css("background-color", noAvatarColor["color"]);
			a(".settings-image-wrapper .arrowchat_tab_letter").html(noAvatarColor["character"]);
			setAvatarVisibility();
			u_is_guest == 1 && c_guest_name_change == 1 && u_guest_name == "" && a("#change-name").show();
			window.fn.showNamePrompt = function() {
				ons.notification.prompt(lang[289], {buttonLabels:lang[282]}).then(function(input) {
					a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
						chat_name: input
					}, function (data) {
						if (data != "1") {
							showToast(lang[290] + data, 5000);
						} else {
							a("#change-name").hide();
							u_name = input;
							a("#settings-name").html(input);
							showToast(lang[291], 3000);
						}
					});
				});
			};
			a("#change-name").keydown(function (h) {
				if (h.keyCode == 13) {
					a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
						chat_name: a(this).val()
					}, function (data) {
						if (data != "1") {
							a("#user-error-content").html(data);
						} else {
							a("#change-name-wrapper").hide();
							u_name = a(this).val();
						}
					});
				}
			});
			a('#unblock-mobile').on('change', function() {
				var unblock_chat_user = a("#unblock-mobile").val();
				if (unblock_chat_user != "0") {
					a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
						unblock_chat: unblock_chat_user
					}, function () {
						if (typeof(blockList[unblock_chat_user]) != "undefined") {
							blockList.splice(unblock_chat_user, 1);
						}
						loadBuddyList();
						receiveBlockList();
						showToast(lang[292], 3000);
					});
				}
			});
			if (u_sounds == 1) {
				a('#flip-chat-sounds').prop('checked', true);
			} else {
				a('#flip-chat-sounds').prop('checked', false);
			}
			if (u_no_avatars == 1) {
				a('#flip-disable-avatars').prop('checked', true);
			} else {
				a('#flip-disable-avatars').prop('checked', false);
			}
			if (a.cookie('ac_hide_mobile') == 1) {
				a('#flip-hide-mobile').prop('checked', true);
			} else {
				a('#flip-hide-mobile').prop('checked', false);
			}
			a("#flip-hide-mobile").bind("change", function() {
				if (a("#flip-hide-mobile").prop("checked")) {
					a.cookie('ac_hide_mobile', 1, { path: '/' });
				} else {
					a.cookie('ac_hide_mobile', 0, { path: '/' });
				}
			});
			a("#flip-chat-sounds").bind("change", function() {
				if (a("#flip-chat-sounds").prop("checked")) {
					_soundcheck = 1;
					u_sounds = 1;
				} else {
					_soundcheck = -1;
					u_sounds = 0;
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					sound: _soundcheck
				}, function () {
				});
			});
			a("#flip-disable-avatars").bind("change", function() {
				if (a("#flip-disable-avatars").prop("checked")) {
					_namecheck = 1;
					u_no_avatars = 1;
					setAvatarVisibility();
				} else {
					_namecheck = -1;
					u_no_avatars = 0;
					setAvatarVisibility();
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php?_="+new Date().getTime(), {
					name: _namecheck
				}, function () {
				});
			});
		}
		function chatroomPasswordClick() {
			a("#password-page-input").val('');
			a("#password-page-input").keypress(function (e) {
				if (e.which == 13) {
					fn.hideDialog('password-dialog');
					loadChatroom(Ccr, cr_type[Ccr], a("#password-page-input").val());
					addChatroomJoined(Ccr, false);
				}
			});
			a("#chatroom-submit-password").click(function() {
				fn.hideDialog('password-dialog');
				loadChatroom(Ccr, cr_type[Ccr], a("#password-page-input").val());
				addChatroomJoined(Ccr, false);
			});
			a("#chatroom-cancel-password").click(function() {
				fn.hideDialog('password-dialog');
			});
		}
		function chatroomCreateClick() {
			a("#create-page-input").val('');
			a("#create-page-input").keypress(function (e) {
				if (e.which == 13) {
					fn.hideDialog('create-dialog');
					startCreateChatRoom();
				}
			});
			a("#chatroom-submit-create").click(function() {
				fn.hideDialog('create-dialog');
				startCreateChatRoom();
			});
			a("#chatroom-cancel-create").click(function() {
				fn.hideDialog('create-dialog');
			});
		}
		function startCreateChatRoom() {
			var i = a("#create-page-input").val();
			var passinput = a("#create-password-page-input").val();
			a("#create-page-input").val("");
			a("#create-password-page-input").val("");
			i = i.replace(/^\s+|\s+$/g, "");
			i != "" && a.post(c_ac_path + "includes/json/send/send_chatroom_create.php", {
				userid: u_id,
				name: i,
				password: passinput,
				description: '',
				welcome: ''
			}, function (e) {
				if (e) {
					if (e == "-1") {
						showToast(lang[39], 5000);
					} else if (e == "-2") {
						showToast(lang[40], 5000);
					} else {
						chatroomreceived = 0;
						loadChatroomList();
					}
				}
			});
		}
		function changeGuestName() {
			function guestNameInput() {
				var new_name = a("#change-name-page-input").val();
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					chat_name: new_name
				}, function (data) {
					if (data != "1") {
						if (data == lang[123]) {
							showToast(data, 5000);
							fn.hideDialog('change-name-dialog');
						} else {
							showToast(data, 5000);
						}
					} else {
						fn.hideDialog('change-name-dialog');
						u_name = new_name;
						u_guest_name = new_name;
						a("#settings-name2").html(new_name);
					}
				});
			}
			a("#change-name-page-input").keydown(function (h) {
				if (h.keyCode == 13) {
					guestNameInput();
				}
			});
			a("#arrowchat_guest_name_button").click(function() {
				guestNameInput();
			});
		}
		function settingsButtonClick() {
			a(".arrowchat_search_not_found").remove();
			a("#search-page-input").val('');
			a("#search-page-input").on('keyup input', function (e) {
				a("#search-buddylist-list").html('');
				if (typeof(searchxhr) != "undefined") searchxhr.abort();
				a(".arrowchat_search_not_found").remove();
				a("<div/>").attr("class", "arrowchat_search_not_found arrowchat_nofriends").html('<ons-progress-circular indeterminate></ons-progress-circular>').prependTo("#search-buddylist");
				clearTimeout(Z);
				var i = 0,
					c = "",
					d = "",
					f = a(this).val();
				if (f.length < 2) {
					a(".search-empty").show();
					a(".arrowchat_search_not_found").remove();
					if (buddylisttest == 2) loadBuddyList();
					buddylisttest = 1;
				} else {
					a(".search-empty").hide();
					buddylisttest = 2;
					var avatar = "";
					searchxhr = a.ajax({
						url: c_ac_path + "includes/json/receive/receive_search.php?_="+new Date().getTime(),
						type: "post",
						cache: false,
						dataType: "json",
						data: {
							search_name: f
						},
						success: function (b) {
							if (b && b != null) {
								a.each(b, function (e, l) {
									a.each(l, function (f, h) {
										status_icon = "";
										status_color = "#42b72a";
										longname = renderHTMLString(h.name);
										if (typeof(uc_avatar[h.id]) != "undefined")
											avatar = '<img class="list-item__thumbnail" src="'+uc_avatar[h.id]+'" />';
										else
											avatar = '<img class="list-item__thumbnail" src="'+h.avatar+'" />';
										if (h.status == "available" || h.status == "offline") {
											status_icon = "circle";
											if (h.status == "offline")
												status_color = "#cecece";
										} else if (h.status == "busy") {
											status_icon = "mobile-alt";
										} else {
											status_icon = "moon";
											status_color = "#5485e8";
										}
										var noAvatarColor = getRandomColor(longname);
										c += '<ons-list-item id="arrowchat_userlist_search_' + h.id + '" class="user-window"><div class="left" style="position:relative;"><div class="buddylist-avatar-wrapper buddylist-avatar-wrapper-xsmall arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span>' + avatar + '</div><div class="online-list-icon"><ons-icon size="12px" style="color: '+status_color+'" icon="fa-'+status_icon+'"></ons-icon></div></div><div class="center">' +longname+ '</div></ons-list-item>';
										i++;
										uc_name[h.id] = h.name;
										uc_status[h.id] = h.status;
										if (typeof uc_avatar[h.id] == "undefined")
											uc_avatar[h.id] = h.avatar;
										if (typeof uc_link[h.id] == "undefined")
											uc_link[h.id] = '#';
										if (typeof uc_message[h.id] == "undefined")
											uc_message[h.id] = lang[245];
									});
								});
								if (i == 0) {
									a(".arrowchat_search_not_found").html(lang[26]);
								} else {
									a(".arrowchat_search_not_found").remove();
									a("#search-buddylist-list").html(c);
									a(".user-window").unbind("click");
									a(".user-window").click(function (l) {
										var id = a(this).attr("id");
										a("#search-dialog").hide(0, function() {
											loadBuddyList();
											Satwo(id);
										});
									});
								}
								setAvatarVisibility();
							}
						}
					});
				}
			});
		}
		function recentTabLoading() {
			if (u_is_guest == 1 && c_guest_name_change == 1 && u_guest_name == "") {
				fn.showChangeNameDialog();
			}
			if (recentTabLoaded == 0) {
				recentTabLoaded = 1;
				var recent_chat = false;
				var chatrooms_exist = false;
				var hash_tag = window.location.hash;
				var hash = false, hash_room = false;
				if (hash_tag.search(/#chatwith-/) >= 0) {
					hash = true;
					hash_tag = hash_tag.replace('#chatwith-', '');
				}
				if (hash_tag.search(/#room-/) >= 0) {
					hash_room = true;
					hash_tag = hash_tag.replace('#room-', '');
				}
				
				for (var d = 0; d < unfocus_chat.length; d++) {
					if (typeof unfocus_chat[d] != "undefined") {
						if (unfocus_is_room[d] == "1") {
							if (c_chatrooms != 0) {
								init_open_room[unfocus_chat[d]] = 1;
								changePushChannel(unfocus_chat[d], 1);
								addChatroomJoined(unfocus_chat[d], false);
								chatrooms_exist = true;
							}
						} else {
							I(unfocus_chat[d], uc_name[unfocus_chat[d]], uc_status[unfocus_chat[d]], uc_avatar[unfocus_chat[d]], uc_link[unfocus_chat[d]], uc_message[unfocus_chat[d]], "0");
						}
						recent_chat = true;
					}
				}
				for (var d = 0; d < focus_chat.length; d++) {
					if (typeof(focus_chat[d] != "undefined")) {
						if (focus_is_room[d] == "1") {
							if (c_chatrooms != 0) {
								init_open_room[focus_chat[d]] = 1;
								changePushChannel(focus_chat[d], 1);
								addChatroomJoined(focus_chat[d], false);
								chatrooms_exist = true;
							}
						} else {
							I(focus_chat[d], uc_name[focus_chat[d]], uc_status[focus_chat[d]], uc_avatar[focus_chat[d]], uc_link[focus_chat[d]], uc_message[focus_chat[d]], "0");
						}
						recent_chat = true;
					}
				}
				if (chatrooms_exist) {
					receiveChatroom();
				}
				if (!recent_chat && !(a("#buddylist-container-recent ons-list-item").length > 0)) {
					a("#recent-loader").remove();
					a("#no-recent-convo").remove();
					a("#buddylist-container-recent").parent().append('<p id="no-recent-convo">'+lang[283]+'<br /><br /><ons-button id="start-chatting-button">'+lang[284]+'</ons-button></p>');
					a('#start-chatting-button').unbind('click');
					a('#start-chatting-button').click(function() {
						a('[page="online-users"]').click();
					});
				}
				a("#add-icon").click(function() {
					if (a(this).attr('icon') == "fa-pen-to-square") {
						fn.showSearchDialog();
						a(".search-empty").show();
						a(".arrowchat_search_not_found").remove();
						a("#search-page-input").val('');
						a("#search-buddylist-list").html('');
					} else if (a(this).attr('icon') == "fa-circle-plus") {
						fn.createDialog();
					}
				});
				var noAvatarColor = getRandomColor(u_name);
				a("#settings-image2").prop('src', u_avatar);
				a(".settings-image-wrapper").css("background-color", noAvatarColor["color"]);
				a(".settings-image-wrapper .arrowchat_tab_letter").html(noAvatarColor["character"]);
				a("#settings-open-image").prop('src', u_avatar);
				a(".settings-open").css("background-color", noAvatarColor["color"]);
				a(".settings-open .arrowchat_tab_letter").html(noAvatarColor["character"]);
				a("#settings-name2").html(u_name);
				if (hash) {
					if (a.isNumeric(hash_tag) && hash_tag != u_id) {
						if (a("#arrowchat_userlist_recent_" + hash_tag).length) {
							a("#arrowchat_userlist_recent_" + hash_tag).click();
						} else {
							I(hash_tag, uc_name[hash_tag], uc_status[hash_tag], uc_avatar[hash_tag], uc_link[hash_tag], uc_message[hash_tag], 1);
						}
					}
				}
				if (hash_room && !hash) {
					if (a.isNumeric(hash_tag)) {
						if (a("#arrowchat_chatroom_joined_" + hash_tag).length) {
							a("#arrowchat_chatroom_joined_" + hash_tag).click();
						} else {
							if (c_chatrooms != 0) {
								init_open_room[hash_tag] = 1;
								changePushChannel(hash_tag, 1);
								addChatroomJoined(hash_tag, true);
								if (!chatrooms_exist) {
									receiveChatroom();
								}
							}
						}
					}
				}
			}
		}
		function onlineTabLoading() {
			var pullHook = document.getElementById('pull-hook');
			var icon = document.getElementById('pull-hook-icon');
			pullHook.addEventListener('changestate', function (event) {
			  switch (event.state) {
				case 'initial':
				  icon.setAttribute('icon', 'fa-arrow-down');
				  icon.removeAttribute('rotate');
				  icon.removeAttribute('spin');
				  break;
				case 'preaction':
				  icon.setAttribute('icon', 'fa-arrow-down');
				  icon.setAttribute('rotate', '180');
				  icon.removeAttribute('spin');
				  break;
				case 'action':
				  icon.setAttribute('icon', 'fa-spinner');
				  icon.removeAttribute('rotate');
				  icon.setAttribute('spin', true);
				  break;
			  }
			});
			pullHook.onAction = function (done) {
				a.when(loadBuddyList()).done(function() {
					setTimeout(function() {
						done();
					}, 400);
				});
			}
		}
		function addChatroomJoined(id, open) {
			a("#recent-loader").remove();
			a("#no-recent-convo").remove();
			if (typeof $chatroom_tab[id] == "undefined") {
				if (typeof(cr_name[id]) == "undefined")
					cr_name[id] = lang[301];
				if (typeof(cr_desc[id]) == "undefined")
					cr_desc[id] = lang[301];
				if (typeof(cr_img[id]) == "undefined")
					cr_img[id] = "chatroom_default.png";
				if (typeof(cr_count[id]) == "undefined") {
					cr_count[id] = 0;
				}
				
				$chatroom_tab[id] = a("<ons-carousel/>").attr("id", "arrowchat_chatroom_joined_" + id).addClass("chatroom_joined").attr("swipeable", "true").attr("auto-scroll-ratio", "0.2").attr("auto-scroll", "true").html('<ons-carousel-item><ons-list-item modifier="nodivider"><div class="left" style="position:relative"><img class="list-item__thumbnail" src="' + c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[id] + '" /><div class="online-list-icon"><div class="chatroom_count tabbar__badge notification">' + cr_count[id] + '</div></div></div><div class="center"><span class="list-item__title">' + cr_name[id] + '</span><span class="list-item__subtitle">' + cr_desc[id] + '</span></div><div class="right chatroom_list_right"><span class="notification"></span></div></ons-list-item></ons-carousel-item><ons-carousel-item id="arrowchat_chatroom_leave_'+id+'" class="arrowchat_chatroom_leave">Leave</ons-carousel-item>').prependTo(a("#buddylist-container-recent"));

				if (a("#joined-header").length == 0) {
					//a("<ons-list-header/>").attr("id", "joined-header").addClass("list-header").html(lang[294]).prependTo(a("#buddylist-container-chatroom-joined"));
				}
				a("#arrowchat_chatroom_joined_"+id).unbind('click');
				a("#arrowchat_chatroom_joined_"+id).click(function (e) {
					if (e.target.id != "arrowchat_chatroom_leave_"+id) {
						a("#arrowchat_chatroom_joined_" + id + " .list-item__title").removeClass('new-message-bold');
						a("#arrowchat_chatroom_joined_" + id + " .list-item__subtitle").removeClass('new-message-bold');
						Ccr = id;
						Ccr2 = id;
						if (cr_type[id] == 2 && joinedChangePassword == 1) {
							fn.passwordDialog();
						} else {
							loadChatroom(id, cr_type[id]);
							addChatroomJoined(id, false);
						}
					}
				});
				a("#arrowchat_chatroom_leave_"+id).unbind('click');
				a("#arrowchat_chatroom_leave_"+id).click(function () {
					leaveChatroom(id);
				});
				if (open) {
					a("#arrowchat_chatroom_joined_" + id).click();
				}
			} else {
				a("#arrowchat_chatroom_joined_" + id).show();
			}
		}
		
		function lsClick(id, action, acvar) {
			if (lsreceive == 0) {
				var milliseconds = new Date().getTime();
				if (action == "ac_openclose") {
					if (typeof(acvar) == "undefined") {
						if (a(id).hasClass("arrowchat_tabclick"))
							localStorage.setItem("ac_close", id + "/" + milliseconds);
						else
							localStorage.setItem("ac_open", id + "/" + milliseconds);
					} else {
						eval("var temp=$"+acvar+";");
						if (id == " ") {
							if (temp.hasClass("arrowchat_tabclick"))
								localStorage.setItem("ac_close", id + "," + acvar + "/" + milliseconds);
							else
								localStorage.setItem("ac_open", id + "," + acvar + "/" + milliseconds);
						} else {
							if (a(id, acvar).hasClass("arrowchat_tabclick"))
								localStorage.setItem("ac_close", id + "," + acvar + "/" + milliseconds);
							else
								localStorage.setItem("ac_open", id + "," + acvar + "/" + milliseconds);
						}
					}
				} else if (action == "private_message" || action == "chatroom_message" || action == "chatroom_alerts" || action == "send_chatroom_message") {
					if (!msieversion()) {
						localStorage.setItem(action, id + "/##-" + milliseconds);
					}
				} else {
					if (!msieversion()) {
						if (typeof(acvar) == "undefined") {
							localStorage.setItem(action, id + "/" + milliseconds);
						} else
							localStorage.setItem(action, id + "," + acvar + "/" + milliseconds);
					}
				}
			}
		}
		
		function msieversion() {
			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");
			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))
				return true;

		   return false;
		}
		
		function localStorageFired(e) {
			lsreceive = 1;
			if (e.key == 'window_focus') {
				clearTimeout(dtit3);
				document.title = dtit;
			}
			if (e.key == 'private_message') {
				var res = e.newValue.split("/##-");
				var data = JSON.parse(res[0]);
				receiveMessage(data[0], data[1], data[2], data[3], data[4], 1, 1, 0);
			}
			if (e.key == 'send_chatroom_message') {
				var res = e.newValue.split("/##-");
				var data = JSON.parse(res[0]);
				var tester = data[3];
				if (tester.substring(0, 3) == "cr-")
					tester = tester.substr(3);
				addMessageToChatroom(data[0], data[1], data[2], 1, tester, 0);
				if (Ccr == tester) {
					scrollOnPage3();
					a(".arrowchat_chatroom_msg>div>img,.arrowchat_emoji_text>img").one("load", function() {
						a('#page3').bind('pageshow',function(){scrollOnPage3();});
						scrollOnPage3();
					}).each(function() {
						if(this.complete) a(this).trigger('load');
					});
				}
			}
			lsreceive = 0;
		}
		
		function renderHTMLString(string) {
			var render = a("<div/>").attr("id", "arrowchat_render").html(string).appendTo('body');
			var new_render = a("#arrowchat_render").html();
			render.remove();
			return new_render;
		}
		
        var bounce = 0,
            bounce2 = 0,
			buddylisttest = 1,
			lsreceive = 0,
			chatroom_mod = {},
			chatroom_admin = {},
			searchxhr,
            count = 0,
			joinedChangePassword = 0,
			message_timeout,
			typingTimeout,
            V = {},
			retain_ccr = 0,
            dtit = document.title,
            dtit2 = 1,
            dtit3, window_focus = true,
            xa = {},
			joinedLoaded = 0,
			chatroom_list = {},
            j = "",
            crou = "",
            $ = 0,
			copyTimeout = 0,
            w = 0,
            bli = 1,
			isAway = 0,
            chatroomreceived = 0,
            W = false,
            Y, Z, crtimeout, E = 3E3,
            Crref2 = -1,
			Ccr = -1,
			Ccr2 = 0,
            D = E,
            K = 1,
            ma = 0,
            R = 0,
            m = "",
            Ka = 0,
			scroll_last = 0,
			last_sent = {},
			last_id = {},
			last_name = {},
			pending_msg_count = 0,
			pending_msg_room_count = 0,
			init_open = {},
			init_open_room = {},
            y = {},
            G = {},
            aa = {},
            ca = {},
			sheet = {},
			history_ids = {},
			push_room = {},
			push_uid,
			message_count = [],
			message_chatroom_count = [],
			push_arrowchat,
			room_limit_msg = [],
			room_limit_sec = [],
            Aa = new Date,
            Na = Aa.getDate(),
            ab = Math.floor(Aa.getTime() / 1E3),
            acsi = 1,
            Q = 0,
			recentTabLoaded = 0,
			emoji_loaded = 0,
			emoji_loaded_chatroom = 0,
			premade_smiley = [],
            fa = -1,
            acp = "Powered By <a href='http://www.arrowchat.com/' target='_blank'>ArrowChat</a>",
            pa = 0,
			chatEventListener = 0,
            B, N;
		premade_smiley[0] = [':)','&#x1F642;'];
		premade_smiley[1] = [':-)','&#x1F642;'];
		premade_smiley[2] = ['=)','&#x1F642;'];
		premade_smiley[3] = [':p','&#x1F61B;'];
		premade_smiley[4] = [':o','&#x1F62E;'];
		premade_smiley[5] = [':|','&#x1F610;'];
		premade_smiley[6] = [':(','&#x2639;&#xFE0F;'];
		premade_smiley[7] = ['=(','&#x2639;&#xFE0F;'];
		premade_smiley[8] = [':D','&#x1F603;'];
		premade_smiley[9] = ['=D','&#x1F603;'];
		premade_smiley[10] = [':/','&#x1F615;'];
		premade_smiley[11] = ['=/','&#x1F615;'];
		premade_smiley[12] = [';)','&#x1F609;'];
		premade_smiley[13] = [':\'(','&#x1F622;'];
		premade_smiley[14] = ['<3','&#x2764;&#xFE0F;'];
		premade_smiley[15] = ['>:(','&#x1F621;'];
        var _ts = "",
            _ts2;
        for (d = 0; d < Themes.length; d++) {
            if (Themes[d][2] == u_theme) {
                _ts2 = "selected";
            } else {
                _ts2 = "";
            }
            _ts = _ts + "<option value=\"" + Themes[d][0] + "\" " + _ts2 + ">" + Themes[d][1] + "</option>";
        }
        arguments.callee.videoWith = function (b) {
			var win = window.open(c_ac_path + 'public/video/?rid=' + b, 'audiovideochat', "status=no,toolbar=no,menubar=no,directories=no,resizable=no,location=no,scrollbars=no,width="+c_video_width+",height="+c_video_height+"");
            win.focus();
        };
		function buildMaintenance() {
			var language = lang[58];
			var extraHTML = "";
			if (c_chat_maintenance != 0 || c_db_connection == 1 || c_disable_arrowchat == 1)
				if (c_db_connection == 1)
					language = "We could not connect to the database. Please try again later.";
				else
					language = lang[27];
			else {
				if (c_login_url != "")
					extraHTML = '<div class="arrowchat_login_button_wrapper"><a target="_blank" class="arrowchat_login_button" href="' + c_login_url + '">' + lang[239] + '</a></div>';
			}
			a("body").html('<div style="text-align:center;padding-top:30px;font-size:16px;font-family:\'Open Sans\',Helvetica,Arial,sans-serif">' + language + '</div>' + extraHTML);
		}
		function Xa() {
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_init.php?_="+new Date().getTime(),
				cache: false,
				type: "get",
				dataType: "json",
				success: function (b) {}
			});
			if (c_chat_maintenance != 0 || c_db_connection == 1 || u_id == "" || c_disable_arrowchat == 1) {
				buildMaintenance();
			} else {
				if (u_id != "") {
					window.addEventListener('storage', localStorageFired, false);
					if (c_push_engine == 1) {
						push = new Scaledrone(c_push_publish);
					}
					if (c_push_engine == 1) {
						pushSubscribe();
					}
					receiveCore();
					if (a("#buddylist-container-available").length > 0) {
						loadBuddyList();
					} else {
						document.addEventListener('show', function(event) {
							var page = event.target;
							if (page.id === 'Tab1') {
								loadBuddyList();
							}
						});
					}
					if (a("#buddylist-container-chatroom").length > 0) {
						if (c_chatrooms == 1) {
							loadChatroomList();
						}
					}
					if (c_chatrooms == 1) {
						document.addEventListener('show', function(event) {
							var page = event.target;
							if (page.id === 'Tab3') {
								if (c_chatrooms == 1) {
									loadChatroomList();
								}
							}
						});
					}
					document.addEventListener('prechange', function(event) {
						if (event.tabItem) {
							document.querySelector('ons-toolbar .center').innerHTML = event.tabItem.getAttribute('label');
							a('#add-icon').attr('icon', event.tabItem.getAttribute('add-icon'));
							if (event.tabItem.getAttribute('add-icon') == "fa-circle-plus" && c_user_chatrooms != 1)
								a("#add-icon").hide();
							else
								a("#add-icon").show();
						}
					});
					document.addEventListener('postchange', function(event) {
						if (event.carousel) {
							if (event.activeIndex == 1) {
								a(document).unbind('click');
								a(document).on('click', function (e) {
									if (!a(e.target).closest('.arrowchat_userlist_remove').length) {
										event.carousel.first();
									}
								});
								a(document).unbind('touchmove');
								a(document).on('touchmove', function (e) {
									if (!a(e.target).closest('.arrowchat_userlist_remove').length) {
										event.carousel.first();
									}
								});
							}
							if (event.activeIndex == 0) {
								a(document).unbind('touchmove');
								a(document).unbind('click');
							}
						}
						if (event.index == 1) {
							onlineTabLoading();
						}
					});
					window.fn = {};
					window.fn.open = function(id) {
					  var menu = document.getElementById(id);
					  menu.open();
					};
					window.fn.load = function(page) {
					  var content = document.getElementById('content');
					  var menu = document.getElementById('menu');
					  content.load(page).then(menu.close.bind(menu));
					};
					window.fn.showTemplateDialog = function() {
					  var dialog = document.getElementById('settings-dialog');
					  if (dialog) {
						dialog.show();
						receiveBlockList();
						a("#menu")[0].close();
					  } else {
						ons.createElement('settings-page', { append: true }).then(function(dialog) {
							dialog.show();
							receiveBlockList();
							getSettingsCookies();
							a("#menu")[0].close();
						});
					  }
					};
					window.fn.showChatroomSettings = function() {
					  var dialog = document.getElementById('chatroom-settings-dialog');
					  if (dialog) {
						a("#chatroom-settings-image").prop('src', c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[Ccr]);
						a("#chatroom-settings-name").html(cr_name[Ccr]);
						dialog.show();
					  } else {
						ons.createElement('chatroom-settings-page', { append: true }).then(function(dialog) {
							dialog.show();
							getChatroomSettings();
						});
					  }
					};
					window.fn.showSearchDialog = function() {
					  var dialog = document.getElementById('search-dialog');
					  if (dialog) {
						dialog.show();
					  } else {
						ons.createElement('search-page', { append: true }).then(function(dialog) {
							dialog.show();
							settingsButtonClick();
						});
					  }
					};
					window.fn.showChangeNameDialog = function() {
						var dialog = document.getElementById('change-name-dialog');
						if (dialog) {
							dialog.show();
					    } else {
							ons.createElement('change-name-page', { append: true }).then(function(dialog) {
								dialog.show();
								changeGuestName();
							});
						}
					};
					window.fn.passwordDialog = function() {
					  var dialog = document.getElementById('password-dialog');
					  if (dialog) {
						dialog.show();
						a("#password-page-input").val('');
					  } else {
						ons.createElement('chatroom-password-page', { append: true }).then(function(dialog) {
							dialog.show();
							chatroomPasswordClick();
						});
					  }
					};
					window.fn.createDialog = function() {
					  var dialog = document.getElementById('create-dialog');
					  if (dialog) {
						dialog.show();
						a("#create-page-input").val('');
						a("#create-password-page-input").val("");
					  } else {
						ons.createElement('chatroom-create-page', { append: true }).then(function(dialog) {
							dialog.show();
							chatroomCreateClick();
						});
					  }
					};
					window.fn.hideDialog = function(id) {
					  document.getElementById(id).hide();
					};
					if (a("#buddylist-container-recent").length > 0) {
						recentTabLoading();
					} else {
						document.addEventListener('show', function(event) {
							var page = event.target;
							if (page.id === 'Tab1') {
								recentTabLoading();
							}
						});
					}
					setAvatarVisibility();
				} else {
					a("#buddylist-container-available").html("<li>"+lang[116]+"</li>");
				}
				ion.sound({
					sounds: [
						{
							name: "new_message_mobile"
						},
						{
							name: "send_mobile"
						}
					],
					path: c_ac_path + "themes/" + u_theme + "/sounds/",
					preload: true,
					volume: 1.0
				});
			}
		}
        a.ajaxSetup({
            scriptCharset: "utf-8",
			headers: {
			 'Cache-Control': 'no-cache, no-store, must-revalidate'
		    },
            cache: false
        });
        arguments.callee.runarrowchat = function () {
            Xa()
        };
    }
})(jqac);
jqac(window).on('load', function() {
	jqac.arrowchat();
	jqac.arrowchat.runarrowchat()
});