<?php
/**
*
* @package phpBB Extension - tas2580 Content Security Policy
* @copyright (c) 2014 tas2580
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

		$this->tpl_name = 'acp_usermap_body';
		$this->page_title = $user->lang('ACP_USERMAP_TITLE');

		$user->add_lang_ext('tas2580/usermap', 'acp');

		add_form_key('acp_usermap');

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

			trigger_error($user->lang('ACP_SAVED') . adm_back_link($this->u_action));
		}

		// Send the curent settings to template
		$template->assign_vars(array(
			'U_ACTION'				=> $this->u_action,
			'USERMAP_LON'			=> $config['tas2580_usermap_lon'],
			'USERMAP_LAT'				=> $config['tas2580_usermap_lat'],
			'USERMAP_ZOOM'			=> $config['tas2580_usermap_zoom'],
			'MAP_TYPE_SELECT'			=> $this->map_select($config['tas2580_usermap_map_type']),
			'GOOGLE_API_KEY'			=> $config['tas2580_usermap_google_api_key'],
			'SEARCH_DISTANCE'			=> $config['tas2580_usermap_search_distance'],
			'MAP_IN_VIEWPROFILE'		=> $config['tas2580_usermap_map_in_viewprofile'],
			'DISTANCE_IN_VIEWTOPIC'		=> $config['tas2580_usermap_distance_in_viewtopic'],
			'DISTANCE_FORMAT'			=> $config['tas2580_usermap_distance_format'],


		));
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
