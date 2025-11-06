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

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	// ########################## INCLUDE BACK-END ###########################
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'bootstrap.php');
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');

	// ########################## GET POST DATA ###########################
	if (!isset($_GET['rid'])) $_GET['rid'] = 0;
	$room_id = $_GET['rid'];

	if (strlen($room_id) == 9)
	{
		$room_id = "0".$room_id;
	}

	$username = get_username($userid);
	
	if (empty($userid))
		die ('You must log in to use video chat.');
		
	if ($group_enable_mode == 1)
	{
		if (check_array_for_match($group_id, $group_disable_video_sep))
		{
		}
		else
		{
			die ('Your group is not permitted to use video chat.');
		}
	}
	else
	{
		if (check_array_for_match($group_id, $group_disable_video_sep))
		{
			die ('Your group is not permitted to use video chat.');
		}
	}

	// ############################# OPENTOK ##############################
	require_once (dirname(__FILE__) . '/OpenTok/vendor/autoload.php');
	use OpenTok\OpenTok;
	use OpenTok\Session;
	use OpenTok\Role;
	use OpenTok\MediaMode;

	if ($video_chat_selection == 2)
	{
		$opentok = new OpenTok($tokbox_api, $tokbox_secret);

		if (isset($_REQUEST['rid']))
		{
			$sessionId = $_REQUEST['rid'];
		}
		else
		{
			$session = $opentok->createSession(array('mediaMode' => MediaMode::ROUTED ));
			$sessionId = $session->getSessionId();
		}

		$token = $opentok->generateToken($sessionId);
	}


	// ############################ START HTML ############################
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $language[200]; ?></title>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<?php
		if ($video_chat_selection == 2 || $video_chat_selection == 3) {
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<?php
		}
	?>
	<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/all.css" />
