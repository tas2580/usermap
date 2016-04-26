<?php
/**
*
* @package phpBB Extension - Wiki
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license https://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_1_3_mod extends \phpbb\db\migration\migration
{

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_mail'	=> array('VCHAR:100', ''),
					'user_usermap_phone'	=> array('VCHAR:50', ''),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_mail',
					'user_usermap_phone',
				),
			),
		);
	}
}
