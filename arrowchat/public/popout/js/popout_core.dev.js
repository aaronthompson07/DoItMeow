(function (a) {
    a.arrowchat = function () {
		var $body = a('body');
		var $tooltip = null;
		var $tooltip_content;
		var $users = {};
		var $user_popups = {};
		var $chatroom_tab = {};
		var $chatrooms_popups = {};
		
		function hideTooltip() {
			if ($tooltip) {
				$tooltip.hide();
			}
		}
		
		function showTooltip($target, text, is_left, custom_left, custom_top, is_sideways, is_sideways_right) {
			if ($tooltip === null) {
				$tooltip = a('<div id="arrowchat_tooltip"><div class="arrowchat_tooltip_content"></div></div>').appendTo($body);
				$tooltip_content = a('.arrowchat_tooltip_content', $tooltip);
			}
			$tooltip_content.html(text);
			var target_offset = $target.offset();
			var target_width = $target.width();
			var target_height = $target.height();
			var tooltip_width = $tooltip.width();
			if (!custom_left) {
				custom_left = 0;
			}
			if (!custom_top) {
				custom_top = 0;
			}
			if (is_left) {
				$tooltip.css({
					top				: target_offset.top - a(window).scrollTop() - target_height - 5 - custom_top,
					left			: target_offset.left + target_width - 16 - custom_left,
					display			: "block",
					'padding-right' : "0px",
					'padding-left' : "0px"
				}).addClass("arrowchat_tooltip_left");
			} else if (is_sideways_right) {
				$tooltip.css({
					top				: target_offset.top - a(window).scrollTop() + (target_height/2) - 10 - custom_top,
					left			: target_offset.left + target_width + 28 - custom_left,
					display			: "block",
					'padding-right' : "0px",
					'padding-left' : "6px"
				}).removeClass("arrowchat_tooltip_left");
			} else if (is_sideways) {
				$tooltip.css({
					top				: target_offset.top - a(window).scrollTop() + (target_height/2) - 10 - custom_top,
					left			: target_offset.left + target_width - tooltip_width + 18 - custom_left,
					display			: "block",
					'background-position'	: tooltip_width - 128 + "px -58px",
					'padding-right' : "6px",
					'padding-left' : "0px"
				}).removeClass("arrowchat_tooltip_left");
			} else {
				$tooltip.css({
					top				: target_offset.top - a(window).scrollTop() - target_height - 5 - custom_top,
					left			: target_offset.left + target_width - tooltip_width + 18 - custom_left,
					display			: "block",
					'padding-right' : "0px",
					'padding-left' : "0px"
				}).removeClass("arrowchat_tooltip_left");
			}
			if (W) {
				$tooltip.css("position", "absolute");
				$tooltip.css(
					"top", 
					parseInt(a(window).height()) - parseInt($tooltip.css("bottom")) - parseInt($tooltip.height()) + a(window).scrollTop() + "px"
				);
			}
		}
		
        function loadBuddyList() {
			clearTimeout(Z);
			a(".arrowchat_nofriends").remove();
            a.ajax({
                url: c_ac_path + "includes/json/receive/receive_buddylist.php?popout=1",
                cache: false,
                type: "get",
                dataType: "json",
                success: function (b) {
					if (!a("#arrowchat_room_selection").hasClass("arrowchat_selection_tab_selected"))
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
		
        function buildBuddyList(b) {
			a(".arrowchat_loading_icon").remove();
			a("#arrowchat_popout_left_lists").html('<div id="arrowchat_userslist_available"></div><div id="arrowchat_userslist_busy"></div><div id="arrowchat_userslist_away"></div><div id="arrowchat_userslist_offline"></div>');
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
                        longname = renderHTMLString(f.n);
                        if (G[f.id] != null) {
                            a(".arrowchat_closed_status", $users[f.id]).removeClass("arrowchat_available").removeClass("arrowchat_busy").removeClass("arrowchat_offline").removeClass("arrowchat_away").addClass("arrowchat_" + f.s);
							if (f.s == "offline" || (f.s == "busy" && c_video_select != 2 && c_video_select != 3))
								a(".arrowchat_popout_video_chat", $user_popups[f.id]).addClass("arrowchat_video_unavailable");
							else
								a(".arrowchat_popout_video_chat", $user_popups[f.id]).removeClass("arrowchat_video_unavailable");
                        }
                        if (f.s == "available" || f.s == "away" || f.s == "busy") onlineNumber++;
                        totalFriendsNumber++;
						if (a("#arrowchat_setting_names_only :input").is(":checked")) d = "arrowchat_hide_avatars";
						var icon = ' fas fa-circle';
						if (f.s == 'away')
							icon = ' fas fa-moon';
						else if (f.s == 'busy')
							icon = ' far fa-mobile-screen';
                        c[f.s] += '<div id="arrowchat_userlist_' + f.id + '" class="arrowchat_userlist arrowchat_buddylist_admin_' + f.admin + '" onmouseover="jqac(this).addClass(\'arrowchat_userlist_hover\');" onmouseout="jqac(this).removeClass(\'arrowchat_userlist_hover\');"><img class="arrowchat_userlist_avatar ' + d + '" src="' + f.a + '" /><span class="arrowchat_userscontentname">' + longname + '</span><span class="arrowchat_userscontentdot arrowchat_' + f.s + icon + '"></span></div>';
						if (typeof($user_popups[f.id]) != "undefined") {
							var status = f.s;
							if (status == "available")
								status = lang[1];
							if (status == "away")
								status = lang[240];
							if (status == "offline")
								status = lang[241];
							if (status == "busy")
								status = lang[216];
							$user_popups[f.id].find(".arrowchat_right_header_status").html(status);
							a(".arrowchat_info_status", $user_popups[f.id]).html(status);
						}
                        uc_status[f.id] = f.s;
                        uc_name[f.id] = f.n;
                        uc_avatar[f.id] = f.a;
                        uc_link[f.id] = f.l
                    })
                }
                if (buddylistreceived == 1) {
                    for (buddystatus in c) {
						if (c.hasOwnProperty(buddystatus)) {
							if (c[buddystatus] == "") {
								a("#arrowchat_userslist_" + buddystatus).html("")
							} else {
								a("#arrowchat_userslist_" + buddystatus).html("<div>" + c[buddystatus] + "</div>");
							}
						}
					}
                    a(".arrowchat_userlist").click(function () {
						var c = a(this).attr('id').substr(19);
                        receiveUser(c, uc_name[c], uc_status[c], uc_avatar[c], uc_link[c]);
                    });
                    R = onlineNumber;
                    if (totalFriendsNumber == 0 || onlineNumber == 0) {
						a('<div class="arrowchat_nofriends">' + lang[8] + "</div>").appendTo("#arrowchat_popout_friends");
					}
                    buddylistreceived = 0
                }
				if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
					setAvatarVisibility(1);
				}
            });
			a(".arrowchat_buddylist_admin_1").css("background-color", "#"+c_admin_bg);
			a(".arrowchat_buddylist_admin_1").css("color", "#"+c_admin_txt);
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
				setTimeout(function(){lsClick("body", 'window_focus')},500);
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
		
        function notifyNewMessage(from, nw_count) {
            if (uc_name[from] == null || uc_name[from] == "") setTimeout(function () {
                notifyNewMessage(from, nw_count)
            }, 500);
            else {
				if ($users[from].length > 0) {
					if ($users[from].hasClass("arrowchat_popout_focused")) {
						return false;
					}
					if (a(".arrowchat_popout_alert", $users[from]).length > 0) {
						nw_count = parseInt(a(".arrowchat_popout_alert", $users[from]).html()) + parseInt(nw_count);
					}
					if (nw_count == 0) {
						a(".arrowchat_popout_alert", $users[from]).remove();
					} else {
						if (a(".arrowchat_popout_alert", $users[from]).length > 0) {
							a(".arrowchat_popout_alert", $users[from]).html(nw_count);
						} else a("<div/>").addClass("arrowchat_popout_alert").html(nw_count).insertAfter(a(".arrowchat_popout_wrap .arrowchat_closed_status", $users[from]));
					}
					y[from] = nw_count;
					S();
				}
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
		
		function M() {
			a(".arrowchat_popout_convo").css("height", a(window).height() - a(".arrowchat_popout_convo_focused .arrowchat_popout_convo_right_header").outerHeight() - a("#arrowchat_popout_open_chats").outerHeight() - a(".arrowchat_popout_convo_focused .arrowchat_popout_input_container").outerHeight());
		}
		
		function cancelJSONP() {
			if (typeof CHA != "undefined") {
				clearTimeout(CHA);
			}
			if (typeof xOptions != "undefined") {
				xOptions.abort();
			}
		}
		
        function receiveCore() {
			cancelJSONP();
			var chatroom_string = "";
			if (!a.isEmptyObject(chatroom_list)) {
				for (var i in chatroom_list) {
					chatroom_string = chatroom_string + "&room[]=" + chatroom_list[i];
				}
			}
            var url = c_ac_path + "includes/json/receive/receive_core.php?hash=" + u_hash_id + "&init=" + acsi + chatroom_string;
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
										lsClick(h.typing_id, 'typing');
                                        receiveTyping(h.typing_id);
                                    } else {
										lsClick(h.typing_id, 'untyping');
                                        receiveNotTyping(h.typing_id);
                                    }
                                });
                            }
							if (e == "announcements") {
								a.each(l, function (f, h) {
									receiveAnnouncement(h);
								});
							}
							if (e == "warnings") {
								a.each(l, function (f, h) {
									receiveWarning(h);
								});
							}
							if (e == "chatroom") {
								var alert_count = [],
									room_data = [],
									play_chatroom_sound = 0;
								a.each(l, function (f, h) {
									if (h.action == 1) {
										a("#arrowchat_chatroom_message_" + h.m + " .arrowchat_chatboxmessagecontent").html(lang[159] + h.n);
									} else {
										if (typeof(blockList[h.userid]) == "undefined") {
											addChatroomMessage(h.id, h.n, h.m, h.userid, h.t, h.global, h.mod, h.admin, h.chatroomid);
										}
										if (!a(".arrowchat_textarea").is(":focus") && h.userid != u_id)
											play_chatroom_sound = 1;
											
										room_data[h.chatroomid] = h;
										if (typeof(alert_count[h.chatroomid]) != "undefined")
											alert_count[h.chatroomid] = alert_count[h.chatroomid] + 1;
										else
											alert_count[h.chatroomid] = 1;
									}
								});
								if (room_data.length > 0) {
									showChatroomTime();
									for (var key in room_data) {
										if (typeof(blockList[room_data[key].userid]) == "undefined") {
											chatroomAlerts(alert_count[key], room_data[key].chatroomid);
											var data_array = [alert_count[key], room_data[key].chatroomid];
											lsClick(JSON.stringify(data_array), 'chatroom_alerts');
										}
									}
									u_chatroom_sound == 1 && play_chatroom_sound ==1 && playNewMessageSound();
								}
							}
                            if (e == "messages") {
								var play_sound = 0;
                                a.each(l, function (f, h) {
									receiveMessage(h.id, h.from, h.message, h.sent, h.self, h.old, 0, 0);
									if (!a(".arrowchat_textarea", $user_popups[h.from]).is(":focus")) {
										play_sound = 1;
									}
                                });
                                K = 1;
								D = E;
								//j != "" && i > 0 && addMessageToContent(j, c);
								showTimeAndTooltip();
                                d != 1 && u_sounds == 1 && play_sound == 1 && acsi != 1 && playNewMessageSound();
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
                }
            });
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
		
		function showTimeAndTooltip() {
			a(".arrowchat_chatboxmessagecontent").mouseenter(function () {
				if (a(this).parent().parent().hasClass('arrowchat_image_msg'))
					showTooltip(a(this), a(this).attr("data-id"), false, a(this).parent().width() + 23, 6, 1, false);
				else
					showTooltip(a(this), a(this).attr("data-id"), false, a(this).parent().width() - 5, -3, 1, false);
			});
			a(".arrowchat_chatboxmessagecontent").mouseleave(function () {
				hideTooltip();
			});					
			a(".arrowchat_chatboxmessage").mouseenter(function () {
				a(this).children(".arrowchat_ts").show();
			});
			a(".arrowchat_chatboxmessage").mouseleave(function () {
				a(this).children(".arrowchat_ts").hide();
			});
			a(".arrowchat_lightbox").unbind('click');
			a(".arrowchat_lightbox").click(function (){
				a.slimbox(a(this).attr('data-id'), '<a href="'+a(this).attr('data-id')+'">'+lang[70]+'</a>', {resizeDuration:1, overlayFadeDuration:1, imageFadeDuration:1, captionAnimationDuration:1});
			});
		}
		
        function addMessageToContent(b, c) {
            if (uc_name[b] == null || uc_name[b] == "") setTimeout(function () {
                addMessageToContent(b, c)
            }, 500);
            else {
                a("#arrowchat_popout_text_" + b).append("<div>" + c + "</div>");
                a("#arrowchat_popout_text_" + b).scrollTop(5E4);
                G[b] = 1
            }
        }
		
        function playNewMessageSound() {
			ion.sound.play("new_message");
        }
		
        function receiveUser(b, c, d, e, l, f, h) {
            if (!(b == null || b == "")) {
				if (uc_name[b] == null || uc_name[b] == "") {
					if (aa[b] != 1) {
						aa[b] = 1;
						a.ajax({
							url: c_ac_path + "includes/json/receive/receive_user.php",
							data: {
								userid: b
							},
							type: "post",
							cache: false,
							dataType: "json",
							success: function (o) {
								if (o) {
									c = uc_name[b] = o.n;
									d = uc_status[b] = o.s;
									e = uc_avatar[b] = o.a;
									l = uc_link[b] = o.l;
									if (G[b] != null) {
										a(".arrowchat_closed_status", $users[b]).removeClass("arrowchat_available").removeClass("arrowchat_busy").removeClass("arrowchat_offline").addClass("arrowchat_" + d);
									}
									aa[b] = 0;
									if (c != null) {
										toggleUserChatTab(b, c, d, e, l, f, h)
									} else {
										a.post(c_ac_path + "includes/json/send/send_settings.php", {
											unfocus_chat: b
										}, function () {})
									}
								}
							}
						})
					} else {
						setTimeout(function () {
							receiveUser(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], f, h)
						}, 500);
					}
				} else {
					toggleUserChatTab(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], f, h);
				}
			}
        }
		
		function formatTimestamp(b, noHTML) {
			var c = "am",
				d = b.getHours(),
				i = b.getMinutes(),
				e = b.getDate();
			b = b.getMonth();			var g = d;
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
			if (noHTML) {
				if (c_us_time != 1) {
					return e != Na ? '' + g + ":" + i + " " + e + f + " " + l[b] + "" : '' + g + ":" + i + ""
				} else {
					return e != Na ? '' + l[b] + ' ' + e + ', ' + d + ':' + i + c + '' : '' + d + ':' + i + c + ''
				}
			} else {
				if (c_us_time != 1) {
					return e != Na ? '<span class="arrowchat_ts">' + g + ":" + i + " " + e + f + " " + l[b] + "</span>" : '<span class="arrowchat_ts">' + g + ":" + i + "</span>"
				} else {
					return e != Na ? '<span class="arrowchat_ts">' + l[b] + ' ' + e + ', ' + d + ':' + i + c + '</span>' : '<span class="arrowchat_ts">' + d + ':' + i + c + '</span>'
				}
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
		
        function receiveHistory(b, times) {
			if (times) {} else times = 1;
			if (times > 1) {
				a('<div class="arrowchat_message_history_loading" style="text-align:center;padding:5px 0;"><img src="' + c_ac_path + 'themes/' + u_theme + '/images/img-loading.gif" alt="Loading" /></div>').prependTo(a("#arrowchat_popout_text_" + b));
			}
            a.ajax({
                cache: false,
                url: c_ac_path + "includes/json/receive/receive_history.php",
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
							a(".arrowchat_popout_convo", $user_popups[b]).html("");
                        last_sent[b] = null;
                        var d = "",
                            i = uc_name[b],
							init = false,
							unhide_avatars = [];
                        a.each(c, function (e, l) {
                            e == "messages" && a.each(l, function (f, h) {
                                f = "";
								var pending_icon = "";
								numMessages++;
                                if (h.self == 1) {
                                    fromname = u_name;
									fromid = u_id;
                                    f = " arrowchat_self";
                                    _aa5 = _aa4 = "";
									avatar = u_avatar;
									pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
                                } else {
                                    fromname = i;
									fromid = b;
                                    _aa4 = '<a target="_blank" href="' + uc_link[b] + '">';
                                    _aa5 = "</a>";
									avatar = uc_avatar[h.from];
                                }
								if (last_name[h.from] != fromid && typeof(last_name[h.from]) != "undefined") {
									unhide_avatars.push(last_id[h.from]);
								}
								var image_msg = "";
								var show_time_class = "";
                                var o = new Date(h.sent * 1E3);
								tooltip = formatTimestamp(o, 1);
								if (c_show_full_name != 1) {
									if (fromname.indexOf(" ") != -1) fromname = fromname.slice(0, fromname.indexOf(" "));
								}
								if (h.message.substr(0, 4) == "<div") {
									image_msg = " arrowchat_image_msg";
								}
								if (last_sent[h.from] == null || h.sent - last_sent[h.from] > 180) {
									show_time_class = " arrowchat_show_time";
								}
								var noAvatarColor = getRandomColor(fromname);
								d += '<div class="arrowchat_chatboxmessage arrowchat_clearfix' + f + image_msg + show_time_class + '" id="arrowchat_message_' + h.id + '">' + formatTimestamp(o) + '<div class="arrowchat_chatboxmessagefrom arrowchat_single_avatar_hide arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img alt="' + fromname + '" class="arrowchat_chatbox_avatar" src="' + avatar + '" /></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="' + tooltip + '">' + h.message + "</div></div>" + pending_icon + "</div>";
								last_sent[h.from] = h.sent;
								last_name[h.from] = fromid;
								last_id[h.from] = h.id;
								init = true;
                            })
                        });
						var current_top_element = a("#arrowchat_popout_text_" + b).children('div').first('div');
                        if (times > 1) {
							a(d).prependTo(a("#arrowchat_popout_text_" + b).first('div'));
						} else {
							a("#arrowchat_popout_text_" + b).html("<div>" + d + "</div>");
						}
						if (a("#arrowchat_message_" + last_id[b]).length) {
							a("#arrowchat_message_" + last_id[b]).children('.arrowchat_chatboxmessagefrom').removeClass('arrowchat_single_avatar_hide');
						}
						a.each(unhide_avatars, function(key, value) {
							if (a("#arrowchat_message_" + value).length) {
								a("#arrowchat_message_" + value).children('.arrowchat_chatboxmessagefrom').removeClass('arrowchat_single_avatar_hide');
							}
						});
						a(".arrowchat_pending_icon", a("#arrowchat_popout_text_" + b)).hide();
						a(".arrowchat_tabcontenttext div .arrowchat_pending_icon:last", a("#arrowchat_popout_text_" + b)).show();
						showTimeAndTooltip();
						if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
							setAvatarVisibility(1);
						}
						var previous_height = 0;
						current_top_element.prevAll().each(function() {
						  previous_height += a(this).outerHeight();
						});
						if (times == 1)
							a("#arrowchat_popout_text_" + b).scrollTop(5E4);
						else
							a("#arrowchat_popout_text_" + b).scrollTop(previous_height);
						a("#arrowchat_popout_text_" + b).scroll(function(){
							if (a("#arrowchat_popout_text_" + b).scrollTop() < 50 && history_ids[b] != 1) {
								history_ids[b] = 1;
								if (numMessages == 20) {
									times++;
									receiveHistory(b, times);
								}
							}
						});
						if (numMessages == 0 && times == 1) {
							a("#arrowchat_popout_text_" + b).html('<div class="arrowchat_no_recent_convo"><i class="fas fa-hand-wave"></i>'+lang[245]+'</p>');
							a("#arrowchat_popout_text_" + b).html('<div class="arrowchat_no_recent_convo"><i class="fas fa-hand-wave"></i>'+lang[245]+'</p>');
						}
						if (times == 1) {
							a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
							  a("#arrowchat_popout_text_" + b).scrollTop(5E4);
							}).each(function() {
							  if(this.complete) a(this).trigger('load');
							});
						}
                    }
                }
            });
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
			$element.focus();
			if (c_send_room_msg == 1 && i != "") {
				displayMessage("arrowchat_chatroom_message_flyout", lang[209], "error");
			} else {
				var pending_msg_count_local = 0;
				i != "" && a.ajax({
					url: c_ac_path + "includes/json/send/send_message_chatroom.php",
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
						pending_msg_room_count++;
						pending_msg_count_local = pending_msg_room_count;
						
						addMessageToChatroom(0, u_name, i, 0, to_id, pending_msg_count_local);
					},
					error: function () {
						displayMessage("arrowchat_chatroom_message_flyout", lang[135], "error");
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
											displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
											update_pending_room_status(o, pending_msg_count_local, "arrowchat_pending_error");
										});
									}
								});
							}
							
							if (no_error) {
								update_pending_room_status(o, pending_msg_count_local, "arrowchat_pending_delivered");
								var data_array = [o, u_name, i, to_id];
								lsClick(JSON.stringify(data_array), 'send_chatroom_message');
								a(".arrowchat_popout_convo", $chatrooms_popups[to_id]).scrollTop(a(".arrowchat_popout_convo", $chatrooms_popups[to_id])[0].scrollHeight);
							}
						}
					}
				});
			}
			return false
		}
		
		function chatroomKeydown(key, $element, id) {
			if (key.keyCode == 13 && key.shiftKey == 0) {
				sendChatroomMessage($element, id);
				return false;
			}
		}

		function chatroomKeyup(b, $element, id) {
			if (a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {
				a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").hide();
			}
		}
		
		function userchatKeydown(key, $element, typing) {
			clearTimeout(pa);
			pa = setTimeout(function () {
				a.post(c_ac_path + "includes/json/send/send_typing.php", {
					userid: u_id,
					typing: typing,
					untype: 1
				}, function () {});
				fa = -1
			}, 5E3);
			if (fa != typing) {
				a.post(c_ac_path + "includes/json/send/send_typing.php", {
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
			$element.focus();
			if (c_send_priv_msg == 1 && sent_msg != "") {
				displayMessage("arrowchat_chatroom_message_flyout", lang[209], "error");
			} else {
				var pending_msg_count_local = 0;
				var time = Math.floor((new Date).getTime() / 1E3);
				sent_msg != "" && a.ajax({
					url: c_ac_path + "includes/json/send/send_message.php",
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
						displayMessage("arrowchat_chatroom_message_flyout", lang[135], "error");
						update_pending_status(0, pending_msg_count_local, "arrowchat_pending_error");
					},
					success: function (e) {
						
						if (e) {
							if (!a.isNumeric(e)) {
								a.each(e, function (i, q) {
									if (i == "error") {
										a.each(q, function (l, f) {
											displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
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
		
        function userchatKeyup(key, $element, d) {
			if (a(".arrowchat_smiley_popout", $user_popups[d]).is(":visible")) {
				a(".arrowchat_smiley_popout", $user_popups[d]).children(".arrowchat_more_popout").hide();
			}
            a(".arrowchat_popout_convo", $user_popups[d]).scrollTop(a(".arrowchat_popout_convo", $user_popups[d])[0].scrollHeight)
        }
		
        function toggleUserChatTab(b, c, d, e, l, f) {
            if (typeof($users[b]) != "undefined") {
                if (!$users[b].hasClass("arrowchat_tabclick") && f != 1) {
					$users[b].click();
                }
            } else {
				var name = renderHTMLString(uc_name[b]);
				var noAvatarColor = getRandomColor(name);
                $users[b] = a("<div/>").attr("data-id", b).addClass("arrowchat_popout_tab").html('<div class="arrowchat_bar_left"><div class="arrowchat_popout_wrap"><div class="arrowchat_avatartab arrowchat_white_background"><img class="arrowchat_avatar_tab" src="' + uc_avatar[b] + '" /><span class="arrowchat_tab_letter arrowchat_tab_letter_small arrowchat_tab_letter_xxsmall"></span></div><div class="arrowchat_closed_status arrowchat_' + d + '"></div><div class="arrowchat_popout_tab_name">' + name + '</div><div class="arrowchat_is_typing"><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div></div></div></div><div class="arrowchat_popout_right"><div class="arrowchat_closebox_bottom"><i class="fas fa-xmark"></i></div></div>').appendTo(a("#arrowchat_popout_container"));
				var status = uc_status[b];
				if (status == "available")
					status = lang[1];
				if (status == "away")
					status = lang[240];
				if (status == "offline")
					status = lang[241];
				if (status == "busy")
					status = lang[216];
				$user_popups[b] = a("<div/>").attr("data-id", b).addClass("arrowchat_popout_convo_wrapper").html('<div class="arrowchat_popout_convo_right_header"><div class="arrowchat_user_image"><div class="arrowchat_avatarbox arrowchat_white_background"><img class="arrowchat_avatar" src="' + uc_avatar[b] + '" /><span class="arrowchat_tab_letter arrowchat_tab_letter_small"></span></div></div><div class="arrowchat_header_container"><div class="arrowchat_right_header_name"><a target="_blank" href="' + uc_link[b] + '">' + uc_name[b] + '</a></div><div class="arrowchat_right_header_status">' + status + '</div></div><div class="arrowchat_popout_video_chat fas fa-video" id="arrowchat_video_chat_' + b + '"></div><div class="arrowchat_popout_info far fa-ellipsis" id="arrowchat_info_' + b + '"></div></div><div id="arrowchat_popout_text_' + b + '" class="arrowchat_popout_convo"></div><div class="arrowchat_popout_input_container"><div class="arrowchat_giphy_button"><i class="fas fa-gif"></i><div class="arrowchat_more_wrapper arrowchat_giphy_popout"><div class="arrowchat_more_popout"><div class="arrowchat_giphy_box"><label class="arrowchat_giphy_search_wrapper"><div class="arrowchat_giphy_magnify"><i class="far fa-magnifying-glass"></i></div><input type="text" class="arrowchat_giphy_search" placeholder="'+lang[214]+'" value="" tabindex="0" /></label><div class="arrowchat_giphy_image_wrapper"><div class="arrowchat_loading_icon"></div></div></div><i class="arrowchat_more_tip"></i></div></div></div><div id="arrowchat_upload_button_' + b + '" class="arrowchat_upload_button_container"><i class="fas fa-camera"></i><div id="arrowchat_chatroom_uploader"> </div></div><div class="arrowchat_textarea_wrapper"><textarea class="arrowchat_textarea" placeholder="' + lang[213] + '"></textarea><div class="arrowchat_smiley_button"><i class="fa-solid fa-face-grin-wide"></i><div class="arrowchat_more_wrapper arrowchat_smiley_popout"><div class="arrowchat_more_popout"><div class="arrowchat_smiley_box"><div class="arrowchat_emoji_wrapper"></div><div class="arrowchat_emoji_select_wrapper"><div class="arrowchat_emoji_selector arrowchat_emoji_smileys" data-id="emoji_smileys"><i class="fa-solid fa-face-grin-beam"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_animals" data-id="emoji_animals"><i class="fa-solid fa-dog"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_food" data-id="emoji_food"><i class="fa-solid fa-fork-knife"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_activities" data-id="emoji_activities"><i class="fa-solid fa-basketball"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_travel" data-id="emoji_travel"><i class="fa-solid fa-plane"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_objects" data-id="emoji_objects"><i class="fa-solid fa-bath"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_symbols" data-id="emoji_symbols"><i class="fa-solid fa-symbols"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_flags" data-id="emoji_flags"><i class="fa-solid fa-flag-swallowtail"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_custom" data-id="emoji_custom"><i class="fa-solid fa-sparkles"></i></div></div></div><i class="arrowchat_more_tip"></i></div></div></div></div><div class="arrowchat_user_send_button"> <i class="fa-solid fa-paper-plane-top"></i> </div></div>').appendTo(a("#arrowchat_popout_chat"));
				a('<div id="arrowchat_user_upload_queue_'+b+'" class="arrowchat_users_upload_queue"></div><div class="arrowchat_info_panel" data-id="' + b + '"><div class="arrowchat_info_user"><div class="arrowchat_info_avatar arrowchat_white_background"><img src="' + uc_avatar[b] + '" alt="" /><span class="arrowchat_tab_letter"></span></div><div class="arrowchat_info_name_wrapper"><div class="arrowchat_info_name"><a target="_blank" href="' + uc_link[b] + '">' + uc_name[b] + '</a></div><div class="arrowchat_info_status">' + status + '</div></div><div class="arrowchat_clearfix"></div></div><div class="arrowchat_info_options"><div class="arrowchat_info_option_wrapper arrowchat_option_clear" id="arrowchat_option_clear_' + b + '"><div class="arrowchat_info_option_pic far fa-trash-can"></div><div class="arrowchat_info_option_txt">'+lang[24]+'</div></div><div class="arrowchat_info_option_wrapper arrowchat_option_ban" id="arrowchat_option_ban_' + b + '"><div class="arrowchat_info_option_pic far fa-user-xmark"></div><div class="arrowchat_info_option_txt">'+lang[84]+'</div></div><div class="arrowchat_info_option_wrapper arrowchat_option_report" id="arrowchat_option_report_' + b + '"><div class="arrowchat_info_option_pic far fa-triangle-exclamation"></div><div class="arrowchat_info_option_txt">'+lang[167]+'</div></div></div></div>').prependTo($user_popups[b]);
				a(".arrowchat_closed_image", $users[b]).css('background-color', noAvatarColor["color"]);
				a(".arrowchat_tab_letter", $users[b]).html(noAvatarColor["character"]);
				a(".arrowchat_avatarbox", $user_popups[b]).css('background-color', noAvatarColor["color"]);
				a(".arrowchat_info_avatar", $user_popups[b]).css('background-color', noAvatarColor["color"]);
				a(".arrowchat_avatartab", $users[b]).css('background-color', noAvatarColor["color"]);
				a(".arrowchat_tab_letter", $user_popups[b]).html(noAvatarColor["character"]);
				if (c_enable_moderation != 1) a(".arrowchat_option_report").hide();
				a("#arrowchat_option_ban_" + b).click(function() {
					a(".arrowchat_closebox_bottom", $users[b]).click();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						block_chat: b
					}, function () {
						if (typeof(blockList[b]) == "undefined") {
							blockList[b] = b;
						}
						loadBuddyList();
					})
				});
				a(".arrowchat_info_option_wrapper").mouseenter(function() {
					a(this).addClass("arrowchat_info_option_wrapper_hover");
				});
				a(".arrowchat_info_option_wrapper").mouseleave(function() {
					a(this).removeClass("arrowchat_info_option_wrapper_hover");
				});
				a("#arrowchat_option_clear_" + b).click(function() {
					a("#arrowchat_popout_text_" + b).html("");
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						clear_chat: b
					}, function () {})
				});
				a("#arrowchat_option_report_" + b).click(function() {
					a("#arrowchat_option_report_" + b).hide();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						report_from: u_id,
						report_about: b
					}, function () {
						displayMessage("arrowchat_chatroom_message_flyout", lang[168], "notice");
					});
				});
				a("#arrowchat_info_" + b).mouseenter(function () {
                    a(this).addClass("arrowchat_popout_info_hover");
					showTooltip(a(this), lang[23], false, a(this).width() + 18, 6, 1, false);
                });
				a("#arrowchat_info_" + b).mouseleave(function () {
                    a(this).removeClass("arrowchat_popout_info_hover");
					hideTooltip();
                });
				a("#arrowchat_info_" + b).click(function() {
					hideTooltip();
					if (a(".arrowchat_info_panel", $user_popups[b]).is(":visible")) {
						a(".arrowchat_info_panel", $user_popups[b]).hide();
						$user_popups[b].removeClass("arrowchat_info_panel_visible");
						a(this).removeClass("arrowchat_popout_info_clicked");
						a(".arrowchat_popout_convo", $user_popups[b]).css("margin-right", "0px");
						a(".arrowchat_popout_input_container", $user_popups[b]).css("margin-right", "0px");
					} else {
						a(".arrowchat_info_panel", $user_popups[b]).show();
						$user_popups[b].addClass("arrowchat_info_panel_visible");
						a(this).addClass("arrowchat_popout_info_clicked");
						a(".arrowchat_popout_convo", $user_popups[b]).css("margin-right", "201px");
						a(".arrowchat_popout_input_container", $user_popups[b]).css("margin-right", "201px");
					}
				});
                a(".arrowchat_closebox_bottom", $users[b]).mouseenter(function () {
                    a(this).addClass("arrowchat_closebox_bottomhover")
                });
                a(".arrowchat_closebox_bottom", $users[b]).mouseleave(function () {
                    a(this).removeClass("arrowchat_closebox_bottomhover")
                });
				$users[b].mouseenter(function () {
					a(this).addClass("arrowchat_tabmouseover_popout");
                });
                $users[b].mouseleave(function () {
					a(this).removeClass("arrowchat_tabmouseover_popout");
                });
				if (c_video_chat == 1) {
					a("#arrowchat_video_chat_" + b).mouseenter(function () {
						if (uc_status[b] == 'offline' || (uc_status[b] == 'busy' && c_video_select != 2 && c_video_select != 3)) {
							showTooltip(a(this), lang[146], false, a(this).width() + 18, 6, 1, false);
						} else {
							showTooltip(a(this), lang[88], false, a(this).width() + 18, 6, 1, false);
							a(this).addClass("arrowchat_popout_info_hover");
						}
					});
					a("#arrowchat_video_chat_" + b).mouseleave(function () {
						a(this).removeClass("arrowchat_popout_info_hover");
						hideTooltip();
					});
					if (uc_status[b] == 'offline' || (uc_status[b] == 'busy' && c_video_select != 2 && c_video_select != 3))
						a(".arrowchat_popout_video_chat", $user_popups[b]).addClass("arrowchat_video_unavailable");
					else
						a(".arrowchat_popout_video_chat", $user_popups[b]).removeClass("arrowchat_video_unavailable");
					a("#arrowchat_video_chat_" + b).click(function () {
						hideTooltip();
						if (uc_status[b] != 'offline' && (uc_status[b] != 'busy' || c_video_select == 2 || c_video_select == 3)) {
							var RN = Math.floor(Math.random() * 9999999999);
							while (String(RN).length < 10) {
								RN = '0' + RN;
							}
							if (c_video_select == 4 || c_video_select == 1)
								RN = encodeURI(location.host).replace(/\./g, '') + RN;
							if (c_video_select == 2) {
								a.ajax({
									type:"post",
									url: c_ac_path + "public/video/video_session.php",
									data: {
										room: RN
									},
									async: false,
									success: function(sess) {
										a.post(c_ac_path + "includes/json/send/send_message.php", {
											userid: u_id,
											to: b,
											message: "video{" + sess + "}"
										}, function (e) {
											if (!a.isNumeric(e)) {
												a.each(e, function (i, q) {
													if (i == "error") {
														a.each(q, function (l, f) {
															displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
														});
													}
												});
											} else {
												jqac.arrowchat.videoWith(sess);
												displayMessage("arrowchat_chatroom_message_flyout", lang[63], "notice");
											}
											a("#arrowchat_popout_text_" + b).scrollTop(a("#arrowchat_popout_text_" + b)[0].scrollHeight);
										});
									}
								});
							} else {
								a.post(c_ac_path + "includes/json/send/send_message.php", {
									userid: u_id,
									to: b,
									message: "video{" + RN + "}"
								}, function (e) {
									if (!a.isNumeric(e)) {
										a.each(e, function (i, q) {
											if (i == "error") {
												a.each(q, function (l, f) {
													displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
												});
											}
										});
									} else {
										jqac.arrowchat.videoWith(RN);
										displayMessage("arrowchat_chatroom_message_flyout", lang[63], "notice");
									}
									a("#arrowchat_popout_text_" + b).scrollTop(a("#arrowchat_popout_text_" + b)[0].scrollHeight);
								});
							}
						} else {
							displayMessage("arrowchat_chatroom_message_flyout", lang[146], "error");
						}
					});
				} else {
					a(".arrowchat_popout_video_chat").hide();
				}
                a(".arrowchat_closebox_bottom", $users[b]).click(function () {
                    a.post(c_ac_path + "includes/json/send/send_settings.php", {
                        close_chat: b,
                        tab_alert: 1
                    }, function () {});
					$user_popups[b].remove();
                    $users[b].remove();
					delete $user_popups[b];
					delete $users[b];
                    y[b] = null;
                    G[b] = null;
                    ca[b] = 0;
					M();
                });
                a(".arrowchat_textarea", $user_popups[b]).keydown(function (h) {
                   return userchatKeydown(h, a(this), b);
                });
                a(".arrowchat_textarea", $user_popups[b]).keyup(function (h) {
                    return userchatKeyup(h, a(this), b);
                });
				$users[b].click(function () {
					var tba = 0;
                    if (a(".arrowchat_popout_alert", $users[b]).length > 0) {
                        tba = 1;
                        a(".arrowchat_popout_alert", $users[b]).remove();
                        G[b] = 0;
                        y[b] = 0;
                        S()
                    }
                    if (a(this).hasClass("arrowchat_popout_focused")) {
                        a(this).removeClass("arrowchat_popout_focused");
						$user_popups[b].removeClass("arrowchat_popout_convo_focused");
                        a.post(c_ac_path + "includes/json/send/send_settings.php", {
                            unfocus_chat: b,
                            tab_alert: 1
                        }, function () {})
                    } else {
						a(".arrowchat_popout_tab").removeClass("arrowchat_popout_focused");
						a(".arrowchat_popout_convo_wrapper").removeClass("arrowchat_popout_convo_focused");
                        if (ca[b] != 1) {
                            receiveHistory(b);
                            ca[b] = 1
                        }
						if (typeof(init_open[b]) == "undefined")
							init_open[b] = 1;
						if (init_open[b] == 1) {
							a.post(c_ac_path + "includes/json/send/send_settings.php", {
								focus_chat: b,
								tab_alert: tba
							}, function () {});
							a(".arrowchat_textarea", $user_popups[b]).focus();
						}
						init_open[b] = 1;
						a(this).addClass("arrowchat_popout_focused");
						$user_popups[b].addClass("arrowchat_popout_convo_focused");
					}
                    a(".arrowchat_popout_convo", $user_popups[b]).scrollTop(a(".arrowchat_popout_convo", $user_popups[b])[0].scrollHeight);
				});
				a(".arrowchat_emoji_selector", $user_popups[b]).click(function() {
					if (!a(this).hasClass("arrowchat_emoji_focused")) {
						a(".arrowchat_emoji_wrapper", $user_popups[b]).html('<div class="arrowchat_loading_icon"></div>');
						a(".arrowchat_emoji_selector", $user_popups[b]).removeClass("arrowchat_emoji_focused");
						a(this).addClass("arrowchat_emoji_focused");
						var load_id = a(this).attr("data-id");
						a.ajax({
							url: c_ac_path + 'includes/emojis/' + load_id + '.php',
							type: "GET",
							cache: true,
							success: function(html) {
								a(".arrowchat_emoji_wrapper", $user_popups[b]).html(html);
								a(".arrowchat_emoji", $user_popups[b]).click(function () {
									if (a(this).hasClass("arrowchat_emoji_custom"))
										var smiley_code = a(this).children('img').attr("data-id");
									else
										var smiley_code = a(this).html();
									var existing_text = a(".arrowchat_textarea", $user_popups[b]).val();
									a(".arrowchat_textarea", $user_popups[b]).focus().val('').val(existing_text + smiley_code);
								});
							}
						});
					}
				});
				a(".arrowchat_emoji_selector").mouseover(function(){
					a(this).addClass("arrowchat_emoji_selector_hover");
				});
				a(".arrowchat_emoji_selector").mouseout(function(){
					a(this).removeClass("arrowchat_emoji_selector_hover");
				});
				a(".arrowchat_emoji_smileys").mouseover(function(){showTooltip(a(this), lang[230], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_animals").mouseover(function(){showTooltip(a(this), lang[231], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_food").mouseover(function(){showTooltip(a(this), lang[232], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_activities").mouseover(function(){showTooltip(a(this), lang[233], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_travel").mouseover(function(){showTooltip(a(this), lang[234], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_objects").mouseover(function(){showTooltip(a(this), lang[235], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_symbols").mouseover(function(){showTooltip(a(this), lang[236], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_flags").mouseover(function(){showTooltip(a(this), lang[237], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_custom").mouseover(function(){showTooltip(a(this), lang[238], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_smiley_button", $user_popups[b]).mouseenter(function () {
					if (a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").is(":visible")) {} else {
						showTooltip(a(this), lang[307], 0, 10, 0);
					}
					a(this).addClass("arrowchat_smiley_button_hover")
				});
				a(".arrowchat_smiley_button", $user_popups[b]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_smiley_button_hover");
				});
				a(".arrowchat_smiley_button", $user_popups[b]).click(function () {
					hideTooltip();
					if (a(".arrowchat_giphy_popout", $user_popups[b]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_giphy_popout", $user_popups[b]).children(".arrowchat_more_popout").hide();
					}
					if (a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").hide();
					} else {
						if (!a(".arrowchat_emoji_selector", $user_popups[b]).hasClass("arrowchat_emoji_focused")) {
							a(".arrowchat_emoji_wrapper", $user_popups[b]).html('<div class="arrowchat_loading_icon"></div>');
							a(".arrowchat_emoji_selector", $user_popups[b]).removeClass("arrowchat_emoji_focused");
							a(".arrowchat_emoji_smileys", $user_popups[b]).addClass("arrowchat_emoji_focused");
							a.ajax({
								url: c_ac_path + 'includes/emojis/emoji_smileys.php',
								type: "GET",
								cache: true,
								success: function(html) {
									a(".arrowchat_emoji_wrapper", $user_popups[b]).html(html);
									a(".arrowchat_emoji", $user_popups[b]).click(function () {
										if (a(this).hasClass("arrowchat_emoji_custom"))
											var smiley_code = a(this).children('img').attr("data-id");
										else
											var smiley_code = a(this).html();
										var existing_text = a(".arrowchat_textarea", $user_popups[b]).val();
										a(".arrowchat_textarea", $user_popups[b]).focus().val('').val(existing_text + smiley_code);
									});
								}
							});
						}
						a(".arrowchat_textarea", $user_popups[b]).focus();
						a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").show();
					}
				}).children().not('i').click(function(e){
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest(".arrowchat_smiley_button").length)
						return;
						
					a(".arrowchat_smiley_popout .arrowchat_more_popout").hide();
				});
				a(".arrowchat_user_send_button", $user_popups[b]).mouseenter(function () {
					showTooltip(a(this), lang[311], 0, 10, 0);
					a(this).addClass("arrowchat_user_send_button_hover")
				});
				a(".arrowchat_user_send_button", $user_popups[b]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_user_send_button_hover");
				});
				a(".arrowchat_user_send_button", $user_popups[b]).click(function() {
					hideTooltip();
					sendUserMessage(a(".arrowchat_textarea", $user_popups[b]), b);
				});
				a(".arrowchat_giphy_button", $user_popups[b]).mouseenter(function () {
					if (a(".arrowchat_giphy_button", $user_popups[b]).find(".arrowchat_more_popout").is(":visible")) {} else {
						showTooltip(a(this), lang[309], 1, 10, 0);
						a(this).addClass("arrowchat_giphy_button_hover");
					}
				});
				a(".arrowchat_giphy_button", $user_popups[b]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_giphy_button_hover");
				});
				a(".arrowchat_giphy_button", $user_popups[b]).click(function () {
					hideTooltip();
					if (a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_smiley_popout", $user_popups[b]).children(".arrowchat_more_popout").hide();
					}
					if (a(".arrowchat_giphy_popout", $user_popups[b]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_giphy_popout", $user_popups[b]).children(".arrowchat_more_popout").hide();
					} else {
						a(".arrowchat_giphy_popout", $user_popups[b]).children(".arrowchat_more_popout").show();
						a(".arrowchat_giphy_search", $user_popups[b]).val('');
						a(".arrowchat_giphy_search", $user_popups[b]).focus();
						loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', 1, b);
					}
				}).children('.arrowchat_giphy_popout').click(function(e){
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest(".arrowchat_giphy_button").length)
						return;
						
					a(".arrowchat_giphy_button .arrowchat_more_popout").hide();
				});
				a(".arrowchat_giphy_search", $user_popups[b]).keyup(function () {
					a(".arrowchat_giphy_image_wrapper", $user_popups[b]).html('<div class="arrowchat_loading_icon"></div>');
					if (a(".arrowchat_giphy_search", $user_popups[b]).val() == '')
						loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', 1, b);
					else
						loadGiphy('https://api.giphy.com/v1/gifs/search?api_key=IOYyr4NK5ldaU&limit=20&q=' + a(".arrowchat_giphy_search", $user_popups[b]).val(), 1, b);
				});
				if (c_disable_smilies == 1) {a(".arrowchat_smiley_button").hide();a(".arrowchat_popout_input_container", $user_popups[b]).addClass("arrowchat_no_smiley")}
				if (c_file_transfer != 1) {a("#arrowchat_upload_button_" + b).remove();a(".arrowchat_popout_input_container", $user_popups[b]).addClass("arrowchat_no_file_upload")}
				if (c_giphy == 1) {a(".arrowchat_giphy_button", $user_popups[b]).hide();a(".arrowchat_popout_input_container", $user_popups[b]).addClass("arrowchat_no_giphy")}
				if (c_file_transfer == 1) {uploadProcessing(b, 0);}
				f != 1 && $users[b].click();
				y[b] = 0;
				G[b] = 0;
            }
			M();
        }
		
		function loadGiphy(url, selector, popup_id) {
			var selector_id;
			if (selector == 1)
				selector_id = $user_popups[popup_id];
			else
				selector_id = $chatrooms_popups[popup_id];
			a.ajax({
				url: url,
				type: "get",
				cache: false,
				dataType: "json",
				success: function (results) {
					results && a.each(results, function (i, e) {
						if (i == "data") {
							a(".arrowchat_giphy_image_wrapper", selector_id).html('');
							var new_height = 0;
							a.each(e, function (l, f) {
								new_height = Math.round((270/(f.images.fixed_height_downsampled.width/f.images.fixed_height_downsampled.height)));
								a(".arrowchat_giphy_image_wrapper", selector_id).append('<img class="arrowchat_giphy_image" src="' + f.images.fixed_height_downsampled.url + '" alt="" style="height:' + new_height + 'px;width:270px" height="' + new_height + '" />');
							});
							a(".arrowchat_giphy_image", selector_id).click(function () {
								a(".arrowchat_giphy_popout", selector_id).children(".arrowchat_more_popout").hide();
								var giphy_src = a(this).attr('src');
								if (selector == 2) {
									a.post(c_ac_path + "includes/json/send/send_message_chatroom.php", {
										userid: u_id,
										username: u_name,
										chatroomid: popup_id,
										message: "giphy{" + a(this).attr('height') + "}{" + a(this).attr('src') + "}"
									}, function (e) {
										if (!a.isNumeric(e)) {
											a.each(e, function (i, q) {
												if (i == "error") {
													a.each(q, function (l, f) {
														displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
													});
												}
											});
										} else {
											if (a("#arrowchat_chatroom_message_" + e).length) {} else {
												var tooltip = formatTimestamp(new Date(Math.floor((new Date).getTime() / 1E3) * 1E3), 1);
												a(".arrowchat_popout_convo", $chatrooms_popups[popup_id]).append('<div class="arrowchat_chatroom_box_message arrowchat_self arrowchat_image_msg arrowchat_chatroom_important" id="arrowchat_chatroom_message_' + e + '"><div class="arrowchat_chatroom_message_name">' + u_name + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_chatroom_message_content" data-id="' + tooltip + '"><span class="arrowchat_chatroom_msg"><div class="arrowchat_giphy_message"><img class="arrowchat_lightbox arrowchat_giphy_img" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" /></div></span></div><div class="arrowchat_message_controls"><div class="arrowchat_chatroom_reply"><i class="fas fa-reply"></i></div><div class="arrowchat_chatroom_delete" data-id="' + e + '"><i class="far fa-xmark"></i></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
												var data_array = [e, u_name, '<div class="arrowchat_giphy_message"><img class="arrowchat_lightbox" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" style="width:179px"></div>', popup_id];
												lsClick(JSON.stringify(data_array), 'send_chatroom_message');
												showChatroomTime();
											}
										}
										a(".arrowchat_popout_convo", $chatrooms_popups[popup_id]).scrollTop(a(".arrowchat_popout_convo", $chatrooms_popups[popup_id])[0].scrollHeight);
									});
								} else {
									a.post(c_ac_path + "includes/json/send/send_message.php", {
										userid: u_id,
										to: popup_id,
										message: "giphy{" + a(this).attr('height') + "}{" + a(this).attr('src') + "}"
									}, function (e) {
										if (!a.isNumeric(e)) {
											a.each(e, function (i, q) {
												if (i == "error") {
													a.each(q, function (l, f) {
														displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
													});
												}
											});
										} else {
											if (a("#arrowchat_message_" + e).length) {} else {
												var tooltip = formatTimestamp(new Date(Math.floor((new Date).getTime() / 1E3) * 1E3), 1);
												a(".arrowchat_popout_convo", $user_popups[popup_id]).append('<div class="arrowchat_chatboxmessage arrowchat_clearfix arrowchat_self arrowchat_image_msg" id="arrowchat_message_' + e + '"><span class="arrowchat_ts" style="display: none;"></span><div class="arrowchat_chatboxmessagefrom arrowchat_white_background"><div class="arrowchat_disable_avatars_name">' + u_name + '</div></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="' + tooltip + '"><div class="arrowchat_giphy_message"><img class="arrowchat_lightbox arrowchat_giphy_img" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" /></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
												var time = Math.floor((new Date).getTime() / 1E3);
												var data_array = [e, popup_id, '<div class="arrowchat_giphy_message"><img class="arrowchat_lightbox" data-id="' + giphy_src + '" src="' + giphy_src + '" alt="" style="width:179px"></div>', time, 1, 1];
												lsClick(JSON.stringify(data_array), 'private_message');
												showTimeAndTooltip();
											}
										}
										a(".arrowchat_popout_convo", $user_popups[popup_id]).scrollTop(a(".arrowchat_popout_convo", $user_popups[popup_id])[0].scrollHeight);
									});
								}
							});
						}
					});
				}
			});
		}
		
		function uploadProcessing(b, chatroom) {
			var selector_id;
			if (chatroom == 1)
				selector_id = $chatrooms_popups[b];
			else
				selector_id = $user_popups[b];
			var ts67 = Math.round(new Date().getTime());
			var path = c_ac_path.replace("../", "/");
			a("#arrowchat_upload_button_" + b).uploadifive({
				'uploadScript': path + 'includes/classes/class_uploads.php',
				'buttonText': ' ',
				'buttonClass': 'arrowchat_upload_user_button',
				'removeCompleted' : true,
				'formData': {
					'unixtime': ts67,
					'user': u_id
				},
				'queueID' : 'arrowchat_user_upload_queue_' + b,
				'height' : 'auto',
				'width' : 'auto',
				'multi': false,
				'auto': true,
				'fileType': '.avi,.bmp,.doc,.docx,.gif,.ico,.jpeg,.jpg,.mp3,.mp4,.pdf,.png,.ppt,.pptx,.rar,.tar,.txt,.wav,.wmv,.xls,.xlsx,.zip,.7z',
				'fileSizeLimit' : c_max_upload_size + 'MB',
				'onError': function (file, errorCode, errorMsg, errorString) {
					a(".arrowchat_textarea", selector_id).focus();
				},
				'onCancel': function (file) {
					a(".arrowchat_textarea", selector_id).focus();
				},
				'onUploadComplete': function (file) {
					var uploadType = "file",
						fileType = file.type.toLowerCase();
					if (fileType == "image/jpeg" || fileType == "image/gif" || fileType == "image/jpg" || fileType == "image/png")
						uploadType = "image";
					
					var sendUrl = "includes/json/send/send_message.php";
					var messageID = "arrowchat_message_";
					if (chatroom == 1) {
						sendUrl = "includes/json/send/send_message_chatroom.php";
						messageID = "arrowchat_chatroom_message_";
					}
					
					a.post(c_ac_path + sendUrl, {
						userid: u_id,
						username: u_name,
						chatroomid: b,
						to: b,
						message: uploadType + "{" + ts67 + "}{" + file.name + "}"
					}, function (e) {
						if (!a.isNumeric(e)) {
							a.each(e, function (i, q) {
								if (i == "error") {
									a.each(q, function (l, f) {
										displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
									});
								}
							});
						} else {
							if (a("#" + messageID + e).length) {} else {
								var message = "";
								var tooltip = formatTimestamp(new Date(Math.floor((new Date).getTime() / 1E3) * 1E3), 1);
								if (uploadType == "image") {
									a(".arrowchat_popout_convo", selector_id).append('<div class="arrowchat_chatboxmessage arrowchat_clearfix arrowchat_self arrowchat_image_msg" id="' + messageID + e + '"><span class="arrowchat_ts" style="display: none;">' + tooltip + '</span><div class="arrowchat_chatboxmessagefrom arrowchat_white_background"><div class="arrowchat_disable_avatars_name">' + u_name + '</div></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="' + tooltip + '"><div class="arrowchat_image_message"><img data-id="' + c_ac_path + 'public/download.php?file=' + ts67 + '" src="' + c_ac_path + 'public/download.php?file=' + ts67 + '_t" alt="Image" class="arrowchat_lightbox" /></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
									message = '<div class="arrowchat_image_message"><img data-id="' + c_ac_path + 'public/download.php?file=' + ts67 + '" src="' + c_ac_path + 'public/download.php?file=' + ts67 + '_t" alt="Image" class="arrowchat_lightbox" /></div>';
									a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
										setTimeout(function () {
											a(".arrowchat_popout_convo").scrollTop(5E4);
										}, 500);
									}).each(function() {
									  if(this.complete) a(this).trigger('load');
									});
								} else {
									a(".arrowchat_popout_convo", selector_id).append('<div class="arrowchat_chatboxmessage arrowchat_clearfix arrowchat_self arrowchat_image_msg" id="' + messageID + e + '"><span class="arrowchat_ts" style="display: none;">' + tooltip + '</span><div class="arrowchat_chatboxmessagefrom arrowchat_white_background"><div class="arrowchat_disable_avatars_name">' + u_name + '</div></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="' + tooltip + '"><div class="arrowchat_action_message"><div class="arrowchat_action_message_wrapper">' + lang[69] + '</div><div class="arrowchat_action_message_action"><a href="' + c_ac_path + 'public/download.php?file=' + ts67 + '">' + file.name + '</a></div></div></div></div><div class="arrowchat_pending_icon arrowchat_pending_delivered"></div></div>');
									message = '<div class="arrowchat_action_message"><div class="arrowchat_action_message_wrapper">' + lang[69] + '</div><div class="arrowchat_action_message_action"><a href="' + c_ac_path + 'public/download.php?file=' + ts67 + '">' + file.name + '</a></div></div>';
								}
								if (chatroom == 1) {
									showChatroomTime();
									var data_array = [e, u_name, message, b];
									lsClick(JSON.stringify(data_array), 'send_chatroom_message');
								} else {
									showTimeAndTooltip();
									var time = Math.floor((new Date).getTime() / 1E3);
									var data_array = [e, b, message, time, 1, 1];
									lsClick(JSON.stringify(data_array), 'private_message');
								}
							}
						}
						a(".arrowchat_popout_convo").scrollTop(5E4);
					});
					a(".arrowchat_textarea", selector_id).focus();
					uploadProcessing(b, chatroom);
				}
			});
			a(".arrowchat_upload_user_button").mouseenter(function () {
				showTooltip(a(this), lang[310], 1, 20, -8);
				a(".arrowchat_upload_button_container").addClass("arrowchat_upload_button_hover")
			});
			a(".arrowchat_upload_user_button").mouseleave(function () {
				hideTooltip();
				a(".arrowchat_upload_button_container").removeClass("arrowchat_upload_button_hover");
			});
		}
		
		function loadChatroomList() {
			a("#arrowchat_popout_left_lists").html("");
			a(".arrowchat_nofriends").remove();
			a('<div class="arrowchat_loading_icon"></div>').appendTo("#arrowchat_popout_friends");
			a.ajax({					
				url: c_ac_path + "includes/json/receive/receive_chatroom_list.php",
				cache: false,
				type: "post",
				dataType: "json",
				success: function (b) {
					buildChatroomList(b);
				}
			});
		}
		
		function buildChatroomList(b) {
			a(".arrowchat_loading_icon").remove();
			a(".arrowchat_nofriends").remove();
			a("#arrowchat_popout_left_lists").html("");
			var c = {},
			code = "",
			featured_list = "",
			totalNumber = 0,
			other_list = "";
			b && a.each(b, function (i, e) {
				if (i == "chatrooms") {
					a.each(e, function (l, f) {
						totalNumber++;
						code = '<div id="arrowchat_chatroom_' + f.id + '" class="arrowchat_chatroom_list"><div class="arrowchat_chatroom_image"><img src="' + c_ac_path + "themes/" + u_theme + '/images/icons/' + f.img + '" alt="" /></div><div class="arrowchat_chatroom_name_wrapper"><div class="arrowchat_chatroom_name">' + f.n + '<i class="arrowchat_chatroom_status arrowchat_chatroom_' + f.t + '"></i></div><div class="arrowchat_chatroom_desc">' + f.d + '</div></div><div class="arrowchat_chatroom_status_wrapper"><div class="arrowchat_chatroom_count"><i class="fad fa-circle-user"></i><span>' + f.c + '</span></div></div><div class="arrowchat_clearfix"></div></div>';
						
						if (f.o == 1) {
							other_list += code;
						} else {
							featured_list += code;
						}
						
						cr_name[f.id] = f.n;
						cr_desc[f.id] = f.d;
						cr_welcome[f.id] = f.welcome;
						cr_img[f.id] = f.img;
						cr_type[f.id] = f.t;
						cr_count[f.id] = f.c;
						cr_other[f.id] = f.o;
					})
				}
			});
			if (totalNumber == 0) {
				a('<div class="arrowchat_nofriends">' + lang[318] + "</div>").appendTo("#arrowchat_popout_friends");
			}
			if (featured_list != "") {
				a('<div class="arrowchat_chatroom_list_title">' + lang[227] + '</div>').appendTo(a("#arrowchat_popout_left_lists"));
				a(featured_list).appendTo(a("#arrowchat_popout_left_lists"));
			}
			if (other_list != "") {
				a('<div class="arrowchat_chatroom_list_title">' + lang[228] + '</div>').appendTo(a("#arrowchat_popout_left_lists"));
				a(other_list).appendTo(a("#arrowchat_popout_left_lists"));
			}
			a(".arrowchat_chatroom_list").mouseover(function () {							
				a(this).addClass("arrowchat_chatroom_list_hover");
			}).mouseout(function () {
				a(this).removeClass("arrowchat_chatroom_list_hover");
			});
			a(".arrowchat_chatroom_list").click(function (l) {
				chatroomListClicked(a(this), 19)
			});
			a(".arrowchat_chatroom_count").mouseenter(function() {
				showTooltip(a(this), lang[35], 0, 5, 15);
			});
			a(".arrowchat_chatroom_count").mouseleave(function() {
				hideTooltip();
			});
		}
		
		function leaveChatroom(id) {
			lsClick(".arrowchat_closebox_bottom", 'ac_click', "chatrooms['"+id+"']");
			a.post(c_ac_path + "includes/json/send/send_settings.php", {
				close_chat: 'r'+id
			}, function () {});
			$chatroom_tab[id].remove();
			$chatrooms_popups[id].remove();
			delete $chatroom_tab[id];
			delete $chatrooms_popups[id];
			M();
			room_history_loaded[id] = 0;
			if (Object.keys($chatroom_tab).length < 1) {
				clearTimeout(Crref2);
				Crref2 = -1;
			}
			if (c_push_engine != 1) {
				cancelJSONP();
				receiveCore();
			}
			changePushChannel(id, 0);
			y[id] = null;
			G[id] = null;
		}
		
		function chatroomListClicked(b, length) {
			if (a(b).attr("id"))
				c = a(b).attr("id").substr(length);
			if (c == "") c = a(b).parent().attr("id").substr(length);
			if (cr_type[c] == 2) {
				a("#arrowchat_chatroom_password_id").val(c);
				if (a("#arrowchat_chatroom_" + c).hasClass("arrowchat_chatroom_clicked")) {
					a("#arrowchat_chatroom_password_flyout").hide("slide", { direction: "up"}, 250);
					a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
					a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
				} else {
					a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
					a("#arrowchat_chatroom_" + c).addClass("arrowchat_chatroom_clicked");
					if (!a("#arrowchat_chatroom_password_flyout").is(":visible")) {
						a("#arrowchat_chatroom_password_flyout").show("slide", { direction: "up"}, 250, function() {
							a("#arrowchat_chatroom_password_input").focus();
						});
						a("#arrowchat_popout_wrapper").addClass("arrowchat_chatroom_opacity");
					} else {
						a("#arrowchat_chatroom_password_flyout").hide("slide", { direction: "up"}, 250);
						a("#arrowchat_chatroom_password_flyout").show("slide", { direction: "up"}, 250, function() {
							a("#arrowchat_chatroom_password_input").focus();
						});
						a("#arrowchat_popout_wrapper").addClass("arrowchat_chatroom_opacity");
					}
				}
			} else {
				a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
				if (a("#arrowchat_chatroom_password_flyout").is(":visible")) {
					a("#arrowchat_chatroom_password_flyout").hide("slide", { direction: "up"}, 250);
				}
				addChatroomTab(c, 1);
				if (room_history_loaded[c] != 1) {
					loadChatroom(c, cr_type[c]);
					room_history_loaded[c] = 1;
				}
			}
		}
		
		function addChatroomTab(id, focused) {
			if (typeof $chatroom_tab[id] == "undefined") {
				if (typeof(cr_name[id]) == "undefined")
					cr_name[id] = lang[301];
				if (typeof(cr_desc[id]) == "undefined")
					cr_desc[id] = lang[301];
				if (typeof(cr_img[id]) == "undefined")
					cr_img[id] = "chatroom_default.png";
				if (typeof(cr_count[id]) == "undefined")
					cr_count[id] = 0;
				var img = c_ac_path + "themes/" + u_theme + '/images/icons/' + cr_img[id];
				$chatroom_tab[id] = a("<div/>").attr("data-room-id", id).addClass("arrowchat_popout_tab").html('<div class="arrowchat_chatroom_count_window arrowchat_chatroom_count_tab"><i class="fad fa-circle-user"></i><span>' + cr_count[id] + '</span></div><div class="arrowchat_bar_left"><div class="arrowchat_popout_wrap"><img class="arrowchat_avatar_tab" src="' + img + '" /><div class="arrowchat_popout_tab_name">' + cr_name[id] + '</div></div></div><div class="arrowchat_popout_right"><div class="arrowchat_closebox_bottom"><i class="fas fa-xmark"></i></div></div>').appendTo(a("#arrowchat_popout_container"));
				$chatrooms_popups[id] = a("<div/>").attr("data-room-id", id).addClass("arrowchat_popout_convo_wrapper").addClass("arrowchat_popout_chatroom_convo").html('<div class="arrowchat_chatroom_user_popouts"></div><div class="arrowchat_popout_convo_right_header"><div class="arrowchat_user_image"><img class="arrowchat_avatar" src="' + img + '"><div class="arrowchat_chatroom_count_window"><i class="fad fa-circle-user"></i><span>' + cr_count[id] + '</span></div></div><div class="arrowchat_header_container"><div class="arrowchat_right_header_name">' + cr_name[id] + '</div><div class="arrowchat_right_header_status">' + cr_desc[id] + '</div></div><div class="arrowchat_popout_room_options far fa-ellipsis" id="arrowchat_room_options_' + id + '"></div><div class="arrowchat_more_user_wrapper"><div class="arrowchat_more_popout arrowchat_more_popout_user"><ul class="arrowchat_inner_menu"><li class="arrowchat_menu_item"><a class="arrowchat_room_sounds arrowchat_menu_anchor"><i class="fa-light fa-music"></i><span>'+lang[101]+'</span><label class="arrowchat_switch"><input type="checkbox" checked="" /><span class="arrowchat_slider"></span></label></a></li><li class="arrowchat_menu_item"><a class="arrowchat_block_private_chats arrowchat_menu_anchor"><i class="fa-light fa-user-slash"></i><span>'+lang[279]+'</span><label class="arrowchat_switch"><input type="checkbox" checked="" /><span class="arrowchat_slider"></span></label></a></li><li class="arrowchat_menu_separator arrowchat_admin_controls"></li><li class="arrowchat_menu_item arrowchat_admin_controls"><a class="arrowchat_chatroom_invisible arrowchat_menu_anchor"><i class="fa-light fa-eye-slash"></i><span>'+lang[319]+'</span><label class="arrowchat_switch"><input type="checkbox" checked="" /><span class="arrowchat_slider"></span></label></a></li><li class="arrowchat_menu_item arrowchat_admin_controls"><a class="arrowchat_edit_description arrowchat_menu_anchor"><i class="fa-light fa-book-copy"></i><span>'+lang[157]+'</span></a></li><li class="arrowchat_menu_item arrowchat_admin_controls"><a class="arrowchat_edit_welcome_msg arrowchat_menu_anchor"><i class="fa-light fa-door-open"></i><span>'+lang[153]+'</span></a></li><li class="arrowchat_menu_item arrowchat_admin_controls"><a class="arrowchat_edit_flood arrowchat_menu_anchor"><i class="fa-light fa-water"></i><span>'+lang[171]+'</span></a></li></ul><div class="arrowchat_flood_menu"><div class="arrowchat_block_wrapper"><i class="fa-light fa-water"></i><span class="arrowchat_block_menu_text">'+lang[172]+'</span><div class="arrowchat_block_buttons_wrapper"><div><select class="arrowchat_flood_select_messages"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select><span>'+lang[174]+'</span></div><div><select class="arrowchat_flood_select_seconds"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="60">60</option><option value="90">90</option><option value="120">120</option></select><span>'+lang[175]+'</span></div><div class="arrowchat_ui_button arrowchat_flood_save"><div>'+lang[173]+'</div></div></div></div><div class="arrowchat_menu_separator"></div><ul><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_flood_back"><i class="fa-light fa-angles-left"></i><span>'+lang[302]+'</span></a></li></ul></div><i class="arrowchat_more_tip"></i></div></div><div class="arrowchat_popout_display_list fas fa-user" id="arrowchat_display_list_' + id + '"></div></div><div id="arrowchat_popout_text_room_' + id + '" class="arrowchat_popout_convo"></div><div class="arrowchat_popout_input_container"><div class="arrowchat_giphy_button"><i class="fas fa-gif"></i><div class="arrowchat_more_wrapper arrowchat_giphy_popout"><div class="arrowchat_more_popout"><div class="arrowchat_giphy_box"><label class="arrowchat_giphy_search_wrapper"><div class="arrowchat_giphy_magnify"><i class="far fa-magnifying-glass"></i></div><input type="text" class="arrowchat_giphy_search" placeholder="'+lang[214]+'" value="" tabindex="0" /></label><div class="arrowchat_giphy_image_wrapper"><div class="arrowchat_loading_icon"></div></div></div><i class="arrowchat_more_tip"></i></div></div></div><div id="arrowchat_upload_button_' + id + '" class="arrowchat_upload_button_container"><i class="fas fa-camera"></i><div id="arrowchat_chatroom_uploader"> </div></div><div class="arrowchat_textarea_wrapper"><textarea class="arrowchat_textarea" maxlength="' + c_max_chatroom_msg + '" placeholder="' + lang[213] + '"></textarea><div class="arrowchat_smiley_button"><i class="fa-solid fa-face-grin-wide"></i><div class="arrowchat_more_wrapper arrowchat_smiley_popout"><div class="arrowchat_more_popout"><div class="arrowchat_smiley_box"><div class="arrowchat_emoji_wrapper"></div><div class="arrowchat_emoji_select_wrapper"><div class="arrowchat_emoji_selector arrowchat_emoji_smileys" data-id="emoji_smileys"><i class="fa-solid fa-face-grin-beam"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_animals" data-id="emoji_animals"><i class="fa-solid fa-dog"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_food" data-id="emoji_food"><i class="fa-solid fa-fork-knife"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_activities" data-id="emoji_activities"><i class="fa-solid fa-basketball"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_travel" data-id="emoji_travel"><i class="fa-solid fa-plane"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_objects" data-id="emoji_objects"><i class="fa-solid fa-bath"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_symbols" data-id="emoji_symbols"><i class="fa-solid fa-symbols"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_flags" data-id="emoji_flags"><i class="fa-solid fa-flag-swallowtail"></i></div><div class="arrowchat_emoji_selector arrowchat_emoji_custom" data-id="emoji_custom"><i class="fa-solid fa-sparkles"></i></div></div></div><i class="arrowchat_more_tip"></i></div></div></div></div><div class="arrowchat_user_send_button"><i class="fa-solid fa-paper-plane-top"></i></div></div>').appendTo(a("#arrowchat_popout_chat"));
				a('<div id="arrowchat_user_upload_queue_'+id+'" class="arrowchat_users_upload_queue"></div><div class="arrowchat_info_panel" data-id="' + id + '"><div class="arrowchat_chatroom_list_title arrowchat_popout_room_admins">'+lang[148]+'</div><div class="arrowchat_chatroom_list_admins"></div><div class="arrowchat_chatroom_list_title arrowchat_popout_room_mods">'+lang[149]+'</div><div class="arrowchat_chatroom_list_mods"></div><div class="arrowchat_chatroom_list_title arrowchat_popout_room_users">'+lang[147]+'</div><div class="arrowchat_chatroom_list_users"></div></div>').prependTo($chatrooms_popups[id]);
				addHover($chatroom_tab[id], "arrowchat_tabmouseover_popout");
				a(".arrowchat_closebox_bottom", $chatroom_tab[id]).unbind('click');
				a(".arrowchat_closebox_bottom", $chatroom_tab[id]).click(function() {
					leaveChatroom(id);
				});
				a("#arrowchat_display_list_" + id).mouseenter(function () {
                    a(this).addClass("arrowchat_popout_info_hover");
					showTooltip(a(this), lang[312], false, a(this).width() + 18, 6, 1, false);
                });
				a("#arrowchat_display_list_" + id).mouseleave(function () {
                    a(this).removeClass("arrowchat_popout_info_hover");
					hideTooltip();
                });
				a("#arrowchat_display_list_" + id).click(function() {
					hideTooltip();
					if (a(".arrowchat_info_panel", $chatrooms_popups[id]).is(":visible")) {
						a(".arrowchat_info_panel", $chatrooms_popups[id]).hide();
						$chatrooms_popups[id].removeClass("arrowchat_info_panel_visible");
						a(this).removeClass("arrowchat_popout_display_list_clicked");
						a(".arrowchat_popout_convo", $chatrooms_popups[id]).css("margin-right", "0px");
						a(".arrowchat_popout_input_container", $chatrooms_popups[id]).css("margin-right", "0px");
					} else {
						a(".arrowchat_info_panel", $chatrooms_popups[id]).show();
						$chatrooms_popups[id].addClass("arrowchat_info_panel_visible");
						a(this).addClass("arrowchat_popout_display_list_clicked");
						a(".arrowchat_popout_convo", $chatrooms_popups[id]).css("margin-right", "201px");
						a(".arrowchat_popout_input_container", $chatrooms_popups[id]).css("margin-right", "201px");
					}
					a("#arrowchat_popout_text_room_" + id).scrollTop(5E4);
				});
				a("#arrowchat_room_options_" + id).mouseenter(function () {
                    a(this).addClass("arrowchat_popout_room_options_hover");
					showTooltip(a(this), lang[23], false, a(this).width() + 18, 6, 1, false);
                });
				a("#arrowchat_room_options_" + id).mouseleave(function () {
                    a(this).removeClass("arrowchat_popout_room_options_hover");
					hideTooltip();
                });
				a("#arrowchat_room_options_" + id).click(function () {
					hideTooltip();
					a(".arrowchat_more_popout_user", $chatrooms_popups[id]).toggle();
					a(this).toggleClass("arrowchat_more_button_selected");
					a(".arrowchat_inner_menu", $chatrooms_popups[id]).show();
					a(".arrowchat_flood_menu", $chatrooms_popups[id]).hide();
				}).parent().children('.arrowchat_more_user_wrapper').click(function () {
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest(a(".arrowchat_popout_room_options", $chatrooms_popups[id])).length)
						return;
						
					a(".arrowchat_more_popout_user", $chatrooms_popups[id]).hide();
					a(".arrowchat_popout_room_options", $chatrooms_popups[id]).removeClass("arrowchat_more_button_selected");
				});
				if (u_chatroom_sound == 1) { 
					a(".arrowchat_room_sounds .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", true)
				} else {
					a(".arrowchat_room_sounds").addClass("arrowchat_menu_unchecked");
					a(".arrowchat_room_sounds .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", false)
				}
				a(".arrowchat_room_sounds", $chatrooms_popups[id]).click(function () {
					a(".arrowchat_room_sounds").toggleClass("arrowchat_menu_unchecked");
					var _chatroomsound;
					if (a(".arrowchat_room_sounds :input", $chatrooms_popups[id]).is(":checked")) {
						a(".arrowchat_room_sounds :input").prop("checked", false);
						_chatroomsound = -1;
						u_chatroom_sound = 0;
					} else {
						a(".arrowchat_room_sounds :input").prop("checked", true);
						_chatroomsound = 1;
						u_chatroom_sound = 1;
					}
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_sound: _chatroomsound
					}, function () {
					});
				});
				if (u_chatroom_block_chats == 1) { 
					a(".arrowchat_block_private_chats .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", true)
				} else {
					a(".arrowchat_block_private_chats").addClass("arrowchat_menu_unchecked");
					a(".arrowchat_block_private_chats .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", false)
				}
				a(".arrowchat_block_private_chats", $chatrooms_popups[id]).click(function () {
					a(".arrowchat_block_private_chats").toggleClass("arrowchat_menu_unchecked");
					var _chatroomblock;
					if (a(".arrowchat_block_private_chats :input", $chatrooms_popups[id]).is(":checked")) {
						a(".arrowchat_block_private_chats :input").prop("checked", false);
						_chatroomblock = -1;
						u_chatroom_block_chats = 0;
					} else {
						a(".arrowchat_block_private_chats :input").prop("checked", true);
						_chatroomblock = 1;
						u_chatroom_block_chats = 1;
					}
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_block_chats: _chatroomblock
					}, function () {
					});
				});
				a(".arrowchat_textarea", $chatrooms_popups[id]).keydown(function (h) {
					return chatroomKeydown(h, a(".arrowchat_textarea", $chatrooms_popups[id]), id)
				});
				a(".arrowchat_textarea", $chatrooms_popups[id]).keyup(function (h) {
					return chatroomKeyup(h, a(this), id);
				});
				$chatroom_tab[id].unbind('click');
				$chatroom_tab[id].click(function () {
					if (a(".arrowchat_popout_alert", $chatroom_tab[id]).length > 0) {
						a(".arrowchat_popout_alert", $chatroom_tab[id]).remove();
					}
					if ($chatroom_tab[id].hasClass("arrowchat_popout_focused")) {
						a(this).removeClass("arrowchat_popout_focused");
						$chatrooms_popups[id].removeClass("arrowchat_popout_convo_focused");
						j="";
						a.post(c_ac_path + "includes/json/send/send_settings.php", {
							unfocus_chat: 'r'+id
						}, function () {})
					} else {
						a(".arrowchat_popout_tab").removeClass("arrowchat_popout_focused");
						a(".arrowchat_popout_convo_wrapper").removeClass("arrowchat_popout_convo_focused");
						if (room_history_loaded[id] != 1) {
							loadChatroom(id, cr_type[id]);
							room_history_loaded[id] = 1;
						}
						a(this).addClass("arrowchat_popout_focused");
						$chatrooms_popups[id].addClass("arrowchat_popout_convo_focused");
						if (typeof(init_open_room[id]) == "undefined")
							init_open_room[id] = 1;
						if (init_open_room[id] == 1) {
							a.post(c_ac_path + "includes/json/send/send_settings.php", {
								focus_chat: 'r'+id
							}, function () {});
							a(".arrowchat_textarea", $chatrooms_popups[id]).focus();
						}
						init_open_room[id] = 1;
					}
					a("#arrowchat_popout_text_room_" + id).scrollTop(5E4);
				});
				a(".arrowchat_closebox_bottom", $chatroom_tab[id]).mouseenter(function () {
					a(this).addClass("arrowchat_closebox_bottomhover")
				});
				a(".arrowchat_closebox_bottom", $chatroom_tab[id]).mouseleave(function () {
					a(this).removeClass("arrowchat_closebox_bottomhover")
				});
				a(".arrowchat_emoji_selector", $chatrooms_popups[id]).click(function() {
					if (!a(this).hasClass("arrowchat_emoji_focused")) {
						a(".arrowchat_emoji_wrapper", $chatrooms_popups[id]).html('<div class="arrowchat_loading_icon"></div>');
						a(".arrowchat_emoji_selector", $chatrooms_popups[id]).removeClass("arrowchat_emoji_focused");
						a(this).addClass("arrowchat_emoji_focused");
						var load_id = a(this).attr("data-id");
						a.ajax({
							url: c_ac_path + 'includes/emojis/' + load_id + '.php',
							type: "GET",
							cache: true,
							success: function(html) {
								a(".arrowchat_emoji_wrapper", $chatrooms_popups[id]).html(html);
								a(".arrowchat_emoji", $chatrooms_popups[id]).click(function () {
									if (a(this).hasClass("arrowchat_emoji_custom"))
										var smiley_code = a(this).children('img').attr("data-id");
									else
										var smiley_code = a(this).html();
									var existing_text = a(".arrowchat_textarea", $chatrooms_popups[id]).val();
									a(".arrowchat_textarea", $chatrooms_popups[id]).focus().val('').val(existing_text + smiley_code);
								});
							}
						});
					}
				});
				a(".arrowchat_emoji_selector").mouseover(function(){
					a(this).addClass("arrowchat_emoji_selector_hover");
				});
				a(".arrowchat_emoji_selector").mouseout(function(){
					a(this).removeClass("arrowchat_emoji_selector_hover");
				});
				a(".arrowchat_emoji_smileys").mouseover(function(){showTooltip(a(this), lang[230], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_animals").mouseover(function(){showTooltip(a(this), lang[231], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_food").mouseover(function(){showTooltip(a(this), lang[232], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_activities").mouseover(function(){showTooltip(a(this), lang[233], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_travel").mouseover(function(){showTooltip(a(this), lang[234], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_objects").mouseover(function(){showTooltip(a(this), lang[235], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_symbols").mouseover(function(){showTooltip(a(this), lang[236], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_flags").mouseover(function(){showTooltip(a(this), lang[237], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_emoji_custom").mouseover(function(){showTooltip(a(this), lang[238], false, 0, 20);}).mouseout(function(){hideTooltip();});
				a(".arrowchat_smiley_button", $chatrooms_popups[id]).mouseenter(function () {
					if (a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {} else {
						showTooltip(a(this), lang[307], 0, 10, 0);
					}
					a(this).addClass("arrowchat_smiley_button_hover")
				});
				a(".arrowchat_smiley_button", $chatrooms_popups[id]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_smiley_button_hover");
				});
				a(".arrowchat_smiley_button", $chatrooms_popups[id]).click(function () {
					hideTooltip();
					if (a(".arrowchat_giphy_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_giphy_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").hide();
					}
					if (a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").hide();
					} else {
						if (!a(".arrowchat_emoji_selector", $chatrooms_popups[id]).hasClass("arrowchat_emoji_focused")) {
							a(".arrowchat_emoji_wrapper", $chatrooms_popups[id]).html('<div class="arrowchat_loading_icon"></div>');
							a(".arrowchat_emoji_selector", $chatrooms_popups[id]).removeClass("arrowchat_emoji_focused");
							a(".arrowchat_emoji_smileys", $chatrooms_popups[id]).addClass("arrowchat_emoji_focused");
							a.ajax({
								url: c_ac_path + 'includes/emojis/emoji_smileys.php',
								type: "GET",
								cache: true,
								success: function(html) {
									a(".arrowchat_emoji_wrapper", $chatrooms_popups[id]).html(html);
									a(".arrowchat_emoji", $chatrooms_popups[id]).click(function () {
										if (a(this).hasClass("arrowchat_emoji_custom"))
											var smiley_code = a(this).children('img').attr("data-id");
										else
											var smiley_code = a(this).html();
										var existing_text = a(".arrowchat_textarea", $chatrooms_popups[id]).val();
										a(".arrowchat_textarea", $chatrooms_popups[id]).focus().val('').val(existing_text + smiley_code);
									});
								}
							});
						}
						a(".arrowchat_textarea", $chatrooms_popups[id]).focus();
						a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").show();
					}
				}).children().not('i').click(function(e){
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest(".arrowchat_smiley_button").length)
						return;
						
					a(".arrowchat_smiley_popout .arrowchat_more_popout").hide();
				});
				a(".arrowchat_user_send_button", $chatrooms_popups[id]).mouseenter(function () {
					showTooltip(a(this), lang[311], 0, 10, 0);
					a(this).addClass("arrowchat_user_send_button_hover")
				});
				a(".arrowchat_user_send_button", $chatrooms_popups[id]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_user_send_button_hover");
				});
				a(".arrowchat_user_send_button", $chatrooms_popups[id]).click(function () {
					hideTooltip();
					sendChatroomMessage(a(".arrowchat_textarea", $chatrooms_popups[id]), id);
				});
				a(".arrowchat_giphy_button", $chatrooms_popups[id]).mouseenter(function () {
					if (a(".arrowchat_giphy_button", $chatrooms_popups[id]).find(".arrowchat_more_popout").is(":visible")) {} else {
						showTooltip(a(this), lang[309], 1, 10, 0);
						a(this).addClass("arrowchat_giphy_button_hover");
					}
				});
				a(".arrowchat_giphy_button", $chatrooms_popups[id]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_giphy_button_hover");
				});
				a(".arrowchat_giphy_button", $chatrooms_popups[id]).click(function () {
					if (a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_smiley_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").hide();
					}
					if (a(".arrowchat_giphy_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").is(":visible")) {
						a(".arrowchat_giphy_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").hide();
					} else {
						a(".arrowchat_giphy_popout", $chatrooms_popups[id]).children(".arrowchat_more_popout").show();
						a(".arrowchat_giphy_search", $chatrooms_popups[id]).val('');
						a(".arrowchat_giphy_search", $chatrooms_popups[id]).focus();
						loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', 2, id);
					}
				}).children('.arrowchat_giphy_popout').click(function(e){
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest(".arrowchat_giphy_button").length)
						return;
						
					a(".arrowchat_giphy_button .arrowchat_more_popout").hide();
				});
				a(".arrowchat_giphy_search", $chatrooms_popups[id]).keyup(function () {
					a(".arrowchat_giphy_image_wrapper", $chatrooms_popups[id]).html('<div class="arrowchat_loading_icon"></div>');
					if (a(".arrowchat_giphy_search", $chatrooms_popups[id]).val() == '')
						loadGiphy('https://api.giphy.com/v1/gifs/trending?api_key=IOYyr4NK5ldaU&limit=20', 2, id);
					else
						loadGiphy('https://api.giphy.com/v1/gifs/search?api_key=IOYyr4NK5ldaU&limit=20&q=' + a(".arrowchat_giphy_search", $chatrooms_popups[id]).val(), 2, id);
				});
				if (c_disable_smilies == 1) {a(".arrowchat_smiley_button").hide();a(".arrowchat_popout_input_container", $chatrooms_popups[id]).addClass("arrowchat_no_smiley")}
				if (c_file_transfer != 1 || c_chatroom_transfer != 1) {a("#arrowchat_upload_button_" + id).remove();a(".arrowchat_popout_input_container", $chatrooms_popups[id]).addClass("arrowchat_no_file_upload")}
				if (c_giphy_chatroom == 1) {a(".arrowchat_giphy_button", $chatrooms_popups[id]).hide();a(".arrowchat_popout_input_container", $chatrooms_popups[id]).addClass("arrowchat_no_giphy")}
				if (c_chatroom_transfer == 1) {uploadProcessing(id, 1);}
				focused == 1 && $chatroom_tab[id].click();
			} else {
				if (!$chatroom_tab[id].hasClass("arrowchat_tabclick") && focused != 1) {
					$chatroom_tab[id].click();
                }
			}
			M();
		}
		
		function loadChatroom(b, c, pass) {
			a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
			var global_mod = 0,
				global_admin = 0,
				admin_markup = "",
				msg_history = "",
				message = "";
			chatroom_mod[b] = 0;
			chatroom_admin[b] = 0;
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_chatroom_room.php",
				data: {
					chatroomid: b,
					chatroom_pw: pass
				},
				type: "post",
				cache: false,
				dataType: "json",
				success: function (o) {
					if (o) {
						var no_error = true;
						o && a.each(o, function (i, e) {
							if (i == "error") {
								a.each(e, function (l, f) {
									no_error = false;
									if (typeof($chatroom_tab[b]) != "undefined") {
										a(".arrowchat_closebox_bottom", $chatroom_tab[b]).click();
									}
									loadChatroomList();
									displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
								});
							}
						});
						if (no_error) {
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
										if (typeof cr_name[b] == "undefined") {
											cr_name[b] = f.n;
										}
									});
								}
								if (i == "chat_users") {
									var longname,adminCount=0,modCount=0,userCount=0;
									a(".arrowchat_chatroom_list_users", $chatrooms_popups[b]).html("");
									a(".arrowchat_chatroom_list_mods", $chatrooms_popups[b]).html("");
									a(".arrowchat_chatroom_list_admins", $chatrooms_popups[b]).html("");
									a(".arrowchat_chatroom_user_popouts", $chatrooms_popups[b]).html("");
									a.each(e, function (l, f) {
										if (f.status != 'invisible') {
											if ((global_admin == 1 || global_mod == 1) && (f.t == 1 || f.t == 4)) {
												admin_markup = '<li class="arrowchat_menu_separator"></li><li class="arrowchat_menu_item"><a id="arrowchat_chatroom_make_mod_' + f.id + '" class="arrowchat_chatroom_make_mod arrowchat_menu_anchor"><i class="fa-light fa-user-crown"></i><span>' + lang[52] + '</span></a></li><li class="arrowchat_menu_item"><a id="arrowchat_chatroom_silence_user_' + f.id + '" class="arrowchat_chatroom_silence_user arrowchat_menu_anchor"><i class="fa-light fa-volume-xmark"></i><span>' + lang[161] + '</span></a></li><li class="arrowchat_menu_item"><a id="arrowchat_chatroom_ban_user_' + f.id + '" class="arrowchat_chatroom_ban_user arrowchat_menu_anchor"><i class="fa-light fa-ban"></i><span>' + lang[53] + '</span></a></li>';
											}
											if (global_admin == 1 && f.t == 2) {
												admin_markup = '<li class="arrowchat_menu_separator"></li><li class="arrowchat_menu_item"><a id="arrowchat_chatroom_remove_mod_' + f.id + '" class="arrowchat_chatroom_remove_mod arrowchat_menu_anchor"><i class="fa-light fa-circle-minus"></i><span>' + lang[54] + '</span></a></li>';
											}
											appendVal = a(".arrowchat_chatroom_list_users", $chatrooms_popups[b]);
											if (f.t == 2) {
												appendVal = a(".arrowchat_chatroom_list_mods", $chatrooms_popups[b]);
												modCount++;
											} else if (f.t == 3) {
												appendVal = a(".arrowchat_chatroom_list_admins", $chatrooms_popups[b]);
												adminCount++;
											} else
												userCount++;
											longname = renderHTMLString(f.n);
											f.n = renderHTMLString(f.n);
											var icon = ' fas fa-circle';
											if (f.status == 'away')
												icon = ' fas fa-moon';
											else if (f.status == 'busy')
												icon = ' far fa-mobile-screen';
											a("<div/>").attr('data-user-pop-id', f.id).attr("class", "arrowchat_chatroom_user").mouseover(function () {
												a(this).addClass("arrowchat_chatroom_list_hover");
											}).mouseout(function () {
												a(this).removeClass("arrowchat_chatroom_list_hover");
											}).addClass("arrowchat_chatroom_room_list").addClass('arrowchat_chatroom_admin_' + f.t).html('<img class="arrowchat_chatroom_avatar" src="' + f.a + '"/><span class="arrowchat_chatroom_room_name">' + f.n + '</span><span class="arrowchat_userscontentdot arrowchat_' + f.status + icon + '"></span>').appendTo(appendVal);
											var pm_opacity = "";
											if ((f.b == 1 && global_admin != 1) || f.id == u_id) pm_opacity = " arrowchat_no_private_msg";
											a("<div/>").attr("data-user-id", f.id).addClass("arrowchat_more_wrapper_chatroom").html('<div class="arrowchat_more_popout"><div class="arrowchat_chatroom_flyout_avatar"><img src="'+f.a+'" alt="" /></div><ul class="arrowchat_inner_menu arrowchat_chatroom_flyout_info"><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_title arrowchat_menu_anchor"><span class="arrowchat_chatroom_fullname">' + longname + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_private_message'+pm_opacity+'"><i class="fa-light fa-messages"></i><span>' + lang[41] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_block_user"><i class="fa-light fa-user-slash"></i><span>' + lang[84] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_report_user"><i class="fa-light fa-triangle-exclamation"></i><span>' + lang[167] + '</span></a></li>' + admin_markup + '</ul></div>').appendTo(a('.arrowchat_chatroom_user_popouts', $chatrooms_popups[b]));
											if (f.t == 2) {
												a("#arrowchat_chatroom_title_" + f.id, $chatrooms_popups[b]).html('<a href="'+f.l+'" target="_blank">' + longname + '</a><br/>' + lang[44]);
											} else if (f.t == 3) {
												a("#arrowchat_chatroom_title_" + f.id, $chatrooms_popups[b]).html('<a href="'+f.l+'" target="_blank">' + longname + '</a><br/>' + lang[45]);
											} else if (f.t == 4) {
												a("#arrowchat_chatroom_title_" + f.id, $chatrooms_popups[b]).html('<a href="'+f.l+'" target="_blank">' + longname + '</a><br/>' + lang[212])
											}
											addHover(a(".arrowchat_menu_item"), "arrowchat_more_hover");
											chatroomUserOptions(global_admin);
										}
										uc_cr_block[f.id] = f.b;
									});
									var sort_by_name = function(a, b) {
										return a.querySelector('.arrowchat_chatroom_room_name').innerHTML.toLowerCase().localeCompare(b.querySelector('.arrowchat_chatroom_room_name').innerHTML.toLowerCase());
									};
									var list = a(".arrowchat_chatroom_list_users > div", $chatrooms_popups[b]).get();
									list.sort(sort_by_name);
									for (var i = 0; i < list.length; i++) {
										list[i].parentNode.appendChild(list[i]);
									}
									if (userCount == 0)
										a(".arrowchat_popout_room_users", $chatrooms_popups[b]).hide();
									else
										a(".arrowchat_popout_room_users", $chatrooms_popups[b]).show();
									if (adminCount == 0)
										a(".arrowchat_popout_room_admins", $chatrooms_popups[b]).hide();
									else
										a(".arrowchat_popout_room_admins", $chatrooms_popups[b]).show();
									if (modCount == 0)
										a(".arrowchat_popout_room_mods", $chatrooms_popups[b]).hide();
									else
										a(".arrowchat_popout_room_mods", $chatrooms_popups[b]).show();
									a(".arrowchat_chatroom_admin_3").css("background-color", "#"+c_admin_bg);
									a(".arrowchat_chatroom_admin_3").css("color", "#"+c_admin_txt);
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
											var tooltip = formatTimestamp(sent_time, 1);
											if (f.global == 1) {
												msg_history += '<div class="arrowchat_chatroom_box_message arrowchat_global_chatroom_message" id="arrowchat_chatroom_message_' + f.id + '"><div class="arrowchat_chatroom_message_content' + l + '">' + formatTimestamp(sent_time) + f.m + "</div></div>"
											} else {
												var noAvatarColor = getRandomColor(fromname);
												msg_history += '<div class="arrowchat_chatroom_box_message' + l + image_msg + important + '" id="arrowchat_chatroom_message_' + f.id + '"><div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + fromname + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_chatbox_avatar_wrapper arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><img class="arrowchat_chatroom_message_avatar arrowchat_no_names" src="'+f.a+'" alt="' + fromname + '" /><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span></div><div class="arrowchat_chatroom_message_content" data-id="' + tooltip + '"><span class="arrowchat_chatroom_msg">' + f.m + '</span></div><div class="arrowchat_message_controls"><div class="arrowchat_chatroom_reply"><i class="fas fa-reply"></i></div><div class="arrowchat_chatroom_delete" data-id="' +  f.id + '"><i class="far fa-xmark"></i></div></div></div>' + pending_icon + '</div>';
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
										room_limit_msg[b] = f.limit_msg;
										room_limit_sec[b] = f.limit_sec;
									});
								}
							});
							a("#arrowchat_popout_text_room_" + b).html(msg_history);
							showChatroomTime();
							if (message != "") {
								a("#arrowchat_popout_text_room_" + b).append('<div class="arrowchat_chatroom_box_message arrowchat_chatroom_welcome_msg arrowchat_global_chatroom_message"><div class="arrowchat_chatroom_message_content">' + message + '</div></div>');
							}
							a("#arrowchat_popout_text_room_" + b + " .arrowchat_pending_icon").hide();
							a("#arrowchat_popout_text_room_" + b + " .arrowchat_chatroom_box_message .arrowchat_pending_icon:last").show();
							modDeleteControls(b);
							if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
								setAvatarVisibility(1);
							}
							a("#arrowchat_popout_text_room_" + b).scrollTop(5E4);
							//a(".arrowchat_textarea", $chatrooms_popups[b]).focus();
							if (global_admin == 1 || global_mod == 1) {
								addChatroomAdminControls(b);
							} else {
								a(".arrowchat_admin_controls", $chatrooms_popups[b]).hide();
							}
							a(".arrowchat_image_msg img,.arrowchat_emoji_text>img").one("load", function() {
								a("#arrowchat_popout_text_room_" + b).scrollTop(5E4);
							}).each(function() {
							  if(this.complete) a(this).trigger('load');
							});
						}
					}
				}
			})
		}
		
		function addChatroomAdminControls(id) {
			a('.arrowchat_admin_controls', $chatrooms_popups[id]).show();
			a(".arrowchat_edit_welcome_msg", $chatrooms_popups[id]).unbind('click');
			a(".arrowchat_edit_welcome_msg", $chatrooms_popups[id]).click(function () {
				var welcome_msg_input = prompt(lang[154], cr_welcome[id]);
				if (welcome_msg_input || welcome_msg_input  == "") {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_welcome_msg: welcome_msg_input,
						chatroom_id: id
					}, function () {
						displayMessage("arrowchat_chatroom_message_flyout", lang[155], "notice");
						cr_welcome[id] = welcome_msg_input;
						a('.arrowchat_popout_room_options', $chatrooms_popups[id]).click();
					});
				}
			});
			if (chatroom_admin[id] == 1) {
				if (u_chatroom_invisible == 1) { 
					a(".arrowchat_chatroom_invisible .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", true)
				} else {
					a(".arrowchat_chatroom_invisible").addClass("arrowchat_menu_unchecked");
					a(".arrowchat_chatroom_invisible .arrowchat_switch :input", $chatrooms_popups[id]).prop("checked", false)
				}
				a(".arrowchat_chatroom_invisible", $chatrooms_popups[id]).unbind('click');
				a(".arrowchat_chatroom_invisible", $chatrooms_popups[id]).click(function () {
					a(".arrowchat_chatroom_invisible").toggleClass("arrowchat_menu_unchecked");
					if (a(".arrowchat_chatroom_invisible :input", $chatrooms_popups[id]).is(":checked")) {
						a(".arrowchat_chatroom_invisible :input").prop("checked", false);
						_chatroominvis = -1;
						u_chatroom_invisible = 0;
					} else {
						a(".arrowchat_chatroom_invisible :input").prop("checked", true);
						_chatroominvis = 1;
						u_chatroom_invisible = 1;
					}
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_invisible: _chatroominvis
					}, function () {
					});
				});
			} else {
				a(".arrowchat_chatroom_invisible").parent(".arrowchat_admin_controls").hide();
			}
			a(".arrowchat_edit_description", $chatrooms_popups[id]).unbind('click');
			a(".arrowchat_edit_description", $chatrooms_popups[id]).click(function () {
				var desc_input = prompt(lang[158], cr_desc[id]);
				if (desc_input || desc_input  == "") {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_description: desc_input,
						chatroom_id: id
					}, function () {
						displayMessage("arrowchat_chatroom_message_flyout", lang[155], "notice");
						cr_desc[id] = desc_input;
						desc_input = renderHTMLString(desc_input);
						loadChatroomList();
						a('.arrowchat_name_description', $chatrooms_popups[id]).html(desc_input);
						a('.arrowchat_popout_room_options', $chatrooms_popups[id]).click();
					});
				}
			});
			a(".arrowchat_edit_flood", $chatrooms_popups[id]).unbind('click');
			a(".arrowchat_edit_flood", $chatrooms_popups[id]).click(function () {
				a(this).parents(".arrowchat_inner_menu").hide();
				a(".arrowchat_flood_select_messages", $chatrooms_popups[id]).val(room_limit_msg[id]);
				a(".arrowchat_flood_select_seconds", $chatrooms_popups[id]).val(room_limit_sec[id]);
				a(".arrowchat_flood_menu", $chatrooms_popups[id]).show();
			});
			a(".arrowchat_flood_back", $chatrooms_popups[id]).unbind('click');
			a(".arrowchat_flood_back", $chatrooms_popups[id]).click(function () {
				a(this).parents('.arrowchat_more_popout').find(".arrowchat_inner_menu").show();
				a(".arrowchat_flood_menu", $chatrooms_popups[id]).hide();
			});
			a(".arrowchat_flood_save", $chatrooms_popups[id]).unbind('click');
			a(".arrowchat_flood_save", $chatrooms_popups[id]).click(function () {
				a(".arrowchat_popout_room_options", $chatrooms_popups[id]).click();
				var flood_message = a(".arrowchat_flood_select_messages", $chatrooms_popups[id]).val();
				var flood_seconds = a(".arrowchat_flood_select_seconds", $chatrooms_popups[id]).val();
				if (!isNaN(flood_message) && !isNaN(flood_seconds)) {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_id: id,
						flood_message: flood_message,
						flood_seconds: flood_seconds
					}, function () {
						displayMessage("arrowchat_chatroom_message_flyout", lang[155], "notice");
						room_limit_msg[id] = flood_message;
						room_limit_sec[id] = flood_seconds;
					});
				}
			});
		}
		
		function receiveChatroom() {
			if (c_chatrooms != 1) {
				return false;
			}
			clearTimeout(Crref2);
			var admin_markup = "";
			var rooms_string = "";
			for (var i in $chatroom_tab) {
				rooms_string += i + ",";
			}
			rooms_string = rooms_string.slice(0, -1);
			a.ajax({
				url: c_ac_path + "includes/json/receive/receive_chatroom.php",
				cache: false,
				type: "post",
				data: {
					chatrooms: rooms_string
				},
				dataType: "json",
				success: function (data) {
					if (data.room_data) {
						var no_error = true;
						var open_flyout = 0;
						var open_flyout_cr_id = 0;
						if (a('.arrowchat_chatroom_create_flyout_display').length) {
							open_flyout = a('.arrowchat_chatroom_create_flyout_display').parent().attr('data-user-id');
							open_flyout_cr_id = a('.arrowchat_chatroom_create_flyout_display').parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
						}
						data.room_data && a.each(data.room_data, function (i, room) {
							if (room.error != 0) {
								no_error = false;
								if (typeof($chatroom_tab[c]) != "undefined") {
									a(".arrowchat_closebox_bottom", $chatroom_tab[c]).click();
								}
								loadChatroomList();
								displayMessage("arrowchat_chatroom_message_flyout", f.m, "error");
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
								a(".arrowchat_chatroom_list_users", $chatrooms_popups[room.id]).html("");
								a(".arrowchat_chatroom_list_mods", $chatrooms_popups[room.id]).html("");
								a(".arrowchat_chatroom_list_admins", $chatrooms_popups[room.id]).html("");
								a(".arrowchat_chatroom_user_popouts", $chatrooms_popups[room.id]).html("");
								
								a.each(room.chat_users, function (l, user) {
									if (user.status != 'invisible') {
										admin_markup = "";
										totalCount++;
										if ((chatroom_admin[room.id] == 1 || chatroom_mod[room.id] == 1) && (user.t == 1 || user.t == 4)) {
											admin_markup = '<li class="arrowchat_menu_separator"></li><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_make_mod arrowchat_menu_anchor"><i class="fa-light fa-user-crown"></i><span>' + lang[52] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_silence_user arrowchat_menu_anchor"><i class="fa-light fa-volume-xmark"></i><span>' + lang[161] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_ban_user arrowchat_menu_anchor"><i class="fa-light fa-ban"></i><span>' + lang[53] + '</span></a></li>';
										}
										if (chatroom_admin[room.id] == 1 && user.t == 2) {
											admin_markup = '<li class="arrowchat_menu_separator"></li><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_remove_mod arrowchat_menu_anchor"><i class="fa-light fa-circle-minus"></i><span>' + lang[54] + '</span></a></li>';
										}
										var appendVal = a(".arrowchat_chatroom_list_users", $chatrooms_popups[room.id]);
										if (user.t == 2) {
											appendVal = a(".arrowchat_chatroom_list_mods", $chatrooms_popups[room.id]);
											modCount++;
										} else if (user.t == 3) {
											appendVal = a(".arrowchat_chatroom_list_admins", $chatrooms_popups[room.id]);
											adminCount++;
										} else
											userCount++;
										user.n = renderHTMLString(user.n);
										var icon = ' fas fa-circle';
										if (user.status == 'away')
											icon = ' fas fa-moon';
										else if (user.status == 'busy')
											icon = ' far fa-mobile-screen';
										a("<div/>").attr('data-user-pop-id', user.id).attr("class", "arrowchat_chatroom_user").mouseover(function () {
											a(this).addClass("arrowchat_chatroom_list_hover");
										}).mouseout(function () {
											a(this).removeClass("arrowchat_chatroom_list_hover");
										}).addClass("arrowchat_chatroom_room_list").addClass('arrowchat_chatroom_admin_' + user.t).html('<img class="arrowchat_chatroom_avatar" src="' + user.a + '"/><span class="arrowchat_chatroom_room_name">' + user.n + '</span><span class="arrowchat_userscontentdot arrowchat_' + user.status + icon + '"></span>').appendTo(appendVal);
										var pm_opacity = "";
										if ((user.b == 1 && chatroom_admin[room.id] != 1) || user.id == u_id) pm_opacity = " arrowchat_no_private_msg";
										a("<div/>").attr("data-user-id", user.id).addClass("arrowchat_more_wrapper_chatroom").html('<div class="arrowchat_more_popout"><div class="arrowchat_chatroom_flyout_avatar"><img src="'+user.a+'" alt="" /></div><ul class="arrowchat_inner_menu arrowchat_chatroom_flyout_info"><li class="arrowchat_menu_item"><a class="arrowchat_chatroom_title arrowchat_menu_anchor"><span class="arrowchat_chatroom_fullname">' + user.n + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_private_message'+pm_opacity+'"><i class="fa-light fa-messages"></i><span>' + lang[41] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_block_user"><i class="fa-light fa-user-slash"></i><span>' + lang[84] + '</span></a></li><li class="arrowchat_menu_item"><a class="arrowchat_menu_anchor arrowchat_chatroom_report_user"><i class="fa-light fa-triangle-exclamation"></i><span>' + lang[167] + '</span></a></li>' + admin_markup + '</ul></div>').appendTo(a('.arrowchat_chatroom_user_popouts', $chatrooms_popups[room.id]));
										addHover(a(".arrowchat_menu_item"), "arrowchat_more_hover");
									}
									uc_avatar[user.id] = user.a;
									uc_cr_block[user.id] = user.b;
								});
								
								chatroomUserOptions(chatroom_admin[room.id]);
								var sort_by_name = function(a, b) {
									return a.querySelector('.arrowchat_chatroom_room_name').innerHTML.toLowerCase().localeCompare(b.querySelector('.arrowchat_chatroom_room_name').innerHTML.toLowerCase());
								};
								var list = a(".arrowchat_chatroom_list_users > div").get();
								list.sort(sort_by_name);
								for (var i = 0; i < list.length; i++) {
									list[i].parentNode.appendChild(list[i]);
								}
								
								if (userCount == 0)
									a(".arrowchat_popout_room_users", $chatrooms_popups[room.id]).hide();
								else
									a(".arrowchat_popout_room_users", $chatrooms_popups[room.id]).show();
								if (adminCount == 0)
									a(".arrowchat_popout_room_admins", $chatrooms_popups[room.id]).hide();
								else
									a(".arrowchat_popout_room_admins", $chatrooms_popups[room.id]).show();
								if (modCount == 0)
									a(".arrowchat_popout_room_mods", $chatrooms_popups[room.id]).hide();
								else
									a(".arrowchat_popout_room_mods", $chatrooms_popups[room.id]).show();
								
								modDeleteControls(room.id);
								if (chatroom_admin[room.id] == 1 || chatroom_mod[room.id] == 1) {
									addChatroomAdminControls(room.id);
								} else {
									a('.arrowchat_admin_controls', $chatrooms_popups[room.id]).hide();
								}
								
								a('.arrowchat_chatroom_count_window span', $chatrooms_popups[room.id]).html(totalCount);
								a('.arrowchat_chatroom_count_window span', $chatroom_tab[room.id]).html(totalCount);
								a('#arrowchat_chatroom_' + room.id).find('.arrowchat_chatroom_count').children('span').html(totalCount);
							}
						});
						
						if (no_error) {
							a(".arrowchat_chatroom_admin_3").css("background-color", "#"+c_admin_bg);
							a(".arrowchat_chatroom_admin_3").css("color", "#"+c_admin_txt);
							if (open_flyout != 0) {
								a('div[data-user-pop-id="'+open_flyout+'"]', $chatrooms_popups[open_flyout_cr_id]).click();
							}
							if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
								setAvatarVisibility(1);
							}
							if (a("#arrowchat_chatroom_show_names :input").is(":checked")) {
								setAvatarVisibility(0);
							}
						}
					}
				}
			});
			clearTimeout(Crref2);
			Crref2 = setTimeout(function () {
				receiveChatroom()
			}, 6E4);
		}
		
		function showChatroomTime() {
			a(".arrowchat_chatroom_msg:not(.arrowchat_global_chatroom_message)").unbind('mouseenter');
			a(".arrowchat_chatroom_msg:not(.arrowchat_global_chatroom_message)").mouseenter(function (e) {
				if (a(this).parents('.arrowchat_chatroom_box_message').hasClass('arrowchat_image_msg'))
					showTooltip(a(this), a(this).parent().attr("data-id"), false, a(this).parent().width() + 23, 6, 1, false);
				else
					showTooltip(a(this), a(this).parent().attr("data-id"), false, a(this).parent().width() - 5, -3, 1, false);
				if (!a(this).parents('.arrowchat_chatroom_box_message').hasClass('arrowchat_self')) {
					a(this).parents(".arrowchat_chatroom_msg_wrap").find(".arrowchat_message_controls").addClass("arrowchat_message_controls_display");
				}
			});
			a(".arrowchat_chatroom_msg:not(.arrowchat_global_chatroom_message)").unbind('mouseleave');
			a(".arrowchat_chatroom_msg:not(.arrowchat_global_chatroom_message)").mouseleave(function (e) {
				if(!a(e.relatedTarget).hasClass('arrowchat_message_controls')) {
					hideTooltip();
					a(".arrowchat_message_controls").removeClass("arrowchat_message_controls_display");
				}
			});
			a(".arrowchat_message_controls").unbind('mouseleave');
			a(".arrowchat_message_controls").mouseleave(function (e) {
				if(!a(e.relatedTarget).hasClass('arrowchat_chatroom_msg')) {
					hideTooltip();
					a(".arrowchat_message_controls").removeClass("arrowchat_message_controls_display");
				}
			});
			a(".arrowchat_chatroom_reply").unbind("mouseenter").unbind("mouseleave").unbind("click");
			a(".arrowchat_chatroom_reply").mouseenter(function () {
				showTooltip(a(this), lang[313], 0, 10, 10);
				a(this).addClass("arrowchat_chatroom_reply_hover");
			});
			a(".arrowchat_chatroom_reply").mouseleave(function () {
				hideTooltip();
				a(this).removeClass("arrowchat_chatroom_reply_hover");
			});
			a(".arrowchat_chatroom_reply").click(function () {
				hideTooltip();
				a(".arrowchat_message_controls").removeClass("arrowchat_message_controls_display");
				var id = a(this).parents('.arrowchat_tabpopup').attr('data-room-id');
				var mention_name = a(this).parents('.arrowchat_chatroom_box_message').find('.arrowchat_chatroom_message_avatar').attr('alt');
				if (mention_name.charAt(mention_name.length-1) == ":")
					mention_name = mention_name.substring(0, mention_name.length - 1);
				var mention_full = '@' + mention_name + ' ';
				var existing_text = a(".arrowchat_textarea", $chatrooms_popups[id]).val();
				if (existing_text != "") {
					if (existing_text.charAt(existing_text.length-1) != " ")
						mention_full = ' ' + mention_full;
				}
				a(".arrowchat_textarea", $chatrooms_popups[id]).focus().val('').val(existing_text + mention_full);
			});
			a(".arrowchat_lightbox").unbind('click');
			a(".arrowchat_lightbox").click(function (){
				a.slimbox(a(this).attr('data-id'), '<a href="'+a(this).attr('data-id')+'">'+lang[70]+'</a>', {resizeDuration:1, overlayFadeDuration:1, imageFadeDuration:1, captionAnimationDuration:1});
			});
		}
		
		function chatroomUserOptions(is_admin) {
			a(".arrowchat_chatroom_make_mod").unbind('click');
			a(".arrowchat_chatroom_make_mod").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					chatroom_mod: clicked_id,
					chatroom_id: chatroom_id
				}, function () {receiveChatroom();});
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			a(".arrowchat_chatroom_remove_mod").unbind('click');
			a(".arrowchat_chatroom_remove_mod").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					chatroom_remove_mod: clicked_id,
					chatroom_id: chatroom_id
				}, function () {receiveChatroom();});
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			a(".arrowchat_chatroom_block_user").unbind('click');
			a(".arrowchat_chatroom_block_user").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					block_chat: clicked_id
				}, function (json_data) {
					if (json_data != "-1") {
						if (typeof(blockList[clicked_id]) == "undefined") {
							blockList[clicked_id] = clicked_id;
						}
						loadBuddyList();
						displayMessage("arrowchat_chatroom_message_flyout", lang[103], "notice");
					}
				});
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			if (c_enable_moderation != 1) a(".arrowchat_chatroom_report_user").parent().hide();
			a(".arrowchat_chatroom_report_user").unbind('click');
			a(".arrowchat_chatroom_report_user").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					report_about: clicked_id,
					report_from: u_id,
					report_chatroom: chatroom_id
				}, function (json_data) {
					displayMessage("arrowchat_chatroom_message_flyout", lang[168], "notice");
				});
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			a(".arrowchat_chatroom_ban_user").unbind('click');
			a(".arrowchat_chatroom_ban_user").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				var ban_length = prompt(lang[57]);
				if (ban_length != null && ban_length != "" && !(isNaN(ban_length))) {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_ban: clicked_id,
						chatroom_id: chatroom_id,
						chatroom_ban_length: ban_length
					}, function () {receiveChatroom();});
				}
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			a(".arrowchat_chatroom_silence_user").unbind('click');
			a(".arrowchat_chatroom_silence_user").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				var silence_length = prompt(lang[162]);
				if (silence_length != null && silence_length != "" && !(isNaN(silence_length))) {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chatroom_silence: clicked_id,
						chatroom_id: chatroom_id,
						chatroom_silence_length: silence_length
					}, function () {});
				}
				toggleChatroomUserInfo(a(this), clicked_id);
			});
			a(".arrowchat_chatroom_private_message").unbind('click');
			a(".arrowchat_chatroom_private_message").click(function () {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				if (u_id != clicked_id) {
					if (uc_cr_block[clicked_id] != 1 || chatroom_admin[clicked_id] == 1 || chatroom_mod[clicked_id] == 1) {
						toggleChatroomUserInfo(a(this), clicked_id);
						jqac.arrowchat.chatWith(clicked_id);
					}
				}
			});
			a(".arrowchat_chatroom_title").unbind('click');
			a(".arrowchat_chatroom_title").click(function() {
				var clicked_id = a(this).parents('.arrowchat_more_wrapper_chatroom').attr('data-user-id');
				if (typeof(uc_link[clicked_id]) != "undefined")
					window.open(uc_link[clicked_id], "_blank");
			});
			a(".arrowchat_chatroom_user").unbind('click');
			a(".arrowchat_chatroom_user").click(function () {
				var chatroom_id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
				var clicked_id = a(this).attr('data-user-pop-id');
				a(".arrowchat_chatroom_user").removeClass("arrowchat_chatroom_clicked");
				a('.arrowchat_more_popout').removeClass("arrowchat_chatroom_create_flyout_display");
				a(this).toggleClass("arrowchat_chatroom_clicked");
				a('div[data-user-id="'+clicked_id+'"]', $chatrooms_popups[chatroom_id]).children('.arrowchat_more_popout').toggleClass("arrowchat_chatroom_create_flyout_display");
			});
			a('body').click(function(evt){
				if(a(evt.target).closest('.arrowchat_chatroom_user').length)
					return;
					
				if(a(evt.target).closest('.arrowchat_more_popout').length)
					return;
					
				a(".arrowchat_chatroom_user").removeClass("arrowchat_chatroom_clicked");
				a('.arrowchat_more_popout').removeClass("arrowchat_chatroom_create_flyout_display");
			});
		}

		function toggleChatroomUserInfo(element, clicked_id) {
			var chatroom_id = element.parents('.arrowchat_tabpopup').attr('data-room-id');
			a('div[data-user-pop-id="'+clicked_id+'"]', $chatrooms_popups[chatroom_id]).toggleClass("arrowchat_chatroom_clicked");
			a('div[data-user-id="'+clicked_id+'"]', $chatrooms_popups[chatroom_id]).children('.arrowchat_more_popout').toggleClass("arrowchat_chatroom_create_flyout_display");
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
			var tooltip = formatTimestamp(new Date(Math.floor((new Date).getTime() / 1E3) * 1E3), 1);
			if (a("#arrowchat_chatroom_message_" + b).length > 0) {
			} else {
				if (b != 0) {
					var id_element = ' id="arrowchat_chatroom_message_' + b + '" ';
				} else {
					var id_element = "";
				}
				a("#arrowchat_popout_text_room_" + id).append('<div class="arrowchat_chatroom_box_message arrowchat_self' + important + image_msg + pending_class + pending_class_id + '" ' + id_element + '><div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + c + '</div><div class="arrowchat_chatroom_msg_wrap"><img class="arrowchat_chatroom_message_avatar arrowchat_no_names" src="'+u_avatar+'" alt="' + c + '" /><div class="arrowchat_chatroom_message_content" data-id="' + tooltip + '"><span class="arrowchat_chatroom_msg">' + d + '</span></div><div class="arrowchat_message_controls"><div class="arrowchat_chatroom_reply"><i class="fas fa-reply"></i></div><div class="arrowchat_chatroom_delete" data-id="' +  b + '"><i class="far fa-xmark"></i></div></div></div>' + pending_icon + '</div>');
				a("#arrowchat_popout_text_room_" + id).scrollTop(5E4);
			}
			if (id != 0) {
				showChatroomTime();
				modDeleteControls(id);
			}
		}
		
		function modDeleteControls(chatroomid) {
			if (chatroom_mod[chatroomid] == 1 || chatroom_admin[chatroomid] == 1) {
				a(".arrowchat_chatroom_delete", $chatrooms_popups[chatroomid]).show();
				a(".arrowchat_chatroom_delete", $chatrooms_popups[chatroomid]).unbind("mouseenter").unbind("mouseleave").unbind("click");
				a(".arrowchat_chatroom_delete", $chatrooms_popups[chatroomid]).mouseenter(function () {
					showTooltip(a(this), lang[160], 0, 10, 10);
					a(this).addClass("arrowchat_chatroom_delete_hover")
				});
				a(".arrowchat_chatroom_delete", $chatrooms_popups[chatroomid]).mouseleave(function () {
					hideTooltip();
					a(this).removeClass("arrowchat_chatroom_delete_hover");
				});
				a(".arrowchat_chatroom_delete").click(function () {
					hideTooltip();
					var msg_id = a(this).attr('data-id');
					var id = a(this).parents('.arrowchat_popout_chatroom_convo').attr('data-room-id');
					a("#arrowchat_chatroom_message_" + msg_id + " .arrowchat_chatroom_delete").remove();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						delete_msg: msg_id,
						chatroom_id: id,
						delete_name: u_name
					}, function () {
						a("#arrowchat_chatroom_message_" + msg_id + " .arrowchat_chatroom_msg").html(lang[159] + u_name);
					})
				});
			} else {
				a(".arrowchat_chatroom_delete", $chatrooms_popups[chatroomid]).hide();
			}
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
				if (c_push_engine == 1 && typeof(push_room[id]) != "undefined") {
					push_room[id].unsubscribe();
				}
				if (typeof(chatroom_list[id]) != "undefined") {
					delete chatroom_list[id];
				}
			}
		}
		
		function startCreateChatRoom() {
			var i = a(".arrowchat_room_name_input").val();
			var passinput = a(".arrowchat_room_password_input").val();
			var descinput = "";
			var welcomeinput = "";
			a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
			a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
			a(".arrowchat_room_name_input").val("");
			a(".arrowchat_room_password_input").val("");
			i = i.replace(/^\s+|\s+$/g, "");
			i != "" && a.post(c_ac_path + "includes/json/send/send_chatroom_create.php", {
				userid: u_id,
				name: i,
				password: passinput,
				description: descinput,
				welcome: welcomeinput
			}, function (e) {
				if (e) {
					a("#arrowchat_create_room_flyout").removeClass("arrowchat_create_room_flyout_display");
					a(".arrowchat_room_create").hide();
					if (e == "-1") {
						displayMessage("arrowchat_chatroom_message_flyout", lang[39], "error");
					} else if (e == "-2") {
						displayMessage("arrowchat_chatroom_message_flyout", lang[40], "error");
					} else {
						loadChatroomList();
					}
				}
			});
		}
		
		function loadChatroomInit() {
			a("#arrowchat_password_cancel_button").click(function () {
				a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
				a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
				a("#arrowchat_chatroom_password_flyout").hide("slide", { direction: "up"}, 250);
			});
			a("#arrowchat_password_button").click(function () {
				c = a("#arrowchat_chatroom_password_id").val();
				a("#arrowchat_chatroom_password_flyout").hide();
				a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
				a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
				input_value = a("#arrowchat_chatroom_password_input").val();
				a("#arrowchat_chatroom_password_input").val("");
				input_value = input_value.replace(/^\s+|\s+$/g, "");
				loadChatroom(c, cr_type[c], input_value)
			});
			a('body').click(function(evt){
				if(a(evt.target).closest('#arrowchat_chatroom_password_flyout').length || a(evt.target).closest('.arrowchat_chatroom_list').length)
					return;
					
				a("#arrowchat_chatroom_password_flyout").hide();
				a("#arrowchat_popout_wrapper").removeClass("arrowchat_chatroom_opacity");
				a(".arrowchat_chatroom_list").removeClass("arrowchat_chatroom_clicked");
			});
			if (c_user_chatrooms == 1) {
				a("#arrowchat_room_selection").mouseenter(function() {
					a(".arrowchat_room_create").show();
				});
				a("#arrowchat_room_selection").mouseleave(function() {
					if (!a("#arrowchat_create_room_flyout").hasClass("arrowchat_create_room_flyout_display"))
						a(".arrowchat_room_create").hide();
				});
				a(".arrowchat_room_create").mouseenter(function() {
					showTooltip(a(this), lang[93], 0, 10, 5);
					a(this).addClass("arrowchat_room_create_hover");
				}).children().mouseenter(function () {
					return false;
				});
				a(".arrowchat_room_create").mouseleave(function() {
					hideTooltip();
					a(this).removeClass("arrowchat_room_create_hover");
				}).children().mouseleave(function () {
					return false;
				});
				a(".arrowchat_room_create").click(function () {
					hideTooltip();
					a("#arrowchat_create_room_flyout").toggleClass("arrowchat_create_room_flyout_display");
					a(this).toggleClass("arrowchat_room_create_selected");
					a('.arrowchat_create_input').focus();
				}).children().click(function () {
					return false;
				});
				a('body').click(function(evt){
					if(a(evt.target).closest('.arrowchat_room_create').length)
						return;
						
					a("#arrowchat_create_room_flyout").removeClass("arrowchat_create_room_flyout_display");
				});
				a(".arrowchat_create_password_wrapper").click(function() {
					a(".arrowchat_password_input_wrapper").show();
					a(this).hide();
					a(".arrowchat_room_password_input").focus();
				});
				a(".arrowchat_room_name_input").keydown(function (h) {
					if (h.keyCode == 13) {
						startCreateChatRoom();
					}
				});
				a("#arrowchat_create_room_button").click(function() {
					startCreateChatRoom();
				});
				a(".arrowchat_room_password_input").placeholder();
				a(".arrowchat_room_name_input").placeholder();
			}
		}
		
		function displayMessage(id, message, type) {
			clearTimeout(message_timeout);
			if (a("#" + id).is(":visible")) {
				a("#" + id).hide("slide", { direction: "up"}, 250, function() {					
					a("#" + id + " .arrowchat_message_text").html(message);
					type == "error" && a(".arrowchat_message_box").addClass("arrowchat_message_box_error").removeClass("arrowchat_message_box_notice");
					type == "notice" && a(".arrowchat_message_box").addClass("arrowchat_message_box_notice").removeClass("arrowchat_message_box_error");
					a("#" + id).show("slide", { direction: "up"}, 250);
				});
			} else {
				type == "error" && a(".arrowchat_message_box").addClass("arrowchat_message_box_error").removeClass("arrowchat_message_box_notice");
				type == "notice" && a(".arrowchat_message_box").addClass("arrowchat_message_box_notice").removeClass("arrowchat_message_box_error");
				a("#" + id + " .arrowchat_message_text").html(message);
				a("#" + id).show("slide", { direction: "up"}, 250);
			}
			message_timeout = setTimeout(function () {
				a("#" + id).hide("slide", { direction: "up"}, 250);
			}, 5000);
		}
		
		function stripslashes(str) {
			str=str.replace(/\\'/g,'\'');
			str=str.replace(/\\"/g,'"');
			str=str.replace(/\\0/g,'\0');
			str=str.replace(/\\\\/g,'\\');
			return str;
		}
		
		function receiveMessage(id, from, message, sent, self, old, multi_tab, pending_msg) {
			if (pending_msg == 0 && self == 1 && multi_tab == 0) {
				return false;
			}
			aa[from] != 1 && receiveUser(from, uc_name[from], uc_status[from], uc_avatar[from], uc_link[from], 1, 1);
			if (pending_msg == 0 && self != 1) {
				var data_array = [id, from, message, sent, self, old];
				acsi != 1 && lsClick(JSON.stringify(data_array), 'private_message');
			}
			clearTimeout(dtit3);
			document.title = dtit;
			if (uc_name[from] == null || uc_name[from] == "") setTimeout(function () {
				receiveMessage(id, from, message, sent, self, old, multi_tab, pending_msg)
			}, 500);
			else {
				lsClick(from, 'untyping');
				receiveNotTyping(from);
				var container = a("#arrowchat_popout_text_" + from)[0].scrollHeight - a("#arrowchat_popout_text_" + from).scrollTop() - 10;
				var container2 = a("#arrowchat_popout_text_" + from).outerHeight();
				var o = uc_name[from];
				if (uc_status[from] == "offline" && self != 1) {
					const unixTime = Math.floor(Date.now() / 1000);
					if ((unixTime - sent) < 10) {
						loadBuddyList();
					}
				}
				f = "";
				if (self == 1) {
					fromname = u_name;
					fromid = u_id;
					f = " arrowchat_self";
					avatar = u_avatar;
					var pending_icon = "<div class='arrowchat_pending_icon'></div>";
					if (multi_tab == 1) {
						pending_icon = "<div class='arrowchat_pending_icon arrowchat_pending_delivered'></div>";
					}
				} else {
					DTitChange(uc_name[from]);
					fromname = o;
					fromid = from;
					avatar = uc_avatar[from];
					var pending_icon = "";
					
					a(".arrowchat_pending_icon", $user_popups[from]).hide();
					a(".arrowchat_pending_icon:last", $user_popups[from]).show();
				}
				var pending_class = "";
				var pending_class_id = "";
				if (pending_msg != 0) {
					pending_class = " arrowchat_pending_msg";
					pending_class_id = ' arrowchat_pending_msg_' + pending_msg;
				}
				tooltip = formatTimestamp(new Date(sent * 1E3), 1);
				var image_msg = "";
				var show_time_class = "";
				message = stripslashes(message);
				if (multi_tab == 1) {
					message = youTubeEmbed(message);
				}
				message = replaceURLWithHTMLLinks(message);
				if (multi_tab == 1) {
					message = smileyreplace(message);
				}
				if (message.substr(0, 4) == "<div") {
					image_msg = " arrowchat_image_msg";
				}
				if (last_sent[from] == null || sent - last_sent[from] > 180) {
					show_time_class = " arrowchat_show_time";
				}
				if (a("#arrowchat_message_" + id).length > 0 && pending_msg == 0) {
					a("#arrowchat_message_" + id + " .arrowchat_chatboxmessagecontent").html(message);
				} else {
					if (c_show_full_name != 1) {
						if (fromname.indexOf(" ") != -1) fromname = fromname.slice(0, fromname.indexOf(" "));
					}
					if (id != 0) {
						var id_element = ' id="arrowchat_message_' + id + '" ';
					} else {
						var id_element = "";
					}
					var noAvatarColor = getRandomColor(fromname);
					a(".arrowchat_popout_convo", $user_popups[from]).append('<div class="arrowchat_chatboxmessage arrowchat_clearfix' + f + image_msg + show_time_class + pending_class + pending_class_id + '" ' + id_element + '>' + formatTimestamp(new Date(sent * 1E3)) + '<div class="arrowchat_chatboxmessagefrom arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img alt="' + fromname + '" class="arrowchat_chatbox_avatar" src="' + avatar + '" /></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="' + tooltip + '">' + message + '</div></div>' + pending_icon + '</div>');
					if (a("#arrowchat_message_" + last_id[from]).length && self != 1) {
						a("#arrowchat_message_" + last_id[from]).children('.arrowchat_chatboxmessagefrom').addClass('arrowchat_single_avatar_hide');
					}
					last_sent[from] = sent;
					last_name[from] = fromid;
					
					if (pending_msg == 0) {
						last_id[from] = id;
					}
					
					if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
						setAvatarVisibility(1);
					}
				}
				if (container <= container2 || !$users[from].hasClass("arrowchat_popout_focused")) {
					a("#arrowchat_popout_text_" + from).scrollTop(5E4);
					a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
						setTimeout(function () {
							a("#arrowchat_popout_text_" + from).scrollTop(5E4);
						}, 500);
					}).each(function() {
					  if(this.complete) a(this).trigger('load');
					});
				} else {
					displayMessage("arrowchat_chatroom_message_flyout", lang[134], "notice");
				}
				
				notifyNewMessage(from, 1);
			}
		}
		
		function receiveTyping(id) {
			if (a("#arrowchat_popout_text_" + id).length) {
				var container = a("#arrowchat_popout_text_" + id)[0].scrollHeight - a("#arrowchat_popout_text_" + id).scrollTop() - 10;
				var container2 = a("#arrowchat_popout_text_" + id).outerHeight();
				a(".arrowchat_closed_status", $users[id]).addClass("arrowchat_typing");
				a(".arrowchat_is_typing", $users[id]).show();
				if (a("#arrowchat_popout_text_" + id + " #arrowchat_typing_message_" + id).length) {
					a("#arrowchat_popout_text_" + id + " #arrowchat_typing_message_" + id).remove();
				}
				var noAvatarColor = getRandomColor(uc_name[id]);
				a("#arrowchat_popout_text_" + id).append('<div class="arrowchat_chatboxmessage arrowchat_clearfix" id="arrowchat_typing_message_' + id + '"><div class="arrowchat_chatboxmessagefrom arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span><img alt="" class="arrowchat_chatbox_avatar" src="' + uc_avatar[id] + '" /></div><div class="arrowchat_chatboxmessage_wrapper"><div class="arrowchat_chatboxmessagecontent" data-id="Typing"><div class="arrowchat_is_typing arrowchat_is_typing_chat"><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div><div class="arrowchat_typing_bubble"></div></div></div></div></div>');
				if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
					setAvatarVisibility(1);
				}
				if (container <= container2 || !$users[id].hasClass("arrowchat_popout_focused")) {
					a("#arrowchat_popout_text_" + id).scrollTop(5E4);
				}
				clearTimeout(typingTimeout);
				typingTimeout = setTimeout(function () {
					lsClick(id, 'untyping');
					receiveNotTyping(id)
				}, 30000);
			}
		}
		
		function receiveNotTyping(id) {
			if (a("#arrowchat_popout_text_" + id).length) {
				clearTimeout(typingTimeout);
				if (a("#arrowchat_typing_message_" + id).length) {
					a("#arrowchat_typing_message_" + id).remove();
				}
				a(".arrowchat_closed_status", $users[id]).removeClass("arrowchat_typing");
				a(".arrowchat_is_typing", $users[id]).hide();
			}
		}
		
		function receiveWarning(h) {
			if (h.read == 0 && h.data != "") {
				a("#arrowchat_warnings").remove();
				a("#arrowchat_warning_background").remove();
				$body.append('<div id="arrowchat_warning_background"></div><div id="arrowchat_warnings"><div class="arrowchat_warnings_content"><div class="arrowchat_warning_icon"><i class="fad fa-triangle-exclamation"></i></div><div class="arrowchat_warning_message">'+lang[199]+'</div><div class="arrowchat_warning_reason">'+h.data+'</div><div class="arrowchat_warnings_close_div"><div class="arrowchat_warnings_close arrowchat_ui_button"><div><i class="fa-regular fa-user-check"></i>'+lang[198]+'</div></div></div></div></div>');
				a("#arrowchat_warnings .arrowchat_warnings_close").click(function () {
					a("#arrowchat_warnings").remove();
					a("#arrowchat_warning_background").remove();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						warning_read: 1
					}, function () {});
				});
			} else {
				a("#arrowchat_warnings").remove();
				a("#arrowchat_warning_background").remove();
			}
		}
		
		function chatroomAlerts(count, chatroomid) {
			if (typeof($chatroom_tab[chatroomid]) != "undefined") {
				if ($chatroom_tab[chatroomid].hasClass("arrowchat_popout_focused")) {
					return false;
				}
				if (a(".arrowchat_popout_alert", $chatroom_tab[chatroomid]).length > 0) {
					count = parseInt(a(".arrowchat_popout_alert", $chatroom_tab[chatroomid]).html()) + parseInt(count);
				}
				if (count == 0) {
					$chatroom_tab[chatroomid].removeClass("arrowchat_tab_new_message");
					a(".arrowchat_popout_alert", $chatroom_tab[chatroomid]).remove()
				} else {
					if (a(".arrowchat_popout_alert", $chatroom_tab[chatroomid]).length > 0) {
						a(".arrowchat_popout_alert", $chatroom_tab[chatroomid]).html(count);
					} else a("<div/>").addClass("arrowchat_popout_alert").html(count).prependTo(a(".arrowchat_popout_wrap", $chatroom_tab[chatroomid]));
					$chatroom_tab[chatroomid].removeClass("arrowchat_tab_new_message").addClass("arrowchat_tab_new_message")
				}
				
				M();
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
					url: c_ac_path + "includes/json/receive/receive_user.php",
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
			var container = a("#arrowchat_popout_text_room_" + chatroomid)[0].scrollHeight - a("#arrowchat_popout_text_room_" + chatroomid).scrollTop() - 10;
			var container2 = a("#arrowchat_popout_text_room_" + chatroomid).outerHeight();
			var title = "", l = "", important = "", image_msg = "";
			if (userid == u_id) {
				l = "arrowchat_self";
			}
			if (mod == 1) {
				title = lang[137];
				important = "arrowchat_chatroom_important";
			}
			if (admin == 1) {
				title = lang[136];
				important = "arrowchat_chatroom_important";
			}
			if (message.substr(0, 4) == "<div") {
				image_msg = " arrowchat_image_msg";
			}
			a(".arrowchat_pending_icon", $chatrooms_popups[chatroomid]).hide();
			a(".arrowchat_chatroom_box_message .arrowchat_pending_icon:last", $chatrooms_popups[chatroomid]).show();
			var regex = new RegExp('(^|\\s)(@' + u_name + ')(\\s|$)', 'i');
			message = message.replace(regex, '$1<span class="arrowchat_at_user">$2</span>$3');
			if (a("#arrowchat_chatroom_message_" + id).length > 0) {
				a("#arrowchat_chatroom_message_" + id + " .arrowchat_chatboxmessagecontent").html(message);
				if (userid == u_id) {
					a("#arrowchat_chatroom_message_" + id).addClass(l);
				}
			} else {
				var tooltip = formatTimestamp(time, 1);
				if (global == 1) {
					a("<div/>").attr("id", "arrowchat_chatroom_message_" + id).addClass("arrowchat_chatroom_box_message").addClass("arrowchat_global_chatroom_message").html('<div class="arrowchat_chatroom_message_content">' + formatTimestamp(time) + message + "</div>").appendTo("#arrowchat_popout_text_room_" + chatroomid);
					receiveChatroom();
				} else {
					var noAvatarColor = getRandomColor(name);
					a("<div/>").attr("id", "arrowchat_chatroom_message_" + id).addClass(important).addClass(image_msg).addClass(l).addClass("arrowchat_chatboxmessage").addClass("arrowchat_popout_room_msg").html('<div class="arrowchat_chatroom_message_name"><i class="' + title + '"></i>' + name + '</div><div class="arrowchat_chatroom_msg_wrap"><div class="arrowchat_chatbox_avatar_wrapper arrowchat_white_background" style="background-color:' + noAvatarColor["color"] + '"><img class="arrowchat_chatroom_message_avatar arrowchat_no_names" src="'+image+'" alt="' + name + '" /><span class="arrowchat_tab_letter arrowchat_tab_letter_xsmall">' + noAvatarColor["character"] + '</span></div><div class="arrowchat_chatroom_message_content" data-id="' + tooltip + '"><span class="arrowchat_chatroom_msg">' + message + '</span></div><div class="arrowchat_message_controls"><div class="arrowchat_chatroom_reply"><i class="fas fa-reply"></i></div><div class="arrowchat_chatroom_delete" data-id="' +  id + '"><i class="far fa-xmark"></i></div></div></div>').appendTo("#arrowchat_popout_text_room_" + chatroomid);
				}
				
				if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) {
					setAvatarVisibility(1);
				}
				if (container <= container2) {
					a("#arrowchat_popout_text_room_" + chatroomid).scrollTop(5E4);
					a(".arrowchat_image_msg img,.arrowchat_emoji_text>img").one("load", function() {
						setTimeout(function () {
							a("#arrowchat_popout_text_room_" + chatroomid).scrollTop(5E4);
						}, 500);
					}).each(function() {
					  if(this.complete) a(this).trigger('load');
					});
				} else {
					displayMessage("arrowchat_chatroom_message_flyout", lang[134], "notice");
				}
				showChatroomTime();
				modDeleteControls(chatroomid);
			}
		}
		
		function receiveAnnouncement(h) {
			if (h.read == 0 && h.data != "") {
				a("#arrowchat_announcement").remove();
				$body.append('<div id="arrowchat_announcement"><div class="arrowchat_announcement_content">'+h.data+'<div class="arrowchat_announce_close_div"><div class="arrowchat_announce_close arrowchat_ui_button"><i class="fa-regular fa-xmark"></i>'+lang[28]+'</div></div></div><div class="arrowchat_announcement_tip_pos"></div></div>');
				a("#arrowchat_announcement .arrowchat_announce_close").click(function () {
					a("#arrowchat_announcement").remove();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						announce: 1
					}, function () {});
				});
			} else {
				a("#arrowchat_announcement").remove();
			}
		}
		
		function pushSubscribe() {
			if (c_push_engine == 1) {
				push_uid = push.subscribe(c_push_encrypt + "_u"+u_id);
				push_arrowchat = push.subscribe(c_push_encrypt + "_arrowchat");
		
				push_arrowchat.on('data', function (data) {
					pushReceive(data);
				});
				push_uid.on('data', function (data) {
					pushReceive(data);
				});
			}
		}
		
		function pushReceive(data) {
			if ("announcement" in data) {
				receiveAnnouncement(data.announcement);
			}
			if ("typing" in data) {
				lsClick(data.typing.id, 'typing');
				receiveTyping(data.typing.id);
			}
			if ("warning" in data) {
				receiveWarning(data.warning);
			}
			if ("nottyping" in data) {
				lsClick(data.nottyping.id, 'untyping');
				receiveNotTyping(data.nottyping.id);
			}
			if ("messages" in data) {
				receiveMessage(data.messages.id, data.messages.from, data.messages.message, data.messages.sent, data.messages.self, data.messages.old, 0, 0);
				data.messages.self != 1 && u_sounds == 1 && !a(".arrowchat_textarea").is(":focus") && playNewMessageSound();
				showTimeAndTooltip();
				K = 1;
				D = E;
			}
			if ("chatroommessage" in data) {
				if (typeof(blockList[data.chatroommessage.userid]) == "undefined")
				{
					addChatroomMessage(data.chatroommessage.id, data.chatroommessage.name, data.chatroommessage.message, data.chatroommessage.userid, data.chatroommessage.sent, data.chatroommessage.global, data.chatroommessage.mod, data.chatroommessage.admin, data.chatroommessage.chatroomid);
					if (data.chatroommessage.name != 'Delete' && data.chatroommessage.global != 1) {
						chatroomAlerts(1, data.chatroommessage.chatroomid);
						var data_array = [1, data.chatroommessage.chatroomid];
						lsClick(JSON.stringify(data_array), 'chatroom_alerts');
						if (data.chatroommessage.userid != u_id) {
							u_chatroom_sound == 1 && !a(".arrowchat_textarea").is(":focus") && playNewMessageSound();
						}
					}
				}
			}
			if ("chatroomban" in data) {
				a(".arrowchat_closebox_bottom", $chatroom_tab[data.chatroomban.id]).click();
				displayMessage("arrowchat_chatroom_message_flyout", data.chatroomban.error2, "error");
			}
		}
		
		function renderHTMLString(string) {
			var render = a("<div/>").attr("id", "arrowchat_render").html(string).appendTo('body');
			var new_render = a("#arrowchat_render").html();
			render.remove();
			return new_render;
		}
		
		function buildModButton() {
			a("#arrowchat_setting_mod_cp").show();
			a("#arrowchat_setting_mod_cp").click(function() {
				window.open(c_ac_path + "mod/", "_blank", "");
				var total_count = parseInt(a(".arrowchat_mobiletab_new_count").html());
				var more_count = parseInt(a("#arrowchat_more_notification").html());
				if ((total_count-more_count) <= 0) {
					a("#arrowchat_mobiletab_new").hide();
				}
				a(".arrowchat_mobiletab_new_count").html(parseInt(total_count-more_count));
				a("#arrowchat_more_notification_modcp").hide();
				a("#arrowchat_more_notification").hide();
			});
			if (u_num_mod_reports > 0) {
				var total_count = parseInt(a(".arrowchat_mobiletab_new_count").html()) + parseInt(u_num_mod_reports);
				a(".arrowchat_mobiletab_new_count").html(total_count);
				a("#arrowchat_mobiletab_new").show();
				a("#arrowchat_more_notification").show().html(u_num_mod_reports);
				a("#arrowchat_more_notification_modcp").show().html(u_num_mod_reports);
			}
		}
		
		function loadSettingsButton() {
			if (u_sounds == 1) { 
				a("#arrowchat_setting_sound :input").prop("checked", true)
			} else {
				a("#arrowchat_setting_sound").addClass("arrowchat_menu_unchecked");
				a("#arrowchat_setting_sound :input").prop("checked", false)
			}
			if (u_no_avatars == 1) {
				a("#arrowchat_setting_names_only :input").prop("checked", true)
			} else {
				a("#arrowchat_setting_names_only").addClass("arrowchat_menu_unchecked");
				a("#arrowchat_setting_names_only :input").prop("checked", false);
			}
			if (u_chatroom_block_chats == 1) { 
				a("#arrowchat_chatroom_block :input").prop("checked", true)
			} else {
				a("#arrowchat_chatroom_block").addClass("arrowchat_menu_unchecked");
				a("#arrowchat_chatroom_block :input").prop("checked", false)
			}
			if (u_chatroom_show_names == 1) { 
				a("#arrowchat_chatroom_show_names :input").prop("checked", true)
			} else {
				a("#arrowchat_chatroom_show_names").addClass("arrowchat_menu_unchecked");
				a("#arrowchat_chatroom_show_names :input").prop("checked", false)
			}
			var noAvatarColor = getRandomColor(u_name);
			a(".arrowchat_popout_settings_button").css('background-color', noAvatarColor["color"]);
			a(".arrowchat_tab_letter_psmall").html(noAvatarColor["character"]);
			a("#arrowchat_popout_settings").click(function() {
				a("#arrowchat_options_flyout").toggleClass("arrowchat_options_flyout_display");
				a("#arrowchat_options_flyout").children(".arrowchat_inner_menu").show();
				a(".arrowchat_block_menu").hide();
				a(".arrowchat_change_name_menu").hide();
			}).children().not('.arrowchat_popout_settings_button').click(function () {
				return false;
			});
			a(".arrowchat_popout_settings_button").mouseover(function() {
				a(this).addClass("arrowchat_popout_settings_button_hover");
			});
			a(".arrowchat_popout_settings_button").mouseleave(function() {
				a(this).removeClass("arrowchat_popout_settings_button_hover");
			});
			a('body').click(function(evt){
				if(a(evt.target).closest('#arrowchat_popout_settings').length)
					return;
				if(a(evt.target).closest('#arrowchat_options_flyout').length)
					return;

				a("#arrowchat_options_flyout").removeClass("arrowchat_options_flyout_display");
			});
			a("#arrowchat_setting_sound").click(function () {
				a(this).toggleClass("arrowchat_menu_unchecked");
				if (a("#arrowchat_setting_sound :input").is(":checked")) {
					a("#arrowchat_setting_sound :input").prop("checked", false);
					_soundcheck = -1;
					u_sounds = 0;
				} else {
					a("#arrowchat_setting_sound :input").prop("checked", true);
					_soundcheck = 1;
					u_sounds = 1;
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					sound: _soundcheck
				}, function () {
				});
			});
			a("#arrowchat_setting_names_only").click(function () {
				a(this).toggleClass("arrowchat_menu_unchecked");
				if (a("#arrowchat_setting_names_only :input").is(":checked")) {
					a("#arrowchat_setting_names_only :input").prop("checked", false);
					setAvatarVisibility(0);
					_namecheck = -1
				} else {
					a("#arrowchat_setting_names_only :input").prop("checked", true);
					setAvatarVisibility(1);
					_namecheck = 1
				}
				a.post(c_ac_path + "includes/json/send/send_settings.php", {
					name: _namecheck
				}, function () {
				});
			});
			if (u_is_guest == 1 && c_guest_name_change == 1 && u_guest_name == "") {
				function guestNameInput() {
					var new_name = a(".arrowchat_guest_name_input").val();
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						chat_name: new_name
					}, function (data) {
						if (data != "1") {
							if (data == lang[123]) {
								displayMessage("arrowchat_chatroom_message_flyout", data, "error");
								a(".arrowchat_enter_name_wrapper").fadeOut('fast');
							} else {
								displayMessage("arrowchat_chatroom_message_flyout", data, "error");
							}
						} else {
							a(".arrowchat_enter_name_wrapper").fadeOut('fast');
							u_name = new_name;
						}
					});
				}
				a(".arrowchat_enter_name_wrapper").show();
				a(".arrowchat_guest_name_input").keydown(function (h) {
					if (h.keyCode == 13) {
						guestNameInput();
					}
				});
				a("#arrowchat_guest_name_button").click(function() {
					guestNameInput();
				});
			}
			a("#arrowchat_setting_block_list").click(function () {
				a(this).parent().parent(".arrowchat_inner_menu").hide();
				a(".arrowchat_block_menu").show();
				a.ajax({
					url: c_ac_path + "includes/json/receive/receive_block_list.php",
					type: "get",
					cache: false,
					dataType: "json",
					success: function (b) {
						if (b && b != null) {
							a(".arrowchat_block_menu select").html("");
							a("<option/>").attr("value", "0").html(lang[118]).appendTo(a(".arrowchat_block_menu select"));
							a.each(b, function (e, l) {
								a.each(l, function (f, h) {
									a("<option/>").attr("value", h.id).html(h.username).appendTo(a(".arrowchat_block_menu select"));
								});
							});
						}
					}
				});
			});
			a("#arrowchat_block_back").click(function () {
				a("#arrowchat_options_flyout .arrowchat_inner_menu").show();
				a(".arrowchat_block_menu").hide();
			});
			a("#arrowchat_unblock_button").click(function () {
				a("#arrowchat_popout_settings").click();
				var unblock_chat_user = a(".arrowchat_block_menu select").val();
				if (unblock_chat_user != "0") {
					a.post(c_ac_path + "includes/json/send/send_settings.php", {
						unblock_chat: unblock_chat_user
					}, function () {
						if (typeof(blockList[unblock_chat_user]) != "undefined") {
							blockList.splice(unblock_chat_user, 1);
						}
						loadBuddyList();
					});
				}
			});
			addHover(a(".arrowchat_menu_item"), "arrowchat_more_hover");
			
			if (a.cookie('arrowchat_hide_lists') == 1 || ac_autohide_panel == 1) {
				a(".arrowchat_popout_hide_lists").show();
				a("#arrowchat_popout_wrapper").addClass("arrowchat_lists_hidden");
			}
			a(".arrowchat_popout_hide_lists").mouseenter(function () {
				a(this).addClass("arrowchat_popout_info_hover");
			});
			a(".arrowchat_popout_hide_lists").mouseleave(function () {
				a(this).removeClass("arrowchat_popout_info_hover");
			});
			a("#arrowchat_hide_lists_button").click(function() {
				a.cookie('arrowchat_hide_lists', 1, {expires: 365, path: '/'});
				a("#arrowchat_popout_settings").click();
				a(".arrowchat_popout_hide_lists").show();
				a("#arrowchat_popout_wrapper").addClass("arrowchat_lists_hidden");
			});
			a(".arrowchat_popout_hide_lists").click(function () {
				a.cookie('arrowchat_hide_lists', 0, {expires: 365, path: '/'});
				a(".arrowchat_popout_hide_lists").hide();
				a("#arrowchat_popout_wrapper").removeClass("arrowchat_lists_hidden");
			});

		}
		
		function loadSelectionButtons() {
			a("#arrowchat_room_selection").click(function() {
				if (a(this).hasClass("arrowchat_selection_tab_selected")){}else{
					a("#arrowchat_user_selection").removeClass("arrowchat_selection_tab_selected");
					a(this).addClass("arrowchat_selection_tab_selected");
					loadChatroomList();
				}
			});
			a("#arrowchat_user_selection").click(function() {
				if (a(this).hasClass("arrowchat_selection_tab_selected")){}else{
					a(".arrowchat_search_friends_input").val('');
					a("#arrowchat_room_selection").removeClass("arrowchat_selection_tab_selected");
					a(this).addClass("arrowchat_selection_tab_selected");
					a("#arrowchat_popout_left_lists").html("");
					a('<div class="arrowchat_loading_icon"></div>').appendTo("#arrowchat_popout_friends");
					loadBuddyList();
				}
			});
			a(".arrowchat_search_friends_input").keydown(function() {
				a("#arrowchat_user_selection").click();
			});
			a(".arrowchat_search_friends_input").placeholder();
			a(".arrowchat_search_friends_input").keyup(function () {
				if (typeof(searchxhr) != "undefined") searchxhr.abort();
				a(".arrowchat_search_not_found").remove();
				a("#arrowchat_popout_left_lists").children('div').not('.arrowchat_userslist_offline').hide();
				clearTimeout(Z);
				var i = 0,
					c = "",
					d = "",
					f = a(this).val();
				if (f.length < 2) {
					a(".arrowchat_search_not_found").remove();
					a("#arrowchat_popout_left_lists").children('div').not('#arrowchat_userslist_offline').show();
					if (buddylisttest == 2) loadBuddyList();
					buddylisttest = 1;
				} else {
					a(".arrowchat_nofriends").remove();
					a("<div/>").attr("class", "arrowchat_search_not_found arrowchat_nofriends").html('<div class="arrowchat_loading_icon"></div>').prependTo("#arrowchat_popout_left_lists");
					buddylisttest = 2;
					if (a("#arrowchat_setting_names_only :input").is(":checked")) d = "arrowchat_hide_avatars";
					searchxhr = a.ajax({
						url: c_ac_path + "includes/json/receive/receive_search.php",
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
										if (typeof(uc_avatar[h.id]) != "undefined")
											var ava = uc_avatar[h.id];
										else
											var ava = h.avatar;
										var icon = ' fas fa-circle';
										if (h.status == 'away')
											icon = ' fas fa-moon';
										else if (h.status == 'busy')
											icon = ' far fa-mobile-screen';
										if (c_disable_avatars == 1 || a("#arrowchat_setting_names_only :input").is(":checked")) d = "arrowchat_hide_avatars";
										c += '<div id="arrowchat_userlist_' + h.id + '" class="arrowchat_userlist" onmouseover="jqac(this).addClass(\'arrowchat_userlist_hover\');" onmouseout="jqac(this).removeClass(\'arrowchat_userlist_hover\');"><img class="arrowchat_userlist_avatar ' + d + '" src="' + ava + '" /><span class="arrowchat_userscontentname">' + h.name + '</span><span class="arrowchat_userscontentdot arrowchat_' + h.status + icon +'"></span></div>';
										i++;
									});
								});
								if (i == 0) {
									a(".arrowchat_search_not_found").html(lang[26]);
								} else {
									a(".arrowchat_search_not_found").remove();
									a('#arrowchat_userslist_available').show().html(c);
									
									a(".arrowchat_userlist").click(function () {
										var c = a(this).attr('id').substr(19);
										receiveUser(c, uc_name[c], uc_status[c], uc_avatar[c], uc_link[c]);
									});
								}
							}
						}
					});
				}
			});
		}
		
		function addHover($elements, classes) {
			$elements.each( function (i, element) {
				a(element).hover(
					function () {
						a(this).addClass(classes);
					}, function () {
						a(this).removeClass(classes);
					}
				);
			});
		}
		
		function setAvatarVisibility(b) {
			if (b == 1) { 
				a(".arrowchat_popout_tab[data-id] .arrowchat_avatar_tab").addClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_convo_wrapper[data-id] .arrowchat_avatarbox img").addClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_convo_wrapper[data-id] .arrowchat_info_avatar img").addClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_settings_button img").addClass("arrowchat_hide_avatars");
				a(".arrowchat_tab_letter").css('display', 'flex');
				a(".arrowchat_userlist .arrowchat_userlist_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_flyout_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_message_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_chatbox_avatar").addClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_settings_button").removeClass("arrowchat_white_background");
				a(".arrowchat_chatboxmessagefrom").removeClass("arrowchat_white_background");
				a(".arrowchat_avatarbox").removeClass("arrowchat_white_background");
				a(".arrowchat_info_avatar").removeClass("arrowchat_white_background");
				a(".arrowchat_avatartab").removeClass("arrowchat_white_background");
				a(".arrowchat_chatbox_avatar_wrapper").removeClass("arrowchat_white_background");
				a(".arrowchat_disable_avatars_name").show();
				a(".arrowchat_chatboxmessage_wrapper").addClass("arrowchat_chatboxmessage_wrapper2");
				a(".arrowchat_chatboxmessagecontent").addClass("arrowchat_chatboxmessagecontent2");
				a(".arrowchat_userlist").addClass("arrowchat_userslist_no_avatars");
			} else {
				a(".arrowchat_popout_tab[data-id] .arrowchat_avatar_tab").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_convo_wrapper[data-id] .arrowchat_avatarbox img").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_convo_wrapper[data-id] .arrowchat_info_avatar img").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_settings_button img").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_tab_letter").hide();
				a(".arrowchat_userlist .arrowchat_userlist_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_flyout_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_chatroom_message_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_chatbox_avatar").removeClass("arrowchat_hide_avatars");
				a(".arrowchat_popout_settings_button").addClass("arrowchat_white_background");
				a(".arrowchat_chatboxmessagefrom").addClass("arrowchat_white_background");
				a(".arrowchat_avatarbox").addClass("arrowchat_white_background");
				a(".arrowchat_info_avatar").addClass("arrowchat_white_background");
				a(".arrowchat_avatartab").addClass("arrowchat_white_background");
				a(".arrowchat_chatbox_avatar_wrapper").addClass("arrowchat_white_background");
				a(".arrowchat_disable_avatars_name").hide();
				a(".arrowchat_chatboxmessage_wrapper").removeClass("arrowchat_chatboxmessage_wrapper2");
				a(".arrowchat_chatboxmessagecontent").removeClass("arrowchat_chatboxmessagecontent2");
				a(".arrowchat_userlist").removeClass("arrowchat_userslist_no_avatars");
			}
		}
		
		function setUserStatus(status) {
			a.post(c_ac_path + "includes/json/send/send_status.php", {
				userid: u_id,
				status: status
			}, function () {})
		}
		
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
					extraHTML = '<div class="arrowchat_login_button_wrapper"><a class="arrowchat_login_button" href="' + c_login_url + '">' + lang[239] + '</a></div>';
			}
			a("#arrowchat_popout_wrapper").html('<div style="text-align:center;padding-top:10px;font-size:16px">' + language + '</div>' + extraHTML);
		}
		
		function getChatroomTabs(room, focus) {
			a.ajax({					
				url: c_ac_path + "includes/json/receive/receive_chatroom_list.php",
				cache: false,
				type: "post",
				dataType: "json",
				success: function (b) {
					b && a.each(b, function (i, e) {
						if (i == "chatrooms") {
							a.each(e, function (l, f) {
								cr_name[f.id] = f.n;
								cr_desc[f.id] = f.d;
								cr_welcome[f.id] = f.welcome;
								cr_img[f.id] = f.img;
								cr_type[f.id] = f.t;
								cr_count[f.id] = f.c;
								cr_other[f.id] = f.o;
							})
						}
					});
					changePushChannel(room, 1);
					if (ac_load_chatroom_id > 0) {
						if (cr_type[room] == 2) {
							a("#arrowchat_chatroom_password_id").val(room);
							a("#arrowchat_chatroom_password_flyout").show("slide", { direction: "up"}, 250, function() {
								a("#arrowchat_chatroom_password_input").focus();
							});
							a("#arrowchat_popout_wrapper").addClass("arrowchat_chatroom_opacity");
						} else {
							init_open_room[room] = 0;
							addChatroomTab(room, focus);
						}
					} else {
						receiveChatroom();
						addChatroomTab(room, focus);
					}
				}
			});
		}
		
		function lsClick(id, action, acvar) {
			if (lsreceive == 0) {
				var milliseconds = new Date().getTime();
				if (action == "private_message" || action == "chatroom_message" || action == "chatroom_alerts" || action == "send_chatroom_message") {
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
				addMessageToChatroom(data[0], data[1], data[2], 1, data[3], 0);
				a("#arrowchat_popout_text_room_" + data[3]).scrollTop(5E4);
				a(".arrowchat_chatboxmessagecontent>div>img,.arrowchat_emoji_text>img").one("load", function() {
				  a("#arrowchat_popout_text_room_" + data[3]).scrollTop(5E4);
				}).each(function() {
				  if(this.complete) a(this).trigger('load');
				});
			}
			if (c_push_engine != 1) {
				if (e.key == 'untyping') {
					var res = e.newValue.split("/");
					receiveNotTyping(res[0]);
				}
				if (e.key == 'typing') {
					var res = e.newValue.split("/");
					receiveTyping(res[0]);
				}
				if (e.key == 'chatroom_message') {
					var res = e.newValue.split("/##-");
					var data = JSON.parse(res[0]);
					var tester = data[8];
					if (tester.substring(0, 3) == "cr-")
						tester = tester.substr(3);
					addChatroomMessage(data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7], tester);
				}
				if (e.key == 'chatroom_alerts') {
					var res = e.newValue.split("/##-");
					var data = JSON.parse(res[0]);
					var tester = data[1];
					if (tester.substring(0, 3) == "cr-")
						tester = tester.substr(3);
					chatroomAlerts(data[0], tester);
				}
			}
			lsreceive = 0;
		}
		
        var bounce = 0,
            bounce2 = 0,
			buddylisttest = 1,
			searchxhr,
			typingTimeout,
			lsreceive = 0,
			chatroom_mod = {},
			chatroom_admin = {},
            count = 0,
            V = {},
            dtit = document.title,
            dtit2 = 1,
            dtit3, window_focus = true,
            xa = {},
			chatroom_list = {},
			pending_msg_count = 0,
			pending_msg_room_count = 0,
            crou = "",
            $ = 0,
            w = 0,
            bli = 1,
			isAway = 0,
            msgcount = 0,
            W = false,
            Y, Z, E = 3E3,
            Crref2 = -1,
            D = E,
            K = 1,
            ma = 0,
            R = 0,
            m = "",
            Ka = 0,
            y = {},
            G = {},
            aa = {},
            ca = {},
			history_ids = {},
			room_history_loaded = {},
			push_room = {},
			push_uid,
			push_arrowchat,
			room_desc = [],
			room_limit_msg = [],
			room_limit_sec = [],
			last_id = {},
			last_sent = {},
			last_name = {},
			init_open = {},
			init_open_room = {},
            Aa = new Date,
            Na = Aa.getDate(),
            ab = Math.floor(Aa.getTime() / 1E3),
            acsi = 1,
            Q = 0,
			message_timeout,
            fa = -1,
            acp = "Powered By <a href='http://www.arrowchat.com/' target='_blank'>ArrowChat</a>",
            pa = 0,
			premade_smiley = [],
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
			if (c_video_select == 4) {
				var win = window.open('https://meet.jit.si/' + b, 'audiovideochat', "status=no,toolbar=no,menubar=no,directories=no,resizable=no,location=no,scrollbars=no,width="+c_video_width+",height="+c_video_height+"");
			} else if (c_video_select == 1) {
				var win = window.open('https://meet.jit.si/' + b, 'audiovideochat', "status=no,toolbar=no,menubar=no,directories=no,resizable=no,location=no,scrollbars=no,width="+c_video_width+",height="+c_video_height+"");
			} else {
				var win = window.open(c_ac_path + 'public/video/?rid=' + b, 'audiovideochat', "status=no,toolbar=no,menubar=no,directories=no,resizable=no,location=no,scrollbars=no,width="+c_video_width+",height="+c_video_height+"");
			}
			win.focus();
        };
		function runarrowchat() {
			a.ajax({					
				url: c_ac_path + "includes/json/receive/receive_init.php",
				cache: false,
				type: "get",
				dataType: "json",
				success: function (b) {}
			});
			if (!Modernizr.emoji) {
				c_disable_smilies = 1;
			}
			window.addEventListener('storage', localStorageFired, false);
			if (c_chat_maintenance != 0 || c_db_connection == 1 || u_id == "" || c_disable_arrowchat == 1) {				
				buildMaintenance();
			} else {
				if (c_push_engine == 1) {
					push = new Scaledrone(c_push_publish);
				}
				if (c_push_engine == 1) {
					pushSubscribe();
				}
				a("#arrowchat_popout_left_lists").html("");
				a('<div class="arrowchat_loading_icon"></div>').appendTo("#arrowchat_popout_friends");
				loadBuddyList();
				loadChatroomInit();
				loadSelectionButtons();
				loadSettingsButton();
				u_is_mod == 1 && c_enable_moderation == 1 && buildModButton();
				if (c_chatrooms != 1) {
					a("#arrowchat_chat_selection_tabs").remove();
					a("#arrowchat_popout_left_lists").css("top", "101px");
				} else {					
					if (ac_load_chatroom_id > 0) {
						getChatroomTabs(ac_load_chatroom_id, 1);
					}
					if (ac_select_chatroom > 0 || c_online_list == 0) {
						a("#arrowchat_room_selection").click();
					}
					if (c_online_list == 0) {
						a("#arrowchat_user_selection").css("visibility", "hidden");
						a("#arrowchat_room_selection").addClass("arrowchat_selection_tab_selected");
					}
				}
				M();
				a(window).bind("resize", M);
				a("#arrowchat_popout_left_lists").perfectScrollbar();
				var chatrooms_exist = false;
				for (var d = (focus_chat.length-1); d >= 0; d--) {
					if (typeof(focus_chat[d] != "undefined")) {
						if (focus_is_room[d] == "1" ) {
							if (c_chatrooms != 0 && ac_load_chatroom_id != focus_chat[d]) {
								init_open_room[focus_chat[d]] = 0;
								changePushChannel(focus_chat[d], 1);
								if (d == (focus_chat.length-1)) {
									addChatroomTab(focus_chat[d], 1);
								} else {
									addChatroomTab(focus_chat[d], 0);
								}
								chatrooms_exist = true;
							}
						} else {
							init_open[focus_chat[d]] = 0;
							if (d == (focus_chat.length-1)) {
								receiveUser(focus_chat[d], uc_name[focus_chat[d]], uc_status[focus_chat[d]], uc_avatar[focus_chat[d]], uc_link[focus_chat[d]], "0");
							} else {
								receiveUser(focus_chat[d], uc_name[focus_chat[d]], uc_status[focus_chat[d]], uc_avatar[focus_chat[d]], uc_link[focus_chat[d]], "1");
							}
						}
					}
				}
				for (var d = 0; d < unfocus_chat.length; d++) {
					if (typeof(unfocus_chat[d] != "undefined")) {
						if (unfocus_is_room[d] == "1") {
							if (c_chatrooms != 0 && ac_load_chatroom_id != unfocus_chat[d]) {
								changePushChannel(unfocus_chat[d], 1);
								addChatroomTab(unfocus_chat[d], 0);
								chatrooms_exist = true;
							}
						} else {
							receiveUser(unfocus_chat[d], uc_name[unfocus_chat[d]], uc_status[unfocus_chat[d]], uc_avatar[unfocus_chat[d]], uc_link[unfocus_chat[d]], "1");
						}
					}
				}
				if (chatrooms_exist) {
					receiveChatroom();
				}
				receiveCore();
				a(document).bind("idle.idleTimer", function () {
					setUserStatus("away");
					isAway = 1;
				});
				a(document).bind("active.idleTimer", function () {
					setUserStatus("available");
					isAway = 0;
				});
				window.onblur = function () {
					window_focus = false
                };
				window.onfocus = function () {
					window_focus = true
				};
				a.idleTimer(60000 * c_idle_time);
				ion.sound({
					sounds: [
						{
							name: "new_message"
						}
					],
					volume: 1.0,
					path: c_ac_path + "themes/" + u_theme + "/sounds/",
					preload: true
				});
			}
		}
        a.ajaxSetup({
            scriptCharset: "utf-8",
            cache: false
        });
        arguments.callee.runarrowchat = function () {
            runarrowchat()
        };
		arguments.callee.chatWith = function (b) {
			receiveUser(b, uc_name[b], uc_status[b], uc_avatar[b], uc_link[b], "0")
		};
    }
})(jqac);
(jqac);
jqac(document).ready(function () {
    jqac.arrowchat();
    jqac.arrowchat.runarrowchat()
});