</head>
<body>
	<div id="video_chat">
	<?php
		if ($video_chat_selection == 2) {
			if (empty($tokbox_api))
				die ('<p style="color:#fff">There is no API key for the video chat.</p>');
	?>
		<style>
			.OT_root .OT_video-loading{background: url(<?php echo $base_url; ?>public/video/OpenTok/img/ajax-loader.gif) no-repeat;}
		</style>
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>external.php?type=css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/tokbox.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/tooltip.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/tooltip-light.css" />
		<script src='<?php echo $base_url; ?>includes/js/jquery.js'></script>
		<script src='<?php echo $base_url; ?>public/video/js/tooltip.js'></script>
		<script src='<?php echo $base_url; ?>public/video/js/sweetalert.js'></script>
		<script src='https://static.opentok.com/v2/js/opentok.min.js'></script>
		<script type="text/javascript">
			$ = jqac;
			$( document ).ready(function() {
				var apiKey = "<?php echo $tokbox_api; ?>";
				var sessionId = "<?php echo $sessionId; ?>";
				var token = "<?php echo $token; ?>";
				var session;
				var publisher;
				var screenpublisher;
				var subscribers = {};
				var totalStreams = 0;
				var totalViewers = -1;
				
				if (location.protocol === 'http:') {
					Swal.fire("<?php echo $language[258]; ?>", 'You must be using SSL (https://) for this video chat to work.', 'error');
				}

				var isNotIE = function isNotIE () {
				  var userAgent = window.navigator.userAgent.toLowerCase(),
					  appName = window.navigator.appName;

				  return !( appName === 'Microsoft Internet Explorer' ||
						   (appName === 'Netscape' && userAgent.indexOf('trident') > -1) );
				}
				if (!isNotIE())
					alert('Internet Explorer is not supported.  Please update to a modern browser.');

				OT.checkScreenSharingCapability(function(response) {
					if (!response.supported || response.extensionRegistered === false) {
						$('#screenShareLink,#screenShareLink2').hide();
					} else {
						$('#screenShareLink,#screenShareLink2').css('display', 'inline-block');
					}
				});

				if (OT.checkSystemRequirements() != OT.HAS_REQUIREMENTS) {
					Swal.fire("<?php echo $language[258]; ?>", 'Sorry, but your computer configuration does not meet minimum requirements for video chat.', 'error');
				} else {
					var session = OT.initSession(apiKey, sessionId);

					session.on('sessionConnected', function(event) {
						hide('loading');
						show('canvas');

						for (var i = 0; i < event.streams.length; i++) {
							if (event.streams[i].connection.connectionId != session.connection.connectionId) {
								totalStreams++;
							}
							addStream(event.streams[i]);
						}

						publish();
						show('navigation');
						show('disconnectLink');
					});

					session.on('sessionDisconnected', function(event) {
						publisher = null;
						screenpublisher = null;
					});

					session.on('connectionCreated', function(event) {
						totalViewers++;
						$('#viewerCount span').html(totalViewers);
					});

					session.on('connectionDestroyed', function(event) {
						totalViewers--;
						$('#viewerCount span').html(totalViewers);
					});

					session.on('streamCreated', function(event) {
						for (var i = 0; i < event.streams.length; i++) {
							if (event.streams[i].connection.connectionId != session.connection.connectionId) {
								totalStreams++;
							}
							addStream(event.streams[i]);
						}
					});

					session.on('streamDestroyed', function(event) {
						for (var i = 0; i < event.streams.length; i++) {
							if (event.streams[i].connection.connectionId != session.connection.connectionId) {
								totalStreams--;
							}
						}
					});
				}

				function connect() {
					session.connect(token, function(error) {
						publish();
					});
				}

				function disconnect() {
					unpublish();
					session.disconnect();
					hide('navigation');
					hide('popup-nav');
					show('endcall');
					var div = document.getElementById('canvas');
					div.parentNode.removeChild(div);
					window.resizeTo(300,330);
				}

				function publish() {
					if (!publisher) {
						if (session.capabilities.publish == 1) {
							var parentDiv = document.getElementById("myCamera");
							var div = document.createElement('div');
							div.setAttribute('id', 'opentok_publisher');
							parentDiv.appendChild(div);
							$('#opentok_publisher').addClass('small-camera');
							publisher = OT.initPublisher("opentok_publisher",{resolution: '1280x720', fitMode: 'contain', frameRate: 30});
							session.publish(publisher);
							publisher.on('streamCreated', function (event) {
								if (event.stream.hasVideo) {
									show('unpublishLink');
								}
								if (event.stream.hasAudio) {
									show('audioUnpublishLink');
								}
							});
						} else {
							Swal.fire("<?php echo $language[258]; ?>", 'Please make sure you have a connected camera or microphone.', 'error');
						}
					}
				}

				function unpublish() {
					if (publisher) {
						session.unpublish(publisher);
					}

					publisher = null;
					show('publishLink');
					hide('unpublishLink');
				}

				function addStream(stream) {
					if (stream.connection.connectionId == session.connection.connectionId) {
						return;
					}

					var div = document.createElement('div');
					var divId = stream.streamId;
					div.setAttribute('id', divId);
					div.setAttribute('class', 'camera');
					document.getElementById('otherCamera').appendChild(div);
					if (totalStreams > 1 || $('#screen-preview').hasClass('main-camera') || $('#opentok_publisher').hasClass('main-camera')) {
						$('#'+divId).addClass('small-camera');
					} else {
						$('#'+divId).addClass('main-camera');
					}
					subscribers[stream.streamId] = session.subscribe(stream, divId, {resolution: '1280x720', frameRate: 30, fitMode: 'contain'});
				}

				function show(id) {
					document.getElementById(id).style.display = 'inline-block';
				}

				function hide(id) {
					document.getElementById(id).style.display = 'none';
				}

				function position() {
					var h = <?php echo $video_chat_height; ?>;

					if( typeof( window.innerWidth ) == 'number' ) {
						h = window.innerHeight;
					} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
						h = document.documentElement.clientHeight;
					} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
						h = document.body.clientHeight;
					}
				}
				function inviteUser() {
					var url = document.URL;
					Swal.fire({
					  title: "<?php echo $language[251]; ?>",
					  html: "<div class=\"meeting-link\"><input id=\"invite-url\" class=\"invite-input\" readonly type=\"text\" value=\""+url+"\" /><div class=\"copied-message\"><span><?php echo $language[252]; ?></span></div></div>",
					  showConfirmButton: false,
					  showCloseButton: true
					});
					var copyText = document.getElementById("invite-url");
					copyText.select();
					copyText.setSelectionRange(0, 99999);
					document.execCommand("copy");
					document.getSelection().removeAllRanges();
					copyText.blur();
				}
				function screenShare() {
					if (!screenpublisher) {
						$('<div id="screen-preview" class="small-camera"></div>').insertBefore('#otherCamera');
						OT.checkScreenSharingCapability(function(response) {
							if(!response.supported || response.extensionRegistered === false) {
								alert('Your browser does not support screen sharing.');
							} else if (response.extensionInstalled === false) {
								alert('You must install a screen sharing plugin or upgrade your browser.');
							} else {
								screenpublisher = OT.initPublisher('screen-preview',
								  {videoSource: 'screen', fitMode: 'contain'},
								  function(error) {
									if (error) {
									  alert(error.message);
									} else {
									  session.publish(screenpublisher, function(error) {
										if (error) {
										  alert(error.message);
										}
									  });
									  $("#unscreenShareLink,#unscreenShareLink2").css('display', 'inline-block');
									  $("#screenShareLink,#screenShareLink2").hide();
									}
								  }
								);
								screenpublisher.on('streamDestroyed', function(event) {
									screenpublisher = null;
									$("#unscreenShareLink,#unscreenShareLink2").hide();
									$("#screenShareLink,#screenShareLink2").css('display', 'inline-block');
								});
							}
						});
					}
				}
				function unpublishScreen() {
					if (screenpublisher) {
						session.unpublish(screenpublisher);
					}

					screenpublisher = null;
					$("#unscreenShareLink,#unscreenShareLink2").hide();
					$("#screenShareLink,#screenShareLink2").css('display', 'inline-block');
				}
				function publishAudio(state) {
					if (publisher) {
						publisher.publishAudio(state);
					}
					if (state) {
						$("#audioUnpublishLink").css('display', 'inline-block');
						$("#audioPublishLink").hide();
					} else {
						$("#audioUnpublishLink").hide();
						$("#audioPublishLink").css('display', 'inline-block');
					}
				}
				function publishVideo(state) {
					if (publisher) {
						publisher.publishVideo(state);
					}
					if (state) {
						$("#unpublishLink").css('display', 'inline-block');
						$("#publishLink").hide();
					} else {
						$("#unpublishLink").hide();
						$("#publishLink").css('display', 'inline-block');
					}
				}
				window.resizeTo(<?php echo $video_chat_width; ?>,<?php echo $video_chat_height; ?>);
				connect();
				window.onload = function() { position(); }
				window.onresize = function() { position(); }
				$("#publishLink").click(function() {
					publishVideo(true);
				});
				$("#unpublishLink").click(function() {
					publishVideo(false);
				});
				$("#audioUnpublishLink").click(function() {
					publishAudio(false);
				});
				$("#audioPublishLink").click(function() {
					publishAudio(true);
				});
				$("#inviteLink,#inviteLink2").click(function() {
					inviteUser();
				});
				$("#disconnectLink").click(function() {
					disconnect();
				});
				$("#screenShareLink,#screenShareLink2").click(function() {
					screenShare();
				});
				$("#unscreenShareLink,#unscreenShareLink2").click(function() {
					unpublishScreen();
				});
				$('#showMoreLink').click(function() {
					$('#popup-nav').css('display', 'inline-block');
					$('#hideMoreLink').css('display', 'inline-block');
					$('#showMoreLink').hide();
				});
				$('#hideMoreLink').click(function() {
					$('#popup-nav').hide();
					$('#showMoreLink').css('display', 'inline-block');
					$('#hideMoreLink').hide();
				});
				$('#hideWindowsLink,#hideWindowsLink2').click(function() {
					$('.small-camera').hide();
					$('#showWindowsLink,#showWindowsLink2').css('display', 'inline-block');
					$('#hideWindowsLink,#hideWindowsLink2').hide();
				});
				$('#showWindowsLink,#showWindowsLink2').click(function() {
					$('.small-camera').show();
					$('#hideWindowsLink,#hideWindowsLink2').css('display', 'inline-block');
					$('#showWindowsLink,#showWindowsLink2').hide();
				});
				$(document).on('click', '.small-camera', function(e) {
					if (e.target.nodeName != "BUTTON") {
						$('.main-camera').addClass('small-camera').removeClass('main-camera');
						$(this).addClass('main-camera').removeClass('small-camera');
					}
				});
				$('.tooltip').tooltipster({
					animation: 'fade',
					animationDuration: 100,
					theme: 'tooltipster-light',
					delay: 0
				});
			});
		</script>

		<div id="loading" class="loadingio-spinner-spinner-6swz33mh5ng"><div class="ldio-1sdd864959p">
