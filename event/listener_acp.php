<?php
/**
*
* @package phpBB Extension - tas2580 Mobile Notifier
* @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener_acp implements EventSubscriberInterface
{	/** @var string */
	protected $phpbb_extension_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/**
	* Constructor
	*
	* @param \phpbb\request\request			$request			Request object
	* @param \phpbb\user					$user			User Object
	* @param \phpbb\template\template		$template			Template Object
	* @param Container					$phpbb_container
	* @param string						$phpbb_root_path	phpbb_root_path
	* @access public
	*/
	public function __construct($phpbb_extension_manager, \phpbb\path_helper $path_helper, \phpbb\request\request $request, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path)
	{
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->path_helper = $path_helper;
		$this->request = $request;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;

		$user->add_lang_ext('tas2580/usermap', 'acp');
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_users_modify_profile'						=> 'acp_profile_modify_profile_info',
			'core.acp_users_profile_modify_sql_ary'				=> 'acp_profile_info_modify_sql_ary',
			'core.acp_manage_group_display_form'				=> 'acp_manage_group_display_form',
			'core.acp_manage_group_request_data'				=> 'acp_manage_group_request_data',
			'core.acp_manage_group_initialise_data'				=> 'acp_manage_group_initialise_data',
			'core.acp_board_config_edit_add'					=> 'acp_board_config_edit_add',
		);
	}
	/**
	* Add a new data field to the ACP
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_profile_modify_profile_info($event)
	{
		$row = $event['user_row'];
		$lon = substr($this->request->variable('usermap_lon', $row['user_usermap_lon']), 0, 10);
		$lat = substr($this->request->variable('usermap_lat', $row['user_usermap_lat']), 0, 10);

		$event['user_row'] = array_merge($event['user_row'], array(
			'user_usermap_lon'		=> empty($lon) ? '' : $lon,
			'user_usermap_lat'		=> empty($lat) ? '' : $lat,
			'user_usermap_hide'		=> (int) $this->request->variable('usermap_hide', $row['user_usermap_hide']),
		));
		$this->add_field($event['user_row']['user_usermap_lon'], $event['user_row']['user_usermap_lat'], $event['user_row']['user_usermap_hide']);
	}

	/**
	* Admin has changed his whatsapp number, update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_profile_info_modify_sql_ary($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_usermap_lon'		=> $event['user_row']['user_usermap_lon'],
			'user_usermap_lat'		=> $event['user_row']['user_usermap_lat'],
			'user_usermap_hide'		=> $event['user_row']['user_usermap_hide'],
		));
	}

	/**
	* Validate users changes to their whatsapp number
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_profile_validate_profile_info($event)
	{
			$array = $event['error'];
			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$validate_array = array(
				'user_usermap_lon'	=> array('string', false, 0, 12),
				'user_usermap_lat'	=> array('string', false, 0, 12),
			);

			$error = validate_data($event['data'], $validate_array);
			$event['error'] = array_merge($array, $error);
	}

	/**
	 * evtl weg
	 */
	public function acp_manage_group_initialise_data($event)
	{
		$test_variables = $event['test_variables'];
		$test_variables['usermap_marker']  = 'string';
		$test_variables['usermap_legend']  = 'bool';
		$event['test_variables'] = $test_variables;
	}

	public function acp_manage_group_request_data($event)
	{
		$validation_checks = $event['validation_checks'];
		$validation_checks['usermap_marker'] = array('string', true, 5, 255);
		$event['validation_checks'] = $validation_checks;

		$submit_ary = $event['submit_ary'];
		$submit_ary['usermap_marker'] = $this->request->variable('usermap_marker', '');
		$submit_ary['usermap_legend'] = $this->request->variable('usermap_legend', 0);
		$event['submit_ary'] = $submit_ary;
	}

	public function acp_manage_group_display_form($event)
	{
		$data = $event['group_row'];
		$path = $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/');
		$this->template->assign_vars(array(
			'USERMAP_MARKER'				=> (!empty($data['group_usermap_marker'])) ? $path. $data['group_usermap_marker'] : $this->path_helper->update_web_root_path($this->phpbb_root_path . '/images/'). 'spacer.gif',
			'USERMAP_MARKER_PATH'		=> $path,
			'USERMAP_OPTIONS'				=> $this->marker_image_select($data['group_usermap_marker']),
			'USERMAP_LEGEND'				=> $data['group_usermap_legend'],
		));
	}

	/**
	* Add field to acp_board load settings page
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function acp_board_config_edit_add($event)
	{
		if ($event['mode'] == 'load')
		{
			$display_vars = $event['display_vars'];
			$insert = array('tas2580_usermap_map_in_viewprofile' => array('lang' => 'ACP_MAP_IN_VIEWPROFILE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true));
			$display_vars['vars'] = $this->array_insert($display_vars['vars'], 'legend3', $insert);
			$event['display_vars'] = $display_vars;
		}
	}

	private function array_insert(&$array, $position, $insert)
	{
		if (is_int($position))
		{
			array_splice($array, $position, 0, $insert);
		}
		else
		{
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
		return $array;
	}

	private function marker_image_select($marker)
	{
		$path = $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/');

		$imglist = filelist($path);
		$edit_img = $filename_list = '';

		foreach ($imglist as $path => $img_ary)
		{
			sort($img_ary);

			foreach ($img_ary as $img)
			{
				$img = $path . $img;

				if ($img == $marker)
				{
					$selected = ' selected="selected"';
					$edit_img = $img;
				}
				else
				{
					$selected = '';
				}

				if (strlen($img) > 255)
				{
					continue;
				}

				$filename_list .= '<option value="' . htmlspecialchars($img) . '"' . $selected . '>' . $img . '</option>';
			}
		}

		return '<option value=""' . (($edit_img == '') ? ' selected="selected"' : '') . '>----------</option>' . $filename_list;
	}

	/**
	 * Add the field to user profile
	 */
	private function add_field($lon, $lat, $hide)
	{
		$this->template->assign_vars(array(
			'USERMAP_LON'	=> $lon,
			'USERMAP_LAT'		=> $lat,
			'USERMAP_HIDE'	=> $hide,
		));
	}
}
