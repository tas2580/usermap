<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_1_4 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\tas2580\usermap\migrations\update_0_1_2',
		);
	}

	public function update_data()
	{
		return array(
			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_USERMAP_TITLE',
				array(
					'module_basename'	=> '\tas2580\usermap\acp\usermap_module',
					'modes'				=> array('things'),
				),
			)),

			// Add config values
			array('config.add', array('tas2580_usermap_input_method', 'cord')),
			array('config.add', array('tas2580_usermap_force_on_register', '0')),
			array('config.add', array('tas2580_usermap_show_on_register', '0')),
			array('config.add', array('tas2580_usermap_thing_name', 'THING')),
			array('config.add', array('tas2580_usermap_display_coordinates', '1')),
			array('config.add', array('tas2580_usermap_allow_bbcode', '1')),
			array('config.add', array('tas2580_usermap_allow_smilies', '1')),
			array('config.add', array('tas2580_usermap_allow_urls', '1')),
			array('config.add', array('tas2580_usermap_allow_img', '1')),
			array('config.add', array('tas2580_usermap_allow_flash', '1')),

			// Add permissions
			array('permission.add', array('u_usermap_add_thing', true, 'u_')),
			array('permission.add', array('u_usermap_edit_thing', true, 'm_')),
			array('permission.add', array('u_usermap_delete_thing', true, 'm_')),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_zip'	=> array('VCHAR:10', ''),
				),
			),
			'add_tables'	=> array(
				$this->table_prefix . 'usermap_things'	=> array(
					'COLUMNS'	=> array(
						'thing_id'				=> array('UINT', null, 'auto_increment'),
						'thing_title'			=> array('VCHAR:255', ''),
						'thing_text'			=> array('MTEXT_UNI', ''),
						'bbcode_uid'			=> array('VCHAR:10', ''),
						'bbcode_bitfield'		=> array('VCHAR:32', ''),
						'thing_lat'				=> array('VCHAR:10', ''),
						'thing_lon'				=> array('VCHAR:10', ''),
						'thing_marker'			=> array('VCHAR:255', ''),
						'thing_user_id'			=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> 'thing_id',
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_zip',
				),
			),
			'drop_tables'	=> array(
				$this->table_prefix . 'usermap_things',
			),
		);
	}
}
