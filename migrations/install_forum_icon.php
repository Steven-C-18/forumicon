<?php
/**
	* Forum Icon $extends the phpBB Software package.
	* @copyright (c) 2024, Steve, https://steven-clark.tech/
	* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace steve\forumicon\migrations;

class install_forum_icon extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('steve_forumicon_version');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_data()
	{
		return [
			['config.add', ['steve_forumicon_version', '0.9.0-dev']],
		];
	}

	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'forums'	=> [
					'forum_steve_icon'			=> ['VCHAR:255', ''],
					'forum_steve_icon_size'		=> ['INT:10', 0],
					'forum_steve_icon_color'	=> ['VCHAR:6', '']
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'forums'	=> [
					'forum_steve_icon',
					'forum_steve_icon_size',
					'forum_steve_icon_color'
				],
			],
		];
	}
}
