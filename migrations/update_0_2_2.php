<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_2_2 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\tas2580\usermap\migrations\update_0_2_1',
		);
	}

	public function update_data()
	{
		return array(
			array('permission.add', array('u_usermap_comment', true, 'u_')),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'usermap_place_types'	=> array(
					'COLUMNS'	=> array(
						'place_type_id'				=> array('UINT', null, 'auto_increment'),
						'place_type_title'			=> array('VCHAR:255', ''),
						'place_type_marker'			=> array('VCHAR:255', ''),
						'place_display_legend'		=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> 'place_type_id',
				),
				$this->table_prefix . 'usermap_place_comments'	=> array(
					'COLUMNS'	=> array(
						'place_comment_id'				=> array('UINT', null, 'auto_increment'),
						'place_comment_title'			=> array('VCHAR:255', ''),
						'place_comment_text'			=> array('MTEXT_UNI', ''),
						'place_comment_bbcode_uid'		=> array('VCHAR:10', ''),
						'place_comment_bbcode_bitfield'	=> array('VCHAR:32', ''),
						'place_comment_user_id'			=> array('UINT', 0),
						'place_id'						=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> 'place_comment_id',
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'usermap_things'	=> array(
					'place_type_id'	=> array('UINT', 0),
				),
			),
			'drop_columns' => array(
				$this->table_prefix . 'usermap_things'	=> array(
					'thing_marker',
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'usermap_thing_types',
				$this->table_prefix . 'usermap_thing_comments',
			),
		);
	}

}
