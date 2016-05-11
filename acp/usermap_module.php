<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\acp;

class usermap_module
{
	public $u_action;

	public function main($id, $mode)
	{
		global $config, $user, $template, $request;
		$user->add_lang_ext('tas2580/usermap', 'acp');

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
					$config->set('tas2580_usermap_search_distance', $request->variable('search_distance', 0));
					$config->set('tas2580_usermap_map_in_viewprofile', $request->variable('map_in_viewprofile', 0));
					$config->set('tas2580_usermap_distance_in_viewtopic', $request->variable('distance_in_viewtopic', 0));
					$config->set('tas2580_usermap_distance_format', $request->variable('distance_format', 0));
					$config->set('tas2580_usermap_max_marker', $request->variable('max_marker', 100));
					$config->set('tas2580_usermap_input_method', $request->variable('input_method', 'zip'));
					$config->set('tas2580_usermap_force_on_register', $request->variable('force_on_register', 0));
					$config->set('tas2580_usermap_show_on_register', $request->variable('show_on_register', 0));
					$config->set('tas2580_usermap_display_coordinates', $request->variable('display_coordinates', 0));


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
					'SEARCH_DISTANCE'			=> $config['tas2580_usermap_search_distance'],
					'MAP_IN_VIEWPROFILE'		=> $config['tas2580_usermap_map_in_viewprofile'],
					'DISTANCE_IN_VIEWTOPIC'		=> $config['tas2580_usermap_distance_in_viewtopic'],
					'DISTANCE_FORMAT'			=> $config['tas2580_usermap_distance_format'],
					'MAX_MARKER'				=> $config['tas2580_usermap_max_marker'],
					'INPUT_METHOD'				=> $config['tas2580_usermap_input_method'],
					'SHOW_ON_REGISTER'			=> $config['tas2580_usermap_show_on_register'],
					'FORCE_ON_REGISTER'			=> $config['tas2580_usermap_force_on_register'],
					'DISPLAY_COORDINATES'		=> $config['tas2580_usermap_display_coordinates'],
				));
				break;

			case 'things':
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
					$config->set('tas2580_usermap_thing_name', $request->variable('thing_name', '', true));
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
					'THING_NAME'				=> $config['tas2580_usermap_thing_name'],
					'ALLOW_BBCODE'				=> $config['tas2580_usermap_allow_bbcode'],
					'ALLOW_SMILIES'				=> $config['tas2580_usermap_allow_smilies'],
					'ALLOW_URL'					=> $config['tas2580_usermap_allow_urls'],
					'ALLOW_IMG'					=> $config['tas2580_usermap_allow_img'],
					'ALLOW_FLASH'				=> $config['tas2580_usermap_allow_flash'],
				));
				break;


		}





	}

	private function map_select($sel)
	{
		global $user;
		$maps = array(
			'osm'	=> $user->lang('ACP_MAP_OSM'),
			'google'	=> $user->lang('ACP_MAP_GOOGLE'),
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
