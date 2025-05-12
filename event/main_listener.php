<?php
/**
	* Forum Icon $extends the phpBB Software package.
	* @copyright (c) 2024, Steve, https://steven-clark.tech/
	* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace steve\forumicon\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	protected $language;
	protected $request;

	public function __construct(
		\phpbb\language\language $language,
		\phpbb\request\request $request)
	{	
		$this->language = $language;
		$this->request 	= $request;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.display_forums_modify_forum_rows'				=> 'modify_forum_rows',
			'core.display_forums_modify_category_template_vars' => 'display_forums_modify_cat_vars',
			'core.display_forums_modify_template_vars'			=> 'display_forums_modify_template_vars',
			'core.generate_forum_nav'							=> 'forum_nav',

			'core.acp_manage_forums_request_data'				=> 'forums_request_data',
			'core.acp_manage_forums_initialise_data'			=> 'forums_initialise_data',
			'core.acp_manage_forums_display_form'				=> 'forums_display_form',
			'core.acp_manage_forums_validate_data'				=> 'forums_validate_data',
		];
	}

	public function modify_forum_rows($event)
	{
		$subforums = $event['subforums'];
		if (empty($subforums))
		{
			return;
		}

		$parent_id = $event['parent_id'];
		$row = $event['row'];

		$forum_id = $row['forum_id'];

		if (empty($subforums[$parent_id][$row['forum_id']]['display']))
		{
			return;
		}

 		$subforums[$parent_id][$forum_id]['forum_steve_icon'] = $row['forum_steve_icon'];
 		$subforums[$parent_id][$forum_id]['forum_steve_icon_size'] = $row['forum_steve_icon_size'];
 		$subforums[$parent_id][$forum_id]['forum_steve_icon_color'] = $row['forum_steve_icon_color'];

 		$subforums[$parent_id][$row['forum_id']]['name'] = $this->add_forum_icon($row, $subforums[$parent_id][$forum_id]['name']);

		$event['subforums'] = $subforums;
		$event['parent_id'] = $parent_id;
		$event['row'] = $row;
	}

	public function display_forums_modify_cat_vars($event)
	{
		$forum_row = $event['cat_row'];
		if (empty($forum_row))
		{
			return;
		}

		$row = $event['row'];

		$forum_row['FORUM_NAME'] = $this->add_forum_icon($row, $forum_row['FORUM_NAME']);

		$event['cat_row'] = $forum_row;
		$event['row'] = $row;
	}

	public function display_forums_modify_template_vars($event)
	{
		$forum_row = $event['forum_row'];
		if (empty($forum_row))
		{
			return;
		}

		$row = $event['row'];

		if (empty($row['forum_steve_icon_as_image']))
		{
			$forum_row['FORUM_NAME'] = $this->add_forum_icon($row, $forum_row['FORUM_NAME']);
		}
		else
		{
			$forum_row['FORUM_IMAGE'] = $this->add_forum_icon($row, $forum_row['FORUM_IMAGE']);
		}

		$event['forum_row'] = $forum_row;
		$event['row'] = $row;
	}

	public function forum_nav($event)
	{
		$forum_data = $event['forum_data'];
		if (empty($forum_data))
		{
			return;
		}

		$forum_template_data = $event['forum_template_data'];

		$forum_template_data['FORUM_NAME'] = $this->add_forum_icon($forum_data, $forum_template_data['FORUM_NAME']);

		$event['forum_template_data'] = $forum_template_data;
		$forum_data['forum_data'] = $forum_data;
	}

	public function forums_request_data($event)
	{
		$forum_data = $event['forum_data'];
		if (empty($forum_data))
		{
			return;
		}

		$forum_steve_icon_color = $this->request->variable('forum_steve_icon_color', '');
		$forum_steve_icon_color = str_replace('#', '', $forum_steve_icon_color);

		$forum_data['forum_steve_icon'] = $this->request->variable('forum_steve_icon', '');
		$forum_data['forum_steve_icon_size'] = $this->request->variable('forum_steve_icon_size', 0);
		$forum_data['forum_steve_icon_color'] = $forum_steve_icon_color;
		$forum_data['forum_steve_icon_as_image'] = $this->request->variable('forum_steve_icon_as_image', false);

		$event['forum_data'] = $forum_data;
	}

	public function forums_initialise_data($event)
	{
		if ($event['action'] != 'add')
		{
			return;
		}

	 	$forum_data = $event['forum_data'];

		$forum_data['forum_steve_icon'] = '';
		$forum_data['forum_steve_icon_size'] = 0;
		$forum_data['forum_steve_icon_color'] = '';
		$forum_data['forum_steve_icon_as_image'] = '';

		$event['forum_data'] = $forum_data;
	}

	public function forums_display_form($event)
	{
		$this->language->add_lang('common', 'steve/forumicon');

		$template_data = $event['template_data'];

		$template_data = array_merge($template_data, [
			'FORUM_STEVE_ICON'			=> $event['forum_data']['forum_steve_icon'],
			'FORUM_STEVE_ICON_SIZE'		=> $event['forum_data']['forum_steve_icon_size'],
			'FORUM_STEVE_ICON_COLOR'	=> $event['forum_data']['forum_steve_icon_color'],
			'FORUM_STEVE_ICON_AS_IMAGE'	=> $event['forum_data']['forum_steve_icon_as_image'],
			'S_FORUM_STEVE_ICON'		=> true,
		]);

		$event['template_data'] = $template_data;
	}

	private function add_forum_icon($row, $forum_name)
	{
		if (empty($row['forum_steve_icon']))
		{
			return $forum_name;
		}

		$font_size = !empty($row['forum_steve_icon_size']) ? $row['forum_steve_icon_size'] : (int) 15;
		$font_color = !empty($row['forum_steve_icon_color']) ? $row['forum_steve_icon_color'] : '';

		return "<i class='icon steve-forum-icon " . $row['forum_steve_icon'] . " fa-fw' style='color: #" . $font_color . " !important;font-size: " . $font_size . "px;'></i>" . ' ' . $forum_name;
	}

	private function validate_icon($forum_steve_icon)
	{
		return $forum_steve_icon != '' && !preg_match('/^[a-z]+[ a-z0-9-]+$/', $forum_steve_icon) ? true : false;
	}

	private function validate_color($forum_steve_icon_color)
	{
		return $forum_steve_icon_color != '' && !preg_match('/^([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/', $forum_steve_icon_color) ? true : false;
	}

	public function forums_validate_data($event)
	{
		$this->language->add_lang('common', 'steve/forumicon');
		$errors = [];

		if ($this->validate_icon($event['forum_data']['forum_steve_icon']))
		{
			$errors[] = $this->language->lang('ACP_FORUM_STEVE_ICON_WARN');
		}

		if ($this->validate_color($event['forum_data']['forum_steve_icon_color']))
		{
			$errors[] = $this->language->lang('ACP_FORUM_STEVE_COLOR_WARN');
		}

		$event['errors'] = array_merge($event['errors'], $errors);
	}
}
