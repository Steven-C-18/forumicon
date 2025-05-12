<?php
/**
 * forum icon. An extension for the phpBB Forum Software package.
 * @copyright (c) 2022, Steve, https://www.stevenclark.eu/phpBB3/
 */

namespace steve\forumicon\migrations;

use \phpbb\db\migration\container_aware_migration;

class mu1_forum_icon extends container_aware_migration
{
	static public function depends_on()
	{
		return ['\steve\forumicon\migrations\install_forum_icon'];
	}
	
	public function update_data()
	{
		return [
			['config.update', ['steve_forumicon_version', '0.9.5-dev']],
		];
	}
}
