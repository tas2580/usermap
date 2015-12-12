<?php
/**
*
* @package phpBB Extension - Wiki
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_1_2 extends \phpbb\db\migration\migration
{

	public function update_data()
	{
		return array(
			array('permission.add', array('u_usermap_hide', true, 'u_')),
		);
	}
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_hide'	=> array('BOOL', 0),
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_legend'	=> array('BOOL', 1),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_hide',
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_legend',
				),
			),
		);
	}
}
