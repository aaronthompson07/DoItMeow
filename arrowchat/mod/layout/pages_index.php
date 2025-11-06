		<div class="title_bg" id="content-recent"> 
            <div class="module_content">    
				<div class="subtitle total_reports">Active Reports</div>
				<div class="arrowchat_reports_subtitle"></div>
				<div class="arrowchat_clearfix"></div>
				<div class="arrowchat_moderation_content">
					<div id="arrowchat_moderation_flyout" class="arrowchat_message_box">
						<div class="arrowchat_message_box_wrapper">
							<div>
								<span class="arrowchat_message_text"><?php echo $language[182]; ?></span>
							</div>
						</div>
					</div>
					<div class="arrowchat_moderation_full_content">
					</div>
				</div>
			</div>
		</div>
		<style>
			.arrowchat_chatroom_box_message{display:flex;padding:4px 0;line-height:30px}
			.arrowchat_chatroom_message_avatar{width:30px;height:30px;margin-right:5px}
		</style>
		<script type="text/javascript">
			$(document).ready(function() 
			{
				var open_report = -1;
				
				function preventScrolling($target) 
				{
					$target.bind('mousewheel DOMMouseScroll', function (e) {
						var e0 = e.originalEvent,
							delta = e0.wheelDelta || -e0.deltaY || -e0.detail;
						this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
						e.preventDefault();
					});
				}
				
				function displayMessage(id, message, type) {
					clearTimeout(message_timeout);
					if ($("#" + id).is(":visible")) {
						$("#" + id).hide("slide", { direction: "up"}, 250, function() {					
							$("#" + id + " .arrowchat_message_text").html(message);
							type == "error" && $(".arrowchat_message_box").addClass("arrowchat_message_box_error").removeClass("arrowchat_message_box_notice");
							type == "notice" && $(".arrowchat_message_box").addClass("arrowchat_message_box_notice").removeClass("arrowchat_message_box_error");
							$("#" + id).show("slide", { direction: "up"}, 250);
						});
					} else {
						type == "error" && $(".arrowchat_message_box").addClass("arrowchat_message_box_error").removeClass("arrowchat_message_box_notice");
						type == "notice" && $(".arrowchat_message_box").addClass("arrowchat_message_box_notice").removeClass("arrowchat_message_box_error");
						$("#" + id + " .arrowchat_message_text").html(message);
						$("#" + id).show("slide", { direction: "up"}, 250);
					}
					message_timeout = setTimeout(function () {
						$("#" + id).hide("slide", { direction: "up"}, 250);
					}, 5000);
				}
				
				function formatTimestamp(b, noHTML) {
					var c = "am",
						d = b.getHours(),
						i = b.getMinutes(),
						e = b.getDate();
						b = b.getMonth();			
					var g = d;
					var Aa = new Date;
					var Na = Aa.getDate();
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
						if (<?php echo $us_time; ?> != 1) {
							return e != Na ? '' + g + ":" + i + " " + e + f + " " + l[b] + "" : '' + g + ":" + i + ""
						} else {
							return e != Na ? '' + d + ":" + i + c + " " + e + f + " " + l[b] + "" : '' + d + ":" + i + c + ""
						}
					} else {
						if (<?php echo $us_time; ?> != 1) {
							return e != Na ? '<span class="arrowchat_ts">' + g + ":" + i + " " + e + f + " " + l[b] + "</span>" : '<span class="arrowchat_ts">' + g + ":" + i + "</span>"
						} else {
							return e != Na ? '<span class="arrowchat_ts">' + d + ":" + i + c + " " + e + f + " " + l[b] + "</span>" : '<span class="arrowchat_ts">' + d + ":" + i + c + "</span>"
						}
					}
				}
				
				function loadModerationContent() {
					$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
					$.ajax({					
						url: "<?php echo $base_url; ?>includes/json/receive/receive_moderation.php",
						cache: false,
						type: "get",
						dataType: "json",
						success: function (b) {
							buildModerationContent(b);
						}
					});
				}
				
				function loadReport(b) {
					$.ajax({
						url: "<?php echo $base_url; ?>includes/json/receive/receive_report.php",
						data: {
							reportid: b
						},
						type: "post",
						cache: false,
						dataType: "json",
						success: function (o) {
							if (o) {
								var no_error = true;
								o && $.each(o, function (i, e) {
									if (i == "error") {
										$.each(e, function (l, f) {
											no_error = false;
											open_report = 0;
											displayMessage("arrowchat_moderation_flyout", f.m, "error");
											loadModerationContent();
										});
									}
								});
								if (no_error) {
									$(".arrowchat_reports_subtitle").html('<div class="arrowchat_report_sub_back"><a href="javascript:void(0);"><?php echo $language[186]; ?></a></div><div class="arrowchat_report_sub_close"><a href="javascript:void(0);"><?php echo $language[185]; ?></a></div><div class="arrowchat_report_sub_ban"><a href="javascript:void(0);"><?php echo $language[183]; ?></a></div><div class="arrowchat_report_sub_warn"><a href="javascript:void(0);"><?php echo $language[184]; ?></a></div>');
									$(".arrowchat_moderation_full_content").html('<div id="arrowchat_report_history"><div id="arrowchat_report_history_content"></div></div><div id="arrowchat_report_info"><div class="arrowchat_report_info_about"></div><div class="arrowchat_report_info_from"></div><div class="arrowchat_report_info_warnings"></div><div class="arrowchat_report_info_time"></div></div><div id="arrowchat_report_list"><div id="arrowchat_report_line" class="arrowchat_group_container"><span class="arrowchat_group_text"><?php echo $language[181]; ?></span><div class="arrowchat_group_line_container"><span class="arrowchat_group_line"></span></div></div><div id="arrowchat_list_reports"></div></div><div class="arrowchat_clearfix"></div>');
									$(".arrowchat_report_sub_back a").click(function() {
										loadModerationContent();
									});
									$(".arrowchat_report_sub_close a").click(function() {
										$(".arrowchat_reports_subtitle").html('');
										$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
										$.post("<?php echo $base_url; ?>includes/json/send/send_settings.php", {
											report_id: b
										}, function () {
											loadModerationContent();
										})
									});
									$(".arrowchat_report_sub_ban a").click(function() {
										var r = confirm("<?php echo $language[195]; ?>");
										if (r == true) {
											$(".arrowchat_reports_subtitle").html('');
											$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
											$.post("<?php echo $base_url; ?>includes/json/send/send_settings.php", {
												report_ban: b
											}, function () {
												loadModerationContent();
											})
										}
									});
									$(".arrowchat_report_sub_warn a").click(function() {
										var reason = prompt("<?php echo $language[196]; ?>");
										if (reason != null && reason != "") {
											$.post("<?php echo $base_url; ?>includes/json/send/send_settings.php", {
												report_warn: b,
												report_warn_reason: reason
											}, function (e) {
												if (e == 2) {
													var r = confirm("<?php echo $language[197]; ?>");
													if (r == true) {
														$(".arrowchat_reports_subtitle").html('');
														$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
														$.post("<?php echo $base_url; ?>includes/json/send/send_settings.php", {
															report_warn: b,
															report_warn_reason: reason,
															report_warn_confirm: 1
														}, function () {
															loadModerationContent();
														})
													}
												} else {
													$(".arrowchat_reports_subtitle").html('');
													$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
													loadModerationContent();
												}
											})
										}
									});
									$report_history = $("#arrowchat_report_history");
									$report_list = $("#arrowchat_report_list");
									preventScrolling($("#arrowchat_report_list"));
									var no_additional_reports = true,report_time = 0;
									o && $.each(o, function (i, e) {
										if (i == "reports") {
											$.each(e, function (l, f) {
												no_additional_reports = false;
												$("<div/>").attr("id", "arrowchat_report_list_id_" + f.id).mouseover(function () {
													$(this).addClass("arrowchat_report_list_hover");
												}).mouseout(function () {
													$(this).removeClass("arrowchat_report_list_hover");
												}).click(function () {
													if (f.id != open_report) {
														$("#arrowchat_report_history_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
														loadReport(f.id);
														open_report = f.id;
													}
												}).addClass("arrowchat_report_other_list").html('<span class="arrowchat_report_list_name"><?php echo $language[187]; ?>' + f.id + '</span>').appendTo($("#arrowchat_list_reports"));
												if (f.id == open_report) {
													$("#arrowchat_report_list_id_" + f.id).addClass('arrowchat_report_clicked');
												}
											});
										}
										if (i == "report_info") {
											$.each(e, function (l, f) {
												$(".arrowchat_report_info_about").html('<?php echo $language[190]; ?><a href="javascript:void(0);">' + f.about_name + '</a>');
												$(".arrowchat_report_info_from").html('<?php echo $language[191]; ?><a href="javascript:void(0);">' + f.from_name + '</a>');
												$(".arrowchat_report_info_warnings").html('<?php echo $language[192]; ?>' + f.previous_warnings);
												$(".arrowchat_report_info_time").html('<?php echo $language[193]; ?>' + f.time);
												
												$(".arrowchat_report_info_about a").click(function() {
													//receiveUser(f.about, uc_name[f.about], uc_status[f.about], uc_avatar[f.about], uc_link[f.about]);
												});
												$(".arrowchat_report_info_from a").click(function() {
													//receiveUser(f.from, uc_name[f.from], uc_status[f.from], uc_avatar[f.from], uc_link[f.from]);
												});
												report_time = f.unix;
											});
										}
										if (i == "report_history") {
											var d = "",report_here_used=false;
											$.each(e, function (l, f) {
												//if (typeof(blockList[f.userid]) == "undefined") {
													var title = "", important = "";
													if (f.mod == 1) {
														title = "<?php echo $language[137]; ?>";
														important = " arrowchat_chatroom_important";
													}
													if (f.admin == 1) {
														title = "<?php echo $language[136]; ?>";
														important = " arrowchat_chatroom_important";
													}
													l = "",repotee="",image_msg="";
													fromname = f.n;
													if (f.reportee == 1) {
														repotee = " arrowchat_reportee";
													}
													if (f.m.substr(0, 4) == "<div") {
														image_msg = " arrowchat_image_msg";
													}
													var sent_time = new Date(f.t * 1E3);
													var tooltip = formatTimestamp(sent_time, 1);
													if (f.t >= report_time && !report_here_used) {
														d += '<div class="arrowchat_chatroom_box_message arrowchat_report_here"><div class="arrowchat_chatroom_message_content arrowchat_global_chatroom_message"><?php echo $language[194]; ?></div></div>';
														report_here_used = true;
													}
													if (f.global == 1) {
														d += '<div class="arrowchat_chatroom_box_message" id="arrowchat_chatroom_message_' + f.id + '"><div class="arrowchat_chatroom_message_content' + l + ' arrowchat_global_chatroom_message">' + formatTimestamp(sent_time) + f.m + "</div></div>"
													} else {
														d += '<div class="arrowchat_chatroom_box_message' + l + image_msg + repotee + important + '" id="arrowchat_chatroom_message_' + f.id + '"><img class="arrowchat_chatroom_message_avatar arrowchat_no_names" src="'+f.a+'" alt="' + fromname + '" /><div class="arrowchat_chatroom_message_name">' + fromname + ':</div><div class="arrowchat_chatroom_message_content" data-id="' + tooltip + '"><span class="arrowchat_chatroom_msg">' + f.m + '</span></div></div>'
													}
												//}
											});
											if (!report_here_used) {
												d += '<div class="arrowchat_chatroom_box_message arrowchat_report_here"><div class="arrowchat_chatroom_message_content arrowchat_global_chatroom_message"><?php echo $language[194]; ?></div></div>';
											}
											$("#arrowchat_report_history_content").html(d);
											//showChatroomTime();
										}
									});
									$('#arrowchat_report_history').scrollTop($('#arrowchat_report_history').scrollTop() + $(".arrowchat_report_here").position().top - $('#arrowchat_report_history').height()/2 + $(".arrowchat_report_here").height()/2);
									$(".arrowchat_image_msg img,.arrowchat_emoji_text img").one("load", function() {
										$('#arrowchat_report_history').scrollTop($('#arrowchat_report_history').scrollTop() + $(".arrowchat_report_here").position().top - $('#arrowchat_report_history').height()/2 + $(".arrowchat_report_here").height()/2);
									}).each(function() {
										if(this.complete) $(this).trigger('load');
									});
									preventScrolling($("#arrowchat_report_history"));
									if (no_additional_reports) {
										$("<div/>").attr("id", "arrowchat_report_list_none").addClass("arrowchat_report_other_list").html('<span class="arrowchat_report_list_name"><?php echo $language[188]; ?></span>').appendTo($("#arrowchat_list_reports"));
									}
								}
							}
						}
					})
				}
				
				function reportClicked(b) 
				{
					c = "";
					 if ($(b).attr("id"))
						c = $(b).attr("id").substr(17);
					if (c == "") c = $(b).parent().attr("id").substr(17);
					$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
					open_report = c;
					loadReport(c);
				}
				
				function buildModerationContent(b) 
				{
					$(".arrowchat_moderation_full_content").html("");
					$(".arrowchat_reports_subtitle").html('<div class="arrowchat_report_sub_from"><?php echo $language[177]; ?></div><div class="arrowchat_report_sub_about"><?php echo $language[178]; ?></div><div class="arrowchat_report_sub_time"><?php echo $language[179]; ?></div>');
					var c = {},
						no_reports = true;
					b && $.each(b, function (i, e) {
						if (i == "reports") {
							$.each(e, function (l, f) {
								no_reports = false;
								$("<div/>").attr("id", "arrowchat_report_" + f.id).mouseover(function () {							
								$(this).addClass("arrowchat_report_list_hover");
								}).mouseout(function () {
									$(this).removeClass("arrowchat_report_list_hover");
								}).addClass("arrowchat_report_list").html('<div class="arrowchat_report_from_image"><img src="' + f.from_pic + '" alt=""></div><div class="arrowchat_report_from_name">' + f.from + '</div><div class="arrowchat_report_about_image"><img src="' + f.about_pic + '" alt=""></div><div class="arrowchat_report_about_name">' + f.about + ' (' + f.about_num + ')</div><div class="arrowchat_report_time">' + f.time + '</div><div class="arrowchat_clearfix"></div>').appendTo($(".arrowchat_moderation_full_content"));
							})
						}
						if (i == "total_reports") {
							$(".total_reports").html("Active Reports (" + e.count + " Total)");
						}
					});
					if (no_reports) {
						$("<div/>").attr("id", "arrowchat_report_no_reports").html("<?php echo $language[189]; ?>").appendTo($(".arrowchat_moderation_full_content"));
					}
					preventScrolling($(".arrowchat_moderation_content"));
					$(".arrowchat_report_list").click(function (l) {
						reportClicked($(this))
					});
				}
				
				$(".arrowchat_moderation_full_content").html('<div class="arrowchat_nofriends"><div class="arrowchat_loading_icon"></div><?php echo $language[34]; ?></div>');
				$.ajax({					
					url: "<?php echo $base_url; ?>includes/json/receive/receive_moderation.php",
					cache: false,
					type: "get",
					dataType: "json",
					success: function (b) {
						buildModerationContent(b);
					}
				});
			});
		</script>
		<?php if ($is_admin == 1) { ?>
		<div class="title_bg" id="content-recent"> 
            <div class="module_content">    
				<div class="subtitle">Recent Chat</div>
		<?php
			$numrows = $db->count_all("
				arrowchat
			");

			$pagenum = 1;
			$self = htmlentities($_SERVER['PHP_SELF']);
			$nav  = 'Page ';
			$maxpage = 10;
			$total_pages = ceil($numrows / $maxpage);
			
			if(var_check('page')) 
			{
				$pagenum = get_var('page');
			}
			
			for ($i = 1; $i <= $total_pages; $i++) 
			{ 
				if ($i == $pagenum) 
				{
					$nav .= " $i ";
				} 
				else if ($i <= 5) 
				{
					$nav .= " <a href=\"$self?page=$i\">" . floor($i) . "</a> ";
				}     
			}
			
			$offset = ($pagenum - 1) * $maxpage;
			
			$result = $db->execute("
				SELECT * 
				FROM arrowchat 
				ORDER BY id DESC 
				LIMIT " . $db->escape_string($offset) . ", " . $db->escape_string($maxpage) . "
			");
			
			if ($result AND $db->count_select() > 0) 
			{
		?>
				<table cellspacing="0" cellpadding="0" style="border-collapse: collapse">
				<tr style="height: 45px;">
					<td style="width: 125px;" class="row2 table_from">From</td>
					<td style="width: 125px;" class="row2 table_to">To</td>
					<td style="width: 305px;" class="row2 table_message">Message</td>
					<td style="width: 50px;" class="row2 table_read">Read</td>
					<td style="width: 125px;" class="row2 table_sent">Sent</td>
				</tr>
			<?php
				while ($row = $db->fetch_array($result)) 
				{
					if (check_if_guest($row['from']))
					{
						$sql = get_guest_details($row['from']);
						$from_result = $db->execute($sql);
						$from_username = $db->fetch_array($from_result);
						$from_username = create_guest_username($from_username['userid'], $from_username['guest_name']);
					}
					else
					{
						$sql = get_user_details($row['from']);
						$from_result = $db->execute($sql);
						$from_username = $db->fetch_array($from_result);
						$from_username = $from_username['username'];
					}
				  
					if (check_if_guest($row['to']))
					{
						$sql = get_guest_details($row['to']);
						$to_result = $db->execute($sql);
						$to_username = $db->fetch_array($to_result);
						$to_username = create_guest_username($to_username['userid'], $to_username['guest_name']);
					}
					else
					{
						$sql = get_user_details($row['to']);
						$to_result = $db->execute($sql);
						$to_username = $db->fetch_array($to_result);
						$to_username = $to_username['username'];
					}
					
			?>
				<tr style="height: 25px">
					<td class="row1" style="padding: 10px 0"><a href="users.php?do=logs&id=<?php echo $row['from']; ?>"><?php echo $from_username; ?></a></td>
					<td class="row1" style="padding: 10px 0"><a href="users.php?do=logs&id=<?php echo $row['to']; ?>"><?php echo $to_username; ?></a></td>
					<td class="row1" style="padding: 10px 0"><div class="index_message" style="overflow: hidden; max-height: 32px; width: 300px;"><?php echo $row['message']; ?></div></td>
				<?php
					if($row['user_read'] == 1) 
					{
						echo '<td class="row1">Yes</td>';
					} 
					else 
					{
						echo '<td class="row1">No</td>';
					}
				?>
					<td class="row1" style="padding: 10px 0"><?php echo date("M j, Y", $row['sent']); ?><br><?php echo date("g:i a", $row['sent']); ?></td>
				</tr>
			<?php
				}
			?>
				<tr>
					<td colspan="5">
						<div style="margin-top:20px; text-align: center;">
							<?php echo $nav; ?>
						</div>
					</td>
				</tr>
			</table>
		<?php
			} 
			else 
			{
		?>
				No one has ever chatted!
		<?php
			}
		?>
			</div>
		 </div>
		<?php } ?>
		<div style="clear: both;"></div>