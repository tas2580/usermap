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
class listener_ucp extends \tas2580\usermap\includes\class_usermap implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path)
	{
		$this->auth = $auth;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;

		$user->add_lang_ext('tas2580/usermap', 'ucp');
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
			'core.ucp_profile_modify_profile_info'					=> 'ucp_profile_modify_profile_info',
			'core.ucp_profile_validate_profile_info'				=> 'ucp_profile_validate_profile_info',
			'core.ucp_profile_info_modify_sql_ary'				=> 'ucp_profile_info_modify_sql_ary',
		);
	}


	/**
	* Add a new data field to the UCP
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_modify_profile_info($event)
	{
		// Only if the user can add to map
		if ($this->auth->acl_get('u_usermap_add'))
		{
			$hide = $this->auth->acl_get('u_usermap_hide') ? $this->request->variable('usermap_hide', $this->user->data['user_usermap_hide']) : 0;
			$lon = substr($this->request->variable('usermap_lon', $this->user->data['user_usermap_lon']), 0, 10);
			$lat = substr($this->request->variable('usermap_lat', $this->user->data['user_usermap_lat']), 0, 10);
			$event['data'] = array_merge($event['data'], array(
				'user_usermap_lon'		=> empty($lon) ? '' : $lon,
				'user_usermap_lat'		=> empty($lat) ? '' : $lat,
				'user_usermap_hide'		=> (int) $hide,
			));

			$this->add_field($event['data']['user_usermap_lon'], $event['data']['user_usermap_lat'], $event['data']['user_usermap_hide']);
		}
	}

	/**
	* Validate users changes to their whatsapp number
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_validate_profile_info($event)
	{
		// Only if the user can add to map
		if ($this->auth->acl_get('u_usermap_add'))
		{
			$array = $event['error'];
			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$validate_array = array(
				'user_usermap_lon'		=> array('match', false, self::REGEX_LON),
				'user_usermap_lat'		=> array('match', false, self::REGEX_LAT),
			);

			$error = validate_data($event['data'], $validate_array);
			$event['error'] = array_merge($array, $error);
		}
	}

	/**
	* User has changed his whatsapp number, update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_info_modify_sql_ary($event)
	{
		// Only if the user can add to map
		if ($this->auth->acl_get('u_usermap_add'))
		{
			$event['sql_ary'] = array_merge($event['sql_ary'], array(
				'user_usermap_lon'	=> $event['data']['user_usermap_lon'],
				'user_usermap_lat'	=> $event['data']['user_usermap_lat'],
				'user_usermap_hide'	=> $event['data']['user_usermap_hide'],
			));
		}
	}


	/**
	 * Add the field to user profile
	 */
	private function add_field($lon, $lat, $hide)
	{
		$this->template->assign_vars(array(
			'USERMAP_LON'						=> $lon,
			'USERMAP_LAT'							=> $lat,
			'S_ADD_USERMAP'						=> true,
			'USERMAP_HIDE'						=> $hide,
			'A_USERMAP_HIDE'						=> $this->auth->acl_get('u_usermap_hide') ? true : false,
			'UCP_USERMAP_COORDINATES_EXPLAIN'		=> $this->user->lang('UCP_USERMAP_COORDINATES_EXPLAIN', $this->helper->route('tas2580_usermap_index', array())),
		));
	}
}
