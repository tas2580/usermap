<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class initial_module extends \phpbb\db\migration\migration
{

	public function update_data()
	{
		return array(
			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_USERMAP_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_USERMAP_TITLE',
				array(
					'module_basename'	=> '\tas2580\usermap\acp\usermap_module',
					'modes'				=> array('settings'),
				),
			)),

			// Add config values
			array('config.add', array('tas2580_usermap_lon', '8.0000')),
			array('config.add', array('tas2580_usermap_lat', '48.0000')),
			array('config.add', array('tas2580_usermap_zoom', '7')),
			array('config.add', array('tas2580_usermap_map_type', 'osm')),
			array('config.add', array('tas2580_usermap_google_api_key', '')),
			array('config.add', array('tas2580_usermap_search_distance', '50')),
			array('config.add', array('tas2580_usermap_map_in_viewprofile', '1')),
			array('config.add', array('tas2580_usermap_distance_in_viewtopic', '1')),
			array('config.add', array('tas2580_usermap_distance_format', '1')),
			array('config.add', array('tas2580_usermap_max_marker', '100')),

			// Add permissions
			array('permission.add', array('u_usermap_view', true, 'u_')),
			array('permission.add', array('u_usermap_add', true, 'u_')),
			array('permission.add', array('u_usermap_search', true, 'u_')),

			array('custom', array(array($this, 'insert_icons'))),
		);
	}
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_lon'	=> array('VCHAR:10', ''),
					'user_usermap_lat'	=> array('VCHAR:10', ''),
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_marker'	=> array('VCHAR:255', ''),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_lon',
					'user_usermap_lat',
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_marker',
				),
			),
		);
	}

	/**
	 * Insert icons into group tables
	 */
	public function insert_icons()
	{
		$sql = 'UPDATE ' . GROUPS_TABLE . "
			SET group_usermap_marker = 'user.png'
			WHERE group_id = 2
				OR group_id = 3
				OR group_id = 7";
		$this->sql_query($sql);

		$sql = 'UPDATE ' . GROUPS_TABLE . "
			SET group_usermap_marker = 'moderator.png'
			WHERE group_id = 4";
		$this->sql_query($sql);

		$sql = 'UPDATE ' . GROUPS_TABLE . "
			SET group_usermap_marker = 'admin.png'
			WHERE group_id = 5";
		$this->sql_query($sql);
	}
}
