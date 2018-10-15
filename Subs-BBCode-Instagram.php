<?php
/**********************************************************************************
* Subs-BBCode-Instagram.php
***********************************************************************************
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/

function BBCode_Instagram(&$bbc)
{
	// Format: [instagram width=x height=x]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'parameters' => array(
			'width' => array('value' => ' width="$1"', 'match' => '(\d+)'),
			'height' => array('value' => ' height="$1"', 'match' => '(\d+)'),
		),
		'content' => '<iframe src="https://instagram.com/p/$1/embed/"{width}{height} frameborder="0" scrolling="no" allowtransparency="true"></iframe>',
		'disabled_content' => '$1',
	);

	// Format: [instagram]{instagram ID}[/instagram]
	$bbc[] = array(
		'tag' => 'instagram',
		'type' => 'unparsed_content',
		'content' => '<iframe src="https://instagram.com/p/$1/embed/" width="612" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe>',
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

?>