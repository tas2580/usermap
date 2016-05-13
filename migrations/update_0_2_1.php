<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_2_1 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\tas2580\usermap\migrations\update_0_1_4',
		);
	}

	public function update_data()
	{
		return array(
			// Add config values
			array('config.add', array('tas2580_usermap_default_country', '')),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_default_country'	=> array('VCHAR:2', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_default_country',
				),
			),
		);
	}

}
