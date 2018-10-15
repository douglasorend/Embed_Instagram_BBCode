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
	// Format: [instagram width=x height=x]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'parameters' => array(
			'width' => array('match' => '(\d+)'),
			'height' => array('match' => '(\d+)'),
		),
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '{width}|{height}',
		'disabled_content' => '$1',
	);

	// Format: [instagram]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'validate' => 'BBCode_Instagram_Validate',
		'content' => '612|710',
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
	if (empty($data))
		return ($tag['content'] = '');
	list($width, $height) = explode('|', $tag['content']);
	if (strlen($data) == 10)
		$tag['content'] = '<iframe src="https://instagram.com/p/' . $data .'/embed/" width="' . $width . '" height="'. $height . '" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
	else
		$tag['content'] = '<iframe src="' . $data . '/embed" width="' . $width . '" height="'. $height . '" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
}

?>