<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
</div></div>
		<div id="endcall"><?php echo $language[203]; ?></div>
		<div id="canvas">
			<div id="viewerCountWrapper"><div id="viewerCount" class="tooltip" title="<?php echo $language[257]; ?>"><i class="fas fa-eye"></i><span>0</span></div></div>
			<div id="myCamera" class="publisherContainer"></div>
			<div id="otherCamera"></div>
		</div>
		<div id="popup-nav">
			<div id="popup-nav-elements">
				<a href="#" title="<?php echo $language[249]; ?>" class="button tooltip small-b" id="hideWindowsLink2"><i class="fas fa-window-close"></i></a>
				<a href="#" title="<?php echo $language[250]; ?>" class="button tooltip small-b" id="showWindowsLink2"><i class="fas fa-window-restore"></i></a>
				<a href="#" title="<?php echo $language[247]; ?>" class="button tooltip small-b" id="screenShareLink2"><i class="fas fa-laptop"></i></a>
				<a href="#" title="<?php echo $language[248]; ?>" class="button tooltip small-b" id="unscreenShareLink2"><i class="fas fa-laptop-code"></i></a>
				<a href="#" title="<?php echo $language[205]; ?>" class="button tooltip small-b" id="inviteLink2"><i class="fas fa-user-plus"></i></a>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div id="navigation">
			<div id="navigation_elements">
				<a href="#" title="<?php echo $language[206]; ?>" class="button tooltip blue-button" id="publishLink"><i class="fas fa-video-slash"></i></a>
				<a href="#" title="<?php echo $language[207]; ?>" class="button tooltip blue-button" id="unpublishLink"><i class="fas fa-video"></i></a>
				<a href="#" title="<?php echo $language[253]; ?>" class="button tooltip blue-button" id="audioPublishLink"><i class="fas fa-microphone-slash"></i></i></a>
				<a href="#" title="<?php echo $language[254]; ?>" class="button tooltip blue-button" id="audioUnpublishLink"><i class="fas fa-microphone"></i></a>
				<a href="#" title="<?php echo $language[255]; ?>" class="button tooltip" id="showMoreLink"><i class="fas fa-chevron-up"></i></a>
				<a href="#" title="<?php echo $language[256]; ?>" class="button tooltip" id="hideMoreLink"><i class="fas fa-chevron-down"></i></a>
				<a href="#" title="<?php echo $language[249]; ?>" class="button tooltip sizer" id="hideWindowsLink"><i class="fas fa-window-close"></i></a>
				<a href="#" title="<?php echo $language[250]; ?>" class="button tooltip sizer" id="showWindowsLink"><i class="fas fa-window-restore"></i></a>
				<a href="#" title="<?php echo $language[247]; ?>" class="button tooltip sizer" id="screenShareLink"><i class="fas fa-laptop"></i></a>
				<a href="#" title="<?php echo $language[248]; ?>" class="button tooltip sizer" id="unscreenShareLink"><i class="fas fa-laptop-code"></i></a>
				<a href="#" title="<?php echo $language[205]; ?>" class="button tooltip sizer" id="inviteLink"><i class="fas fa-user-plus"></i></a>
				<a href="#" title="<?php echo $language[204]; ?>" class="button tooltip end-call" id="disconnectLink"><i class="fas fa-phone-hangup"></i></a>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
	<?php
		} else if ($video_chat_selection == 3) {
			if (empty($agora_app_id))
				die ('<p style="color:#fff">There is no API key for the video chat.</p>');
	?>
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>external.php?type=css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/agora.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/tooltip.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>public/video/css/tooltip-light.css" />
		<script src='<?php echo $base_url; ?>includes/js/jquery.js'></script>
		<script src='<?php echo $base_url; ?>public/video/js/tooltip.js'></script>
		<script src='<?php echo $base_url; ?>public/video/js/sweetalert.js'></script>
		<script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.6.11.js"></script>
		<script type="text/javascript">
			$ = jqac;
			$( document ).ready(function() {
				var rtc = {
				  client: null,
				  screenClient: null,
				  joined: false,
				  screenJoined: false,
				  published: false,
				  screenPublished: false,
				  localStream: null,
				  screenStream: null,
				  remoteStreams: [],
				  params: {}
				};

				var option = {
				  appID: "<?php echo $agora_app_id; ?>",
				  channel: "<?php echo $room_id; ?>",
				  uid: null,
				  token: null
				};

				var totalStreams = 0;
				var totalViewers = 0;
				var hasAudio = false;
				var hasVideo = false;
				var viewers = [];

				var isChrome = !!window.chrome;
				var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
				var isIEedge = navigator.userAgent.indexOf("Edge") > -1;
				if ((isChrome || isFirefox) && !isIEedge) {
					$('#screenShareLink,#screenShareLink2').css('display', 'inline-block');
				}
				
				if (location.protocol === 'http:') {
					Swal.fire("<?php echo $language[258]; ?>", 'You must be using SSL (https://) for this video chat to work.', 'error');
				}
				
				function generateToken(selection) {
					var randomID = Math.floor(Math.random() * 100000);
					if (selection == "screen")
						randomID = parseInt("9999" + randomID);
					$.ajax({
						url: "./generate_token.php?_="+new Date().getTime(),
						type: "post",
						cache: false,
						dataType: "json",
						data: {
							selection: selection,
							uid: randomID,
							room_id: option.channel
						},
						error: function () {
							Swal.fire("<?php echo $language[258]; ?>", 'You could not join the video chat. Please try again.', 'error');
						},
						success: function (result) {
							option.token = result.token;
							option.uid = randomID;
							if (selection == "join")
								join();
							if (selection == "screen")
								publishScreen();
						}
					});
				}

				function join() {
					AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.ERROR);
					if (rtc.joined) {
						return; // Already Joined
					}

					if (!AgoraRTC.checkSystemRequirements()) {
						Swal.fire("<?php echo $language[258]; ?>", 'Sorry, but your computer configuration does not meet minimum requirements for video chat.', 'error');
					} else {
						rtc.client = AgoraRTC.createClient({mode: "rtc", codec: "vp8"});
						handleEvents(rtc);
						rtc.client.init(option.appID, function () {
							rtc.client.join(option.token, option.channel, option.uid, function (uid) {
								rtc.joined = true;
								rtc.params.uid = uid;
								hide('loading');
								show('canvas');

								AgoraRTC.getDevices(function (items) {
									items.filter(function (item) {
									  return ['audioinput', 'videoinput'].indexOf(item.kind) !== -1
									})
									.map(function (item) {
									  return {
									  name: item.label,
									  value: item.deviceId,
									  kind: item.kind,
									  }
									});
									for (var i = 0; i < items.length; i++) {
										var item = items[i];
										if (item.kind == 'videoinput') {
											hasVideo = true;
										}
										if (item.kind == 'audioinput') {
											hasAudio = true;
										}
									}
									var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
									if (iOS) {
										hasVideo = true;
										hasAudio = true;
									}

									if (!hasVideo && !hasAudio) {
										Swal.fire("<?php echo $language[258]; ?>", "<?php echo $language[259]; ?>", 'error');
									} else {
										rtc.localStream = AgoraRTC.createStream({
											streamID: rtc.params.uid,
											audio: hasAudio,
											video: hasVideo,
											screen: false
										});

										rtc.localStream.setVideoProfile("480p_4");

										rtc.localStream.init(function () {
											rtc.localStream.play("cameras");
											var id = rtc.localStream.getId();
											$('#player_'+id).addClass('small-camera');
											publish();
										}, function (err) {
											rtc.joined = false;
											rtc.params.uid = null;
											Swal.fire("<?php echo $language[258]; ?>", "<?php echo $language[259]; ?>", 'error');
											console.error("init local stream failed ", err);
										});
									}
								});
							  }, function(err) {
								Swal.fire("<?php echo $language[258]; ?>", 'You could not join the video chat. Please try again.', 'error');
								console.error("client join failed", err);
							});
						  }, (err) => {
							Swal.fire("<?php echo $language[258]; ?>", 'You could not join the video chat. Please try again.', 'error');
							console.error(err);
						});
					}
				}

				function handleEvents() {
					rtc.client.on("error", (err) => {
						console.log(err)
					});
					rtc.client.on("peer-online", function (evt) {
						var id = evt.uid;
						if (!viewers.includes(id)) {
							if (id != rtc.params.uid && id != rtc.params.suid) {
								var sstest = id.toString();
								var ss = false;
								sstest = sstest.slice(0, 4);
								if (sstest == "9999")
									ss = true;

								if (!ss) {
									totalViewers++;
									$('#viewerCount span').html(totalViewers);
									viewers.push(id);
								}
							}
						}
					});
					rtc.client.on("peer-leave", function (evt) {
						var id = evt.uid;
						if (id != rtc.params.uid) {
						  removeView(id);
						}
						if (viewers.includes(id)) {
							if (id != rtc.params.uid && id != rtc.params.suid) {
								totalViewers--;
								$('#viewerCount span').html(totalViewers);
								for(var i = viewers.length - 1; i >= 0; i--) {
									if(viewers[i] == id) {
										viewers.splice(i, 1);
									}
								}
							}
						}
					});
					rtc.client.on("stream-published", function (evt) {

					});
					rtc.client.on("stream-added", function (evt) {
						var remoteStream = evt.stream;
						var id = remoteStream.getId();
						if (id !== rtc.params.uid) {
						  rtc.client.subscribe(remoteStream, function (err) {
							console.log("stream subscribe failed", err);
						  });
						}
					});
					rtc.client.on("stream-subscribed", function (evt) {
						var remoteStream = evt.stream;
						var id = remoteStream.getId();
						rtc.remoteStreams.push(remoteStream);
						if (id !== rtc.params.suid) {
							var audioOnly = false;
							if (remoteStream.hasAudio() && !remoteStream.hasVideo())
								audioOnly = true;
							addView(id, audioOnly);
							remoteStream.play("remote_video_" + id);
							if (totalStreams > 1 || $('#cameras').children('div').hasClass('main-camera')) {
								$('#remote_video_panel_'+id).addClass('small-camera');
							} else {
								$('#remote_video_panel_'+id).addClass('main-camera');
							}
						}
					});
					rtc.client.on("mute-video", function (evt) {
						var id = evt.uid;
						$("#remote_video_panel_" + id).addClass('audio-only');
					});
					rtc.client.on("unmute-video", function (evt) {
						var id = evt.uid;
						$("#remote_video_panel_" + id).removeClass('audio-only');
					});
					rtc.client.on("stream-removed", function (evt) {
						var remoteStream = evt.stream;
						var id = remoteStream.getId();
						if (id !== rtc.params.suid) {
							remoteStream.stop("remote_video_" + id);
							rtc.remoteStreams = rtc.remoteStreams.filter(function (stream) {
							  return stream.getId() !== id
							})
							removeView(id);
						}
					});
				}

				function addView (id, audioOnly, show) {
				  if (!$("#" + id)[0]) {
					totalStreams++;
					$("<div/>", {
					  id: "remote_video_panel_" + id,
					  class: "video-view",
					}).appendTo("#cameras");

					$("<div/>", {
					  id: "remote_video_" + id,
					  class: "video-placeholder",
					}).appendTo("#remote_video_panel_" + id);

					$("<div/>", {
					  id: "remote_video_info_" + id,
					  class: "video-profile " + (show ? "" :  "hide"),
					}).appendTo("#remote_video_panel_" + id);

					$("<div/>", {
					  id: "video_autoplay_"+ id,
					  class: "autoplay-fallback hide",
					}).appendTo("#remote_video_panel_" + id);

					if (audioOnly)
						$("#remote_video_panel_" + id).addClass('audio-only');
				  }
				}
				function removeView (id) {
				  if ($("#remote_video_panel_" + id)[0]) {
					totalStreams--;
					$("#remote_video_panel_"+id).remove();
				  }
				}

				function publish() {
					if (!rtc.client) {
						return; // Join room first
					}
					if (rtc.published) {
						return; // Already published
					}
					var oldState = rtc.published;

					rtc.client.publish(rtc.localStream, function (err) {
						rtc.published = oldState;
						Swal.fire("<?php echo $language[258]; ?>", 'We could not publish your video stream. Please try again.', 'error');
						console.error(err);
					});
					rtc.localStream.play("cameras");
					var id = rtc.localStream.getId();
					$('#player_'+id).addClass('small-camera');
					if (!hasVideo && hasAudio)
						$('#player_'+id).addClass('audio-only');
					rtc.published = true;

					if (hasAudio) {
						show('audioUnpublishLink');
						hide('audioPublishLink');
					}
					if (hasVideo) {
						show('unpublishLink');
						hide('publishLink');
					}
				}

				function rejoinVideo() {
					if (!rtc.client) {
						return; // Join room first
					}
					if (!rtc.published) {
						return; // Not published
					}

					var id = rtc.localStream.getId();
					$('#player_'+id).removeClass('audio-only');
					rtc.localStream.unmuteVideo();

					show('unpublishLink');
					hide('publishLink');
				}

				function unpublish() {
					if (!rtc.client) {
						return; // Join room first
					}
					if (!rtc.published) {
						return; // Not published
					}

					var id = rtc.localStream.getId();
					$('#player_'+id).addClass('audio-only');
					rtc.localStream.muteVideo();

					show('publishLink');
					hide('unpublishLink');
				}

				function disconnect() {
					if (!rtc.client) {
						return; // Join room first
					}
					if (!rtc.joined) {
						return; // Not in a channel
					}

					rtc.client.leave(function () {
						rtc.localStream.stop();
						rtc.localStream.close();
						while (rtc.remoteStreams.length > 0) {
							var stream = rtc.remoteStreams.shift();
							stream.stop();
						}
						rtc.localStream = null;
						rtc.remoteStreams = [];
						rtc.client = null;
						rtc.published = false;
						rtc.joined = false;
						hide('navigation');
						hide('popup-nav');
						show('endcall');
						var div = document.getElementById('canvas');
						div.parentNode.removeChild(div);
						window.resizeTo(300,330);
					}, function (err) {
						Swal.fire("<?php echo $language[258]; ?>", 'Unable to end the call. Please try again.', 'error');
						console.error(err);
					})
				}

				function inviteUser() {
					var url = document.URL;
					Swal.fire({
					  title: "<?php echo $language[251]; ?>",
					  html: "<div class=\"meeting-link\"><input id=\"invite-url\" class=\"invite-input\" readonly type=\"text\" value=\""+url+"\" /><div class=\"copied-message\"><span><?php echo $language[252]; ?></span></div></div>",
					  showConfirmButton: false,
					  showCloseButton: true
					});
					var copyText = document.getElementById("invite-url");
					copyText.select();
					copyText.setSelectionRange(0, 99999);
					document.execCommand("copy");
					document.getSelection().removeAllRanges();
					copyText.blur();
				}

				function publishAudio(state) {
					if (!rtc.client) {
						return; // Join room first
					}
					if (!rtc.published) {
						return; // Not published
					}

					if (state) {
						rtc.localStream.unmuteAudio();
					} else {
						rtc.localStream.muteAudio();
					}

					if (state) {
						$("#audioUnpublishLink").css('display', 'inline-block');
						$("#audioPublishLink").hide();
					} else {
						$("#audioUnpublishLink").hide();
						$("#audioPublishLink").css('display', 'inline-block');
					}
				}

				function publishScreen() {
					if (rtc.screenJoined) {
						return; // Already Joined
					}

					if (!AgoraRTC.checkSystemRequirements()) {
						Swal.fire("<?php echo $language[258]; ?>", 'Sorry, but your computer configuration does not meet minimum requirements for screen sharing.', 'error');
					} else {
						rtc.screenClient = AgoraRTC.createClient({mode: "rtc", codec: "vp8"});
						rtc.screenClient.init(option.appID, function () {
							rtc.screenClient.join(option.token, option.channel, option.uid, function (uid) {
								rtc.screenJoined = true;
								rtc.params.suid = uid;

								rtc.screenStream = AgoraRTC.createStream({
									streamID: rtc.params.suid,
									audio: false,
									video: false,
									screen: true
								});

								rtc.screenStream.setVideoProfile("720p_3");

								rtc.screenStream.init(function () {
									rtc.screenStream.play("cameras");
									var id = rtc.screenStream.getId();
									$('#player_'+id).addClass('small-camera');
									$("#screenShareLink,#screenShareLink2").hide();
									$("#unscreenShareLink,#unscreenShareLink2").css('display', 'inline-block');

									var oldState = rtc.screenPublished;
									rtc.screenClient.publish(rtc.screenStream, function (err) {
										rtc.screenPublished = oldState;
										Swal.fire("<?php echo $language[258]; ?>", 'We could not publish your video stream. Please try again.', 'error');
										console.error(err);
									});
									rtc.screenPublished = true;
								}, function (err) {
									rtc.screenJoined = false;
									rtc.params.suid = null;
									Swal.fire("<?php echo $language[258]; ?>", "<?php echo $language[259]; ?>", 'error');
									console.error("init local stream failed ", err);
								});
							  }, function(err) {
								Swal.fire("<?php echo $language[258]; ?>", 'You could not join the video chat. Please try again.', 'error');
								console.error("client join failed", err);
							});
						  }, (err) => {
							Swal.fire("<?php echo $language[258]; ?>", 'You could not join the video chat. Please try again.', 'error');
							console.error(err);
						});
					}
				}

				function unpublishScreen() {
					if (!rtc.screenClient) {
						return; // Start Screen First
					}
					if (!rtc.screenPublished) {
						return; // Not published
					}

					var oldState = rtc.screenPublished;
					rtc.screenClient.unpublish(rtc.screenStream, function (err) {
						rtc.screenPublished = oldState;
						Swal.fire("<?php echo $language[258]; ?>", 'We could not unpublish your screen share. Please try again.', 'error');
						console.error(err);
					});
					rtc.screenStream.stop();
					rtc.screenStream.close();
					rtc.screenPublished = false;
					rtc.screenJoined = false;
					rtc.screenClient = null;

					$("#unscreenShareLink,#unscreenShareLink2").hide();
					$("#screenShareLink,#screenShareLink2").css('display', 'inline-block');
				}

				function show(id) {
					document.getElementById(id).style.display = 'inline-block';
				}

				function hide(id) {
					document.getElementById(id).style.display = 'none';
				}

				window.resizeTo(<?php echo $video_chat_width; ?>,<?php echo $video_chat_height; ?>);

				$("#publishLink").click(function() {
					rejoinVideo();
				});
				$("#unpublishLink").click(function() {
					unpublish();
				});
				$("#audioUnpublishLink").click(function() {
					publishAudio(false);
				});
				$("#audioPublishLink").click(function() {
					publishAudio(true);
				});
				$("#inviteLink,#inviteLink2").click(function() {
					inviteUser();
				});
				$("#disconnectLink").click(function() {
					disconnect();
				});
				$("#screenShareLink,#screenShareLink2").click(function() {
					generateToken("screen");
				});
				$("#unscreenShareLink,#unscreenShareLink2").click(function() {
					unpublishScreen();
				});
				$('#showMoreLink').click(function() {
					$('#popup-nav').css('display', 'inline-block');
					$('#hideMoreLink').css('display', 'inline-block');
					$('#showMoreLink').hide();
				});
				$('#hideMoreLink').click(function() {
					$('#popup-nav').hide();
					$('#showMoreLink').css('display', 'inline-block');
					$('#hideMoreLink').hide();
				});
				$('#hideWindowsLink,#hideWindowsLink2').click(function() {
					$('.small-camera').hide();
					$('#showWindowsLink,#showWindowsLink2').css('display', 'inline-block');
					$('#hideWindowsLink,#hideWindowsLink2').hide();
				});
				$('#showWindowsLink,#showWindowsLink2').click(function() {
					$('.small-camera').show();
					$('#hideWindowsLink,#hideWindowsLink2').css('display', 'inline-block');
					$('#showWindowsLink,#showWindowsLink2').hide();
				});
				$(document).on('click', '.small-camera', function(e) {
					$('.main-camera').addClass('small-camera').removeClass('main-camera');
					$(this).addClass('main-camera').removeClass('small-camera');
				});
				$('.tooltip').tooltipster({
					animation: 'fade',
					animationDuration: 100,
					theme: 'tooltipster-light',
					delay: 0
				});

				generateToken("join");
			});
		</script>

		<div id="loading" class="loadingio-spinner-spinner-6swz33mh5ng"><div class="ldio-1sdd864959p">
<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
</div></div>
		<div id="endcall"><?php echo $language[203]; ?></div>
		<div id="canvas">
			<div id="viewerCountWrapper"><div id="viewerCount" class="tooltip" title="<?php echo $language[257]; ?>"><i class="fas fa-eye"></i><span>0</span></div></div>
			<div id="cameras"></div>
		</div>
		<div id="popup-nav">
			<div id="popup-nav-elements">
				<a href="#" title="<?php echo $language[249]; ?>" class="button tooltip small-b" id="hideWindowsLink2"><i class="fas fa-window-close"></i></a>
				<a href="#" title="<?php echo $language[250]; ?>" class="button tooltip small-b" id="showWindowsLink2"><i class="fas fa-window-restore"></i></a>
				<a href="#" title="<?php echo $language[247]; ?>" class="button tooltip small-b" id="screenShareLink2"><i class="fas fa-laptop"></i></a>
				<a href="#" title="<?php echo $language[248]; ?>" class="button tooltip small-b" id="unscreenShareLink2"><i class="fas fa-laptop-code"></i></a>
				<a href="#" title="<?php echo $language[205]; ?>" class="button tooltip small-b" id="inviteLink2"><i class="fas fa-user-plus"></i></a>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div id="navigation">
			<div id="navigation_elements">
				<a href="#" title="<?php echo $language[206]; ?>" class="button tooltip blue-button" id="publishLink"><i class="fas fa-video-slash"></i></a>
				<a href="#" title="<?php echo $language[207]; ?>" class="button tooltip blue-button" id="unpublishLink"><i class="fas fa-video"></i></a>
				<a href="#" title="<?php echo $language[253]; ?>" class="button tooltip blue-button" id="audioPublishLink"><i class="fas fa-microphone-slash"></i></i></a>
				<a href="#" title="<?php echo $language[254]; ?>" class="button tooltip blue-button" id="audioUnpublishLink"><i class="fas fa-microphone"></i></a>
				<a href="#" title="<?php echo $language[255]; ?>" class="button tooltip" id="showMoreLink"><i class="fas fa-chevron-up"></i></a>
				<a href="#" title="<?php echo $language[256]; ?>" class="button tooltip" id="hideMoreLink"><i class="fas fa-chevron-down"></i></a>
				<a href="#" title="<?php echo $language[249]; ?>" class="button tooltip sizer" id="hideWindowsLink"><i class="fas fa-window-close"></i></a>
				<a href="#" title="<?php echo $language[250]; ?>" class="button tooltip sizer" id="showWindowsLink"><i class="fas fa-window-restore"></i></a>
				<a href="#" title="<?php echo $language[247]; ?>" class="button tooltip sizer" id="screenShareLink"><i class="fas fa-laptop"></i></a>
				<a href="#" title="<?php echo $language[248]; ?>" class="button tooltip sizer" id="unscreenShareLink"><i class="fas fa-laptop-code"></i></a>
				<a href="#" title="<?php echo $language[205]; ?>" class="button tooltip sizer" id="inviteLink"><i class="fas fa-user-plus"></i></a>
				<a href="#" title="<?php echo $language[204]; ?>" class="button tooltip end-call" id="disconnectLink"><i class="fas fa-phone-hangup"></i></a>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
	<?php
		}
	?>
	</div>
</body>
</html>
