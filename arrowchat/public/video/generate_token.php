<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #    Copyright 2010-2020 ArrowSuites LLC. All Rights Reserved.     # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/

	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'bootstrap.php');
	require_once (dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . AC_FOLDER_INCLUDES . DIRECTORY_SEPARATOR . 'init.php');
	include(dirname(__FILE__) . "/agora/RtcTokenBuilder.php");

	if (!isset($_POST['room_id']) OR !is_numeric($_POST['room_id']))
		die("Room ID invalid");
		
	if (!isset($_POST['selection']) OR ($_POST['selection'] != "screen" AND $_POST['selection'] != "join"))
		die("Selection invalid");
		
	if (!isset($_POST['uid']) OR !is_numeric($_POST['uid']))
		die("UID invalid");
		
	$channelName = $_POST['room_id'];
	$uidStr = $_POST['uid'];
	$role = RtcTokenBuilder::RoleAttendee;
	$expireTimeInSeconds = 3600;
	$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
	$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

	$token = RtcTokenBuilder::buildTokenWithUserAccount($agora_app_id, $agora_app_certificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
	$json_response = array('token' => $token);
	
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($json_response);
	exit;
	
?>