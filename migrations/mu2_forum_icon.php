<?php
/**
	* Forum Icon $extends the phpBB Software package.
	* @copyright (c) 2024, Steve, https://steven-clark.tech/
	* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace steve\forumicon\migrations;

use \phpbb\db\migration\container_aware_migration;

class mu2_forum_icon extends container_aware_migration
{
	static public function depends_on()
	{
		return ['\steve\forumicon\migrations\mu1_forum_icon'];
	}
	
	public function update_data()
	{
		return [
			['config.update', ['steve_forumicon_version', '1.0.0']],
		];
	}
}
