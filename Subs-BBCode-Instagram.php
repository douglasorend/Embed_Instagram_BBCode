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

function BBCode_Instagram_LoadTheme()
{
	global $context, $settings;
	$context['html_headers'] .= '
	<link rel="stylesheet" type="text/css" href="' . $settings['default_theme_url'] . '/css/BBCode-Instagram.css" />';
}

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
		),
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '{width}|{height}|{frameborder}',
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
		'content' => '0|0|{frameborder}',
		'disabled_content' => '$1',
	);

	// Format: [instagram]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '0|0|0',
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
	global $txt;
	
	if (empty($data))
		return $txt['instagram_no_post_id'];
	$data = strtr(trim($data), array('<br />' => ''));
	if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
		$data = 'http://' . $data;
	if (strlen($data) !== 10)
	{
		$pattern = '#(http|https)://(|(.+?).)instagram.com/p/((.+?){10})(|(\?|/)(.+?))#i';
		if (!preg_match($pattern, $data, $parts))
			return $txt['instagram_no_post_id'];
		$data = $parts[4];
	}
	list($width, $height, $frameborder) = explode('|', $tag['content']);
	$tag['content'] = '<div style="' . (empty($width) ? '' : 'max-width: ' . $width . 'px;') . (empty($height) ? '' : 'max-height: ' . $height . 'px;') . '"><div class="instagram-wrapper">' .
		'<iframe src="https://instagram.com/p/' . $data .'/embed/" frameborder="' . $frameborder . '" scrolling="no" allowtransparency="true"></iframe></div></div>';
}

?>