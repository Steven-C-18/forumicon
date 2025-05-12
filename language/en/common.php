<?php
/**
	* Forum Icon $extends the phpBB Software package.
	* @copyright (c) 2024, Steve, https://steven-clark.tech/
	* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// ’ » “ ” …

$lang = array_merge($lang, [
    'ACP_FORUM_STEVE_ICON'              => 'Forum FA icon',
    'ACP_FORUM_STEVE_ICON_EXP'          => 'Font Awesome Icons website',
    'ACP_FORUM_STEVE_ICON_SIZE'         => 'FA icon size',
    'ACP_FORUM_STEVE_ICON_COLOR'        => 'FA icon color',
	'ACP_FORUM_STEVE_ICON_AS_IMAGE'		=> 'FA icon as Forum image',
	//
	'ACP_FORUM_STEVE_ICON_WARN'         => 'FA icon can only contain the letters Aa-Zz and spaces',
    'ACP_FORUM_STEVE_COLOR_WARN'        => 'FA icon color incorrect'	
]);
