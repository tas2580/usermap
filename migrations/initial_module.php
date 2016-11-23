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
			array('module.add', array(
				'acp',
				'ACP_USERMAP_TITLE',
				array(
					'module_basename'	=> '\tas2580\usermap\acp\usermap_module',
					'modes'				=> array('things'),
				),
			)),
			array('module.add', array(
				'acp',
				'ACP_USERMAP_TITLE',
				array(
					'module_basename'	=> '\tas2580\usermap\acp\usermap_module',
					'modes'				=> array('maps'),
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
			array('config.add', array('tas2580_usermap_default_country', '')),

			// Add permissions
			array('permission.add', array('u_usermap_view', true, 'u_')),
			array('permission.add', array('u_usermap_add', true, 'u_')),
			array('permission.add', array('u_usermap_search', true, 'u_')),
			array('permission.add', array('u_usermap_hide', true, 'u_')),
			array('permission.add', array('u_usermap_add_thing', true, 'u_usermap_add')),
			array('permission.add', array('u_usermap_comment', true, 'u_')),
			array('permission.add', array('m_usermap_comment_delete', true, 'm_')),
			array('permission.add', array('m_usermap_comment_edit', true, 'm_')),
			array('permission.add', array('m_usermap_place_edit', true, 'm_')),
			array('permission.add', array('m_usermap_place_delete', true, 'm_')),


			array('custom', array(array($this, 'insert_icons'))),
			array('custom', array(array($this, 'install_maps'))),
		);
	}
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_lon'				=> array('VCHAR:10', ''),
					'user_usermap_lat'				=> array('VCHAR:10', ''),
					'user_usermap_hide'				=> array('BOOL', 0),
					'user_usermap_zip'				=> array('VCHAR:10', ''),
					'user_usermap_default_country'	=> array('VCHAR:2', ''),
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_marker'	=> array('VCHAR:255', ''),
					'group_usermap_legend'	=> array('BOOL', 1),
				),
			),
			'add_tables'	=> array(
				$this->table_prefix . 'usermap_places'	=> array(
					'COLUMNS'	=> array(
						'place_id'				=> array('UINT', null, 'auto_increment'),
						'place_title'			=> array('VCHAR:255', ''),
						'place_text'			=> array('MTEXT_UNI', ''),
						'bbcode_uid'			=> array('VCHAR:10', ''),
						'bbcode_bitfield'		=> array('VCHAR:32', ''),
						'place_lat'				=> array('VCHAR:10', ''),
						'place_lon'				=> array('VCHAR:10', ''),
						'place_type_id'			=> array('UINT', 0),
						'user_id'				=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> 'place_id',
				),
				$this->table_prefix . 'usermap_maps'	=> array(
					'COLUMNS'	=> array(
						'map_id'					=> array('UINT', null, 'auto_increment'),
						'map_name'					=> array('VCHAR:255', ''),
						'map_display_name'			=> array('VCHAR:255', ''),
						'map_active'				=> array('BOOL', 0),
						'map_default'				=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> 'map_id',
				),
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
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array(
					'user_usermap_lon',
					'user_usermap_lat',
					'user_usermap_hide',
					'user_usermap_zip',
					'user_usermap_default_country',
				),
				$this->table_prefix . 'groups'	=> array(
					'group_usermap_marker',
					'group_usermap_legend',
				),
			),
			'drop_tables'	=> array(
				$this->table_prefix . 'usermap_places',
				$this->table_prefix . 'usermap_place_types',
				$this->table_prefix . 'usermap_place_comments',
				$this->table_prefix . 'usermap_maps',
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

	public function install_maps()
	{
		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('osm_mapnik', 'OSM Mapnik', 1, 1);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('osm_cyclemap', 'OSM Cyclemap', 1, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('transportmap', 'OSM Transportmap', 1, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Landscape', 'OSM Landscape', 1, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Toner', 'OSM Toner', 1, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Watercolor', 'OSM Watercolor', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Maptookit', 'Maptookit', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('OpenSnowMap', 'Open Snow Map', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Esri', 'Esri', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriSatellite', 'Esri Satellite', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriPhysical', 'Esri Physical', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriShadedRelief', 'Esri Shaded Relief', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriTerrain', 'Esri Terrain', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriTopo', 'Esri Topo', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriGray', 'Esri Gray', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriNationalGeographic', 'Esri National Geographic', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('EsriOcean', 'Esri Ocean', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Komoot', 'Komoot', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('CartoDBLight', 'CartoDB Light', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('CartoDBDark', 'CartoDB Dark', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Sputnik', 'Sputnik', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('Kosmosnimki', 'Kosmosnimki', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('google_terrain', 'Google Terrain', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('google_roadmap', 'Google Roadmap', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('google_hybrid', 'Google Hybrid', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('google_satellite', 'Google Satellite', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('bing_road', 'Bing Roadmap', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('bing_hybrid', 'Bing Hybrid', 0, 0);";
		$this->db->sql_query($sql);

		$sql = "INSERT INTO " . $this->table_prefix . "usermap_maps (map_name, map_display_name, map_active, map_default)
			VALUES ('bing_aerial', 'Bing Satellite', 0, 0);";
		$this->db->sql_query($sql);

		// Add Marker
		$sql = "INSERT INTO " . $this->table_prefix . "usermap_place_types (place_type_title, place_type_marker, place_display_legend)
			VALUES ('Place', 'thing.png', 1);";
		$this->db->sql_query($sql);

	}
}
