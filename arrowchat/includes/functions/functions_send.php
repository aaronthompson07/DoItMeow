<?php

	/*
	|| #################################################################### ||
	|| #                             ArrowChat                            # ||
	|| # ---------------------------------------------------------------- # ||
	|| #    Copyright ©2010-2012 ArrowSuites LLC. All Rights Reserved.    # ||
	|| # This file may not be redistributed in whole or significant part. # ||
	|| # ---------------- ARROWCHAT IS NOT FREE SOFTWARE ---------------- # ||
	|| #   http://www.arrowchat.com | http://www.arrowchat.com/license/   # ||
	|| #################################################################### ||
	*/

	/**
	 * Does everything to turn text into a readable, safe format
	 *
	 * @param	string	$text	The text to convert
	 * @return	string	The new string in a readable, safe format
	*/
	function sanitize($text) 
	{
		global $base_url;
		global $db;
		global $disable_smilies;
		global $language;
		global $smileys;
		global $theme;
		global $blocked_words;
		global $giphy_chatroom_off;
		global $giphy_off;
		
		$text = htmlspecialchars($text, ENT_NOQUOTES);
		$text = preg_replace('/\\\[rn]/', '<br/>', $text);
		$text = preg_replace("/[\r\n]{2,}/", "\n\n", $text);
		
		// Get the theme's directory if it is numeric
		if (is_numeric($theme)) 
		{
			$result = $db->execute("
				SELECT folder 
				FROM arrowchat_themes 
				WHERE id='".$theme."'
			");
			
			if ($result AND $db->count_select() > 0) 
			{
				$row = $db->fetch_array($result);
				$theme = $row['folder'];
			} 
			else 
			{
				$theme = "new_facebook";
			}
		}
		
		if (!empty($blocked_words))
		{
			$bad_words = explode(",", $blocked_words);
			$container_words = array();
			$exact_match_words = array();
			
			foreach ($bad_words as $word)
			{
				$s_word = trim($word);
				
				if (preg_match('/\[(.*?)\]/', $s_word))
				{
					$exact_match_words[] = trim($s_word, '[]');
				}
				else
				{
					$container_words[] = $s_word;
				}
			}

			if (!empty($exact_match_words))
				$text = preg_replace("/\b(" . implode("|",$exact_match_words) . ")\b/i", "****", $text);
				
			if (!empty($container_words))
				$text = preg_replace("/(" . implode("|",$container_words) . ")/i", "****", $text);
		}
		
		if ($disable_smilies != 1) 
		{ 
			$text = preg_replace('/^([*#0-9](?>\\xEF\\xB8\\x8F)?\\xE2\\x83\\xA3|\\xC2[\\xA9\\xAE]|\\xE2..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?(?>\\xEF\\xB8\\x8F)?|\\xE3(?>\\x80[\\xB0\\xBD]|\\x8A[\\x97\\x99])(?>\\xEF\\xB8\\x8F)?|\\xF0\\x9F(?>[\\x80-\\x86].(?>\\xEF\\xB8\\x8F)?|\\x87.\\xF0\\x9F\\x87.|..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?|(((?<zwj>\\xE2\\x80\\x8D)\\xE2\\x9D\\xA4\\xEF\\xB8\\x8F\k<zwj>\\xF0\\x9F..(\k<zwj>\\xF0\\x9F\\x91.)?|(\\xE2\\x80\\x8D\\xF0\\x9F\\x91.){2,3}))?))$/', '<span class="arrowchat_emoji_text arrowchat_emoji_32">$1</span>', $text);
			
			if (!empty($smileys)) 
			{
				foreach ($smileys as $pattern => $result) 
				{
					$pattern = str_replace("\;", ";", $pattern);
					$pattern = htmlspecialchars($pattern);
					$text = str_replace($pattern, '<span class="arrowchat_emoji_text"><img src="' . $base_url . 'includes/emojis/img/16/' . $result . '" alt="" /> </span>', $text);
				}
			}
			
			$premade_smilies = array(
				":)" => "&#x1F642;",
				":-)" => "&#x1F642;",
				"=)" => "&#x1F642;",
				":p" => "&#x1F61B;",
				":o" => "&#x1F62E;",
				":|" => "&#x1F610;",
				":(" => "&#x2639;&#xFE0F;",
				"=(" => "&#x2639;&#xFE0F;",
				":D" => "&#x1F603;",
				"=D" => "&#x1F603;",
				":/" => "&#x1F615;",
				"=/" => "&#x1F615;",
				";)" => "&#x1F609;",
				":'(" => "&#x1F622;",
				"<3" => "&#x2764;&#xFE0F;",
				">:(" => "&#x1F621;"
			);
			
			foreach ($premade_smilies as $pattern => $result) 
			{
				$pattern = str_replace("\;", ";", $pattern);
				$pattern = htmlspecialchars($pattern);
				if ($text == $pattern)
					$text = str_replace($pattern, $result, $text);
				else
					$text = str_replace(" " . $pattern, ' ' . $result, $text);
			}
		}
		
		if (preg_match('@video[{](.*)[}]@', $text, $match))
		{
			$text = str_replace($match[0], '<div class="arrowchat_action_message"><div class="arrowchat_action_message_wrapper">' . $language[61] . '</div><div class="arrowchat_action_message_action"><a href="javascript:jqac.arrowchat.videoWith(\'' . htmlentities($match[1]) . '\');">' . $language[62] . '</a></div></div>', $text);
		}
		
		if (preg_match('@file[{]([0-9]{13})[}][{](.*)[}]@', $text, $match))
		{
			$text = str_replace($match[0], '<div class="arrowchat_action_message"><div class="arrowchat_action_message_wrapper">' . $language[69] . '</div><div class="arrowchat_action_message_action"><a href="' . $base_url . 'public/download.php?file=' . htmlentities($match[1]) . '">' . htmlentities($match[2]) . '</a></div></div>', $text);
		}
		
		if (preg_match('@image[{]([0-9]{13})[}][{](.*)[}]@', $text, $match))
		{
			$text = str_replace($match[0], '<div class="arrowchat_image_message"><img data-id="' . $base_url . 'public/download.php?file=' . htmlentities($match[1]) . '" src="' . $base_url . 'public/download.php?file=' . htmlentities($match[1]) . '_t" alt="Image" class="arrowchat_lightbox" /></div>', $text);
		}
		
		if (preg_match('~^(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)$~i', $text, $match))
		{
			$text = str_replace($match[0], '<span style="margin-bottom:5px;display:block"> ' . htmlentities($match[0]) . ' </span><iframe style="width:100%;height:140px" src="https://www.youtube.com/embed/' . htmlentities($match[1]) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', $text);
		}
				
		if ($giphy_chatroom_off != 1 OR $giphy_off != 1)
		{
			if (preg_match('@giphy[{](.*)[}][{](.*)[}]@', $text, $match))
			{
				$parsed_url = parse_url($match[2]);
				
				if (preg_match('@giphy@i', $parsed_url['host'])) {
					$text = str_replace($match[0], '<div class="arrowchat_giphy_message"><img class="arrowchat_lightbox arrowchat_giphy_img" data-id="' . htmlentities($match[2]) . '" src="' . htmlentities($match[2]) . '" alt="" /></div>', $text);
				}
			}
		}

		return $text;
	}

?>