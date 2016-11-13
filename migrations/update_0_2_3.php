<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_2_3 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\tas2580\usermap\migrations\update_0_2_2',
		);
	}

	public function update_data()
	{
		return array(

			array('module.add', array(
				'acp',
				'ACP_USERMAP_TITLE',
				array(
					'module_basename'	=> '\tas2580\usermap\acp\usermap_module',
					'modes'				=> array('maps'),
				),
			)),

			array('permission.add', array('m_usermap_comment_delete', true, 'm_')),
			array('permission.add', array('m_usermap_comment_edit', true, 'm_')),
			array('permission.add', array('m_usermap_place_edit', true, 'm_')),
			array('permission.add', array('m_usermap_place_delete', true, 'm_')),
			array('permission.remove', array('u_usermap_edit_thing')),
			array('permission.remove', array('u_usermap_delete_thing')),

			array('custom', array(array(&$this, 'install_maps'))),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
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
			),

			'add_columns'	=> array(
				$this->table_prefix . 'usermap_place_comments'	=> array(
					'place_comment_time'	=> array('TIMESTAMP', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'usermap_maps',
			),
		);
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
	}

}
