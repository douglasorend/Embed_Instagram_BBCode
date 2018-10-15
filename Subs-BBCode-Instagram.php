<?php
/**********************************************************************************
* Subs-BBCode-Instagram.php
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF')) 
	die('Hacking attempt...');

function BBCode_Instagram(&$bbc)
{
	// Format: [instagram width=x height=x frameborder=x]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'parameters' => array(
			'width' => array('match' => '(\d+)'),
			'height' => array('optional' => true, 'match' => '(\d+)'),
			'frameborder' => array('optional' => true, 'match' => '(\d+)'),
			'captioned' => array('optional' => true, 'match' => '(y|n|yes|no)'),
		),
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '{width}|{height}|{frameborder}|{captioned}',
		'disabled_content' => '$1',
	);

	// Format: [instagram width=x height=x frameborder=x]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'parameters' => array(
			'frameborder' => array('match' => '(\d+)'),
		),
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '0|0|{frameborder}|',
		'disabled_content' => '$1',
	);

	// Format: [instagram]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '0|0|0|',
		'disabled_content' => '$1',
	);
}

function BBCode_Instagram_Button(&$buttons)
{
	$buttons[count($buttons) - 1][] = array(
		'image' => 'instagram',
		'code' => 'instagram',
		'description' => 'Instagram',
		'before' => '[instagram]',
		'after' => '[/instagram]',
	);
}

function BBCode_Instagram_Validate(&$tag, &$data, &$disabled)
{
	global $txt, $modSettings;
	
	if (empty($data))
		return ($tag['content'] = $txt['instagram_no_post_id']);
	$data = strtr(trim($data), array('<br />' => ''));
	if (strlen($data) > 11)
	{
		if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
			$data = 'http://' . $data;
		$pattern = '#(http|https):\/\/(|(.+?).)instagram.com\/p\/([A-Za-z0-9_\-]+)#i';
		if (!preg_match($pattern, $data, $parts))
			return ($tag['content'] = $txt['instagram_no_post_id']);
		$data = $parts[4] . $parts[5];
	}
	list($width, $height, $frameborder, $captioned) = explode('|', $tag['content']);
	if (empty($width) && !empty($modSettings['instagram_default_width']))
		$width = $modSettings['instagram_default_width'];
	if (empty($height) && !empty($modSettings['instagram_default_height']))
		$height = $modSettings['instagram_default_height'];
	$captioned = empty($captioned) || $captioned == 'y' || $captioned == 'yes';
	$tag['content'] = '<div style="' . (empty($width) ? '' : 'max-width: ' . $width . 'px;') . (empty($height) ? '' : 'max-height: ' . $height . 'px;') . '"><div class="instagram-wrapper">' .
		'<iframe src="https://instagram.com/' . $data .'/embed' . ($captioned ? '/captioned/' : '') . '" scrolling="no" frameborder="' . $frameborder . '"></iframe></div></div>';
}

function BBCode_Instagram_LoadTheme()
{
	global $context, $settings;
	$context['html_headers'] .= '
	<link rel="stylesheet" type="text/css" href="' . $settings['default_theme_url'] . '/css/BBCode-Instagram.css" />';
	$context['allowed_html_tags'][] = '<iframe>';
}

function BBCode_Instagram_Settings(&$config_vars)
{
	$config_vars[] = array('int', 'instagram_default_width');
	$config_vars[] = array('int', 'instagram_default_height');
}

function BBCode_Instagram_Embed(&$message, &$smileys, &$cache_id, &$parse_tags)
{
	$replace = (strpos($cache_id, 'sig') !== false ? '[url]$0[/url]' : '[instagram]$0[/instagram]');
	$pattern = '~(?<=[\s>\.(;\'"]|^)(https?\:\/\/)(?:www\.)?instagram.com\/p\/?[A-Za-z0-9_\-]+(\/embed|\/embed\/captioned|)\/\?taken\-by=[A-Za-z0-9_\-]+\??[/\w\-_\~%@\?;=#}\\\\]?~';
	$message = preg_replace($pattern, $replace, $message);
	$pattern = '~(?<=[\s>\.(;\'"]|^)(https?\:\/\/)(?:www\.)?instagram.com\/p\/[A-Za-z0-9_\-]+(\/embed\/|\/embed\/captioned\/|)\??[/\w\-_\~%@\?;=#}\\\\]?~';
	$message = preg_replace($pattern, $replace, $message);
	if (strpos($cache_id, 'sig') !== false)
		$message = preg_replace('#\[instagram.*\](.*)\[\/instagram\]#i', '[url]$1[/url]', $message);
}

?>