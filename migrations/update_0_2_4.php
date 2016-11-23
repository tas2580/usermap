<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\migrations;

class update_0_2_4 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\tas2580\usermap\migrations\update_0_2_3',
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array(&$this, 'rename_table'))),
		);
	}



	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'usermap_places',
			),
		);
	}


	public function rename_table()
	{
		$sql = "RENAME TABLE " . $this->table_prefix . "usermap_things TO " . $this->table_prefix . "usermap_places";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_id place_id INT AUTO_INCREMENT";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_title place_title VARCHAR(255)";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_text place_text TEXT";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_lat place_lat VARCHAR(255)";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_lon place_lon VARCHAR(255)";
		$this->db->sql_query($sql);

		$sql = "ALTER TABLE " . $this->table_prefix . "usermap_places CHANGE thing_user_id user_id INT";
		$this->db->sql_query($sql);
	}

}
