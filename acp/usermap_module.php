<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\acp;

class usermap_module extends \tas2580\usermap\includes\class_usermap
{
	public $u_action;

	protected $user;

	public function main($id, $mode)
	{
		global $config, $user, $template, $request, $table_prefix, $db;
		$user->add_lang_ext('tas2580/usermap', 'acp');
		$user->add_lang_ext('tas2580/usermap', 'country_codes');
		$this->user = $user;

		add_form_key('acp_usermap');

		switch ($mode)
		{
			case 'settings':
				$this->tpl_name = 'acp_usermap_settings';
				$this->page_title = $user->lang('ACP_USERMAP_TITLE');

				// Form is submitted
				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('acp_usermap'))
					{
						trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					// Set the new settings to config
					$config->set('tas2580_usermap_lon', substr($request->variable('usermap_lon', ''), 0, 10));
					$config->set('tas2580_usermap_lat', substr($request->variable('usermap_lat', ''), 0, 10));
					$config->set('tas2580_usermap_zoom', $request->variable('usermap_zoom', 0));
					$config->set('tas2580_usermap_map_type', $request->variable('map_type', ''));
					$config->set('tas2580_usermap_google_api_key', $request->variable('google_api_key', ''));
					$config->set('tas2580_usermap_bing_api_key', $request->variable('bing_api_key', ''));
					$config->set('tas2580_usermap_search_distance', $request->variable('search_distance', 0));
					$config->set('tas2580_usermap_map_in_viewprofile', $request->variable('map_in_viewprofile', 0));
					$config->set('tas2580_usermap_distance_in_viewtopic', $request->variable('distance_in_viewtopic', 0));
					$config->set('tas2580_usermap_distance_format', $request->variable('distance_format', 0));
					$config->set('tas2580_usermap_max_marker', $request->variable('max_marker', 100));
					$config->set('tas2580_usermap_input_method', $request->variable('input_method', 'zip'));
					$config->set('tas2580_usermap_force_on_register', $request->variable('force_on_register', 0));
					$config->set('tas2580_usermap_show_on_register', $request->variable('show_on_register', 0));
					$config->set('tas2580_usermap_display_coordinates', $request->variable('display_coordinates', 0));
					$config->set('tas2580_usermap_default_country', $request->variable('default_country', ''));

					trigger_error($user->lang('ACP_SAVED') . adm_back_link($this->u_action));
				}

				// Send the curent settings to template
				$template->assign_vars(array(
					'U_ACTION'					=> $this->u_action,
					'USERMAP_LON'				=> $config['tas2580_usermap_lon'],
					'USERMAP_LAT'				=> $config['tas2580_usermap_lat'],
					'USERMAP_ZOOM'				=> $config['tas2580_usermap_zoom'],
					'MAP_TYPE_SELECT'			=> $this->map_select($config['tas2580_usermap_map_type']),
					'GOOGLE_API_KEY'			=> $config['tas2580_usermap_google_api_key'],
					'BING_API_KEY'				=> $config['tas2580_usermap_bing_api_key'],
					'SEARCH_DISTANCE'			=> $config['tas2580_usermap_search_distance'],
					'MAP_IN_VIEWPROFILE'		=> $config['tas2580_usermap_map_in_viewprofile'],
					'DISTANCE_IN_VIEWTOPIC'		=> $config['tas2580_usermap_distance_in_viewtopic'],
					'DISTANCE_FORMAT'			=> $config['tas2580_usermap_distance_format'],
					'MAX_MARKER'				=> $config['tas2580_usermap_max_marker'],
					'INPUT_METHOD'				=> $config['tas2580_usermap_input_method'],
					'SHOW_ON_REGISTER'			=> $config['tas2580_usermap_show_on_register'],
					'FORCE_ON_REGISTER'			=> $config['tas2580_usermap_force_on_register'],
					'DISPLAY_COORDINATES'		=> $config['tas2580_usermap_display_coordinates'],
					'COUNTRY_SELECT'			=> $this->country_code_select($config['tas2580_usermap_default_country']),
				));
				break;

			case 'things':
				$action = $request->variable('action', '');
				switch ($action)
				{
					/**
					 * Add thing type
					 */
					case 'add':
						$marker = '';

						if ($request->is_set_post('add_type'))
						{
							$template->assign_vars(array(
								'TITLE'					=> $request->variable('title', '', true),
							));
						}
						// Form is submitted
						if ($request->is_set_post('submit'))
						{
							if (!check_form_key('acp_usermap'))
							{
								trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
							}

							$error = array();
							$title = $request->variable('title', '', true);
							$display_legend = $request->variable('display_legend', 0);
							$marker = $request->variable('marker', '', true);

							if (utf8_clean_string($title) === '')
							{
								$error[] = $this->user->lang['EMPTY_THING_TITLE'];
							}

							if (empty($marker))
							{
								$error[] = $this->user->lang['NEED_MARKER'];
							}
							if (sizeof($error))
							{
								$template->assign_vars(array(
									'ERROR'					=> implode('<br />', $error),
									'TITLE'					=> $title,
									'DISPLAY_LEGEND'		=> $display_legend,
								));
							}
							else
							{
								$sql_data = array(
									'place_type_title'			=> $title,
									'place_display_legend'		=> $display_legend,
									'place_type_marker'			=> $marker,
								);
								$sql = 'INSERT INTO ' . $table_prefix . 'usermap_place_types
									' . $db->sql_build_array('INSERT', $sql_data);
								$db->sql_query($sql);
								trigger_error($user->lang('ACP_THING_TYPE_ADD_SUCCESS') . adm_back_link($this->u_action));
							}
						}
						$this->tpl_name = 'acp_usermap_things_form';
						$this->page_title = $user->lang('ACP_USERMAP_ADD_PLACE_TYPE');
						global $phpbb_extension_manager;
						$this->phpbb_extension_manager = $phpbb_extension_manager;
						$template->assign_vars(array(
							'USERMAP_MARKER_PATH'			=> $this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things',
							'MARKER_OPTIONS'				=> $this->marker_image_select($marker, 'marker/things/'),
							'U_ACTION'						=> $this->u_action . '&amp;action=add',
						));
						break;

					/**
					 * Edit thing type
					 */
					case 'edit':
						$place_type_id = $request->variable('place_type_id', 0);
						$this->tpl_name = 'acp_usermap_things_form';
						$this->page_title = $user->lang('ACP_USERMAP_EDIT_PLACE_TYPE');

						$sql = 'SELECT place_type_title, place_type_marker, place_display_legend
							FROM ' . $table_prefix . 'usermap_place_types
							WHERE place_type_id = ' . (int) $place_type_id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);

						if ($request->is_set_post('submit'))
						{
							if (!check_form_key('acp_usermap'))
							{
								trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
							}

							$error = array();
							$title = $request->variable('title', '', true);
							$display_legend = $request->variable('display_legend', 0);
							$marker = $request->variable('marker', '', true);

							if (utf8_clean_string($title) === '')
							{
								$error[] = $this->user->lang['EMPTY_THING_TITLE'];
							}

							if (empty($marker))
							{
								$error[] = $this->user->lang['NEED_MARKER'];
							}
							if (sizeof($error))
							{
								$row['place_type_title'] = $title;
								$row['place_display_legend'] = $display_legend;
								$row['place_type_marker'] = $marker;
								$template->assign_vars(array(
									'ERROR'	=> implode('<br />', $error),
								));
							}
							else
							{
								$sql_data = array(
									'place_type_title'			=> $title,
									'place_display_legend'		=> $display_legend,
									'place_type_marker'			=> $marker,
								);
								$sql = 'UPDATE ' . $table_prefix . 'usermap_place_types SET
									' . $db->sql_build_array('UPDATE', $sql_data) . '
										WHERE place_type_id = ' . (int) $place_type_id;
								$db->sql_query($sql);
								trigger_error($user->lang('ACP_THING_TYPE_EDIT_SUCCESS') . adm_back_link($this->u_action));
							}
						}

						global $phpbb_extension_manager;
						$this->phpbb_extension_manager = $phpbb_extension_manager;
						$marker_path = $this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things';
						$template->assign_vars(array(
							'USERMAP_MARKER_PATH'			=> $marker_path,
							'MARKER_OPTIONS'				=> $this->marker_image_select($row['place_type_marker'], 'marker/things/'),
							'USERMAP_MARKER'				=> $marker_path . '/' . $row['place_type_marker'],
							'TITLE'							=> $row['place_type_title'],
							'DISPLAY_LEGEND'				=> $row['place_display_legend'],
							'U_ACTION'						=> $this->u_action . '&amp;action=edit&amp;place_type_id=' . $place_type_id,
						));
						break;

					/**
					 * Delete thing type
					 */
					case 'delete':
						$place_type_id = $request->variable('place_type_id', 0);

						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . $table_prefix . 'usermap_place_types WHERE place_type_id = ' . (int) $place_type_id;
							$result = $db->sql_query($sql);
							trigger_error($user->lang['THING_TYPE_DELETED'] . adm_back_link($this->u_action));
						}
						else
						{
							confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'action'			=> 'delete',
								'i'					=> $id,
								'place_type_id'		=> $place_type_id))
							);
						}

						break;

					default:
						$this->tpl_name = 'acp_usermap_things';
						$this->page_title = $user->lang('ACP_USERMAP_TITLE');
						// Form is submitted
						if ($request->is_set_post('submit'))
						{
							if (!check_form_key('acp_usermap'))
							{
								trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
							}

							// Set the new settings to config
							$config->set('tas2580_usermap_allow_bbcode', $request->variable('allow_bbcode', '', true));
							$config->set('tas2580_usermap_allow_smilies', $request->variable('allow_smilies', '', true));
							$config->set('tas2580_usermap_allow_urls', $request->variable('allow_urls', '', true));
							$config->set('tas2580_usermap_allow_img', $request->variable('allow_img', '', true));
							$config->set('tas2580_usermap_allow_flash', $request->variable('allow_flash', '', true));

							trigger_error($user->lang('ACP_SAVED') . adm_back_link($this->u_action));
						}
						// Send the curent settings to template
						$template->assign_vars(array(
							'U_ACTION'					=> $this->u_action,
							'U_ADD_THING_TYPE'			=> $this->u_action . '&amp;action=add',
							'ALLOW_BBCODE'				=> $config['tas2580_usermap_allow_bbcode'],
							'ALLOW_SMILIES'				=> $config['tas2580_usermap_allow_smilies'],
							'ALLOW_URL'					=> $config['tas2580_usermap_allow_urls'],
							'ALLOW_IMG'					=> $config['tas2580_usermap_allow_img'],
							'ALLOW_FLASH'				=> $config['tas2580_usermap_allow_flash'],
						));

						global $phpbb_extension_manager;
						$this->phpbb_extension_manager = $phpbb_extension_manager;

						$sql = 'SELECT place_type_id, place_type_title, place_type_marker
							FROM ' . $table_prefix . 'usermap_place_types
							ORDER BY place_type_title';
						$result = $db->sql_query($sql);
						while ($row = $db->sql_fetchrow($result))
						{
							$template->assign_block_vars('thing_types', array(
								'TITLE'		=> $row['place_type_title'],
								'MARKER'	=> $this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things/' . $row['place_type_marker'],
								'U_EDIT'	=> $this->u_action . '&amp;action=edit&amp;place_type_id=' . $row['place_type_id'],
								'U_DELETE'	=> $this->u_action . '&amp;action=delete&amp;place_type_id=' . $row['place_type_id'],
							));
						}
						break;
				}
				break;
		}
	}

	private function map_select($sel)
	{
		global $user;
		$maps = array(
			'osm_mapnik'		=> $user->lang('ACP_OSM_MACKNICK'),
			'google_terrain'	=> $user->lang('ACP_GOOGLE_TERRAIN'),
			'google_roadmap'	=> $user->lang('ACP_GOOGLE_ROADMAP'),
			'google_hybrid'		=> $user->lang('ACP_GOOGLE_HYBRID'),
			'google_satellite'	=> $user->lang('ACP_GOOGLE_SATELLITE'),
			'bing_road'			=> $user->lang('ACP_BING_ROAD'),
			'bing_hybrid'		=> $user->lang('ACP_BING_HYBRID'),
			'bing_aerial'		=> $user->lang('ACP_BING_AERIAL'),
		);

		$return = '';
		foreach ($maps as $map => $name)
		{
			$selected = ($sel == $map) ? ' selected="selected"' : '';
			$return .= '<option value="' . $map . '"' . $selected . '>' . $name . '</option>';
		}
		return $return;
	}
}
