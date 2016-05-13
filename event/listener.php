<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace tas2580\usermap\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener extends \tas2580\usermap\includes\class_usermap implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header'							=> 'page_header',
			'core.permissions'							=> 'permissions',
			'core.memberlist_view_profile'				=> 'memberlist_view_profile',
			'core.viewtopic_cache_user_data'			=> 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_modify_post_row',
			'core.ucp_register_data_before'				=> 'ucp_register_data_before',
			'core.ucp_register_data_after'				=> 'ucp_register_data_after',
			'core.ucp_register_user_row_after'			=> 'ucp_register_user_row_after',
		);
	}

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb_extension_manager */
	protected $phpbb_extension_manager;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	private $info;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth						$auth
	 * @param \phpbb\config\config					$config
	 * @param \phpbb\db\driver\driver_interface		$db
	 * @param \phpbb\file_downloader				$file_downloader
	 * @param \phpbb\controller\helper				$helper							Controller helper object
	 * @param \phpbb\path_helper					$path_helper
	 * @param \phpbb_extension_manager				$phpbb_extension_manager		Controller helper object
	 * @param \phpbb\request\request				$request						Request object
	 * @param \phpbb\template						$template						Template object
	 * @param \phpbb\user							$user							User object
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\path_helper $path_helper, $phpbb_extension_manager, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->path_helper = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;

		$this->info = array();
	}

	/**
	* Add permissions
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions += array(
			'u_usermap_view'	=> array(
				'lang'		=> 'ACL_U_USERMAP_VIEW',
				'cat'		=> 'usermap'
			),
			'u_usermap_add'	=> array(
				'lang'		=> 'ACL_U_USERMAP_ADD',
				'cat'		=> 'usermap'
			),
			'u_usermap_search'	=> array(
				'lang'		=> 'ACL_U_USERMAP_SEARCH',
				'cat'		=> 'usermap'
			),
			'u_usermap_hide'	=> array(
				'lang'		=> 'ACL_U_USERMAP_HIDE',
				'cat'		=> 'usermap'
			),
			'u_usermap_add_thing'	=> array(
				'lang'		=> 'ACL_U_USERMAP_ADD_THING',
				'cat'		=> 'usermap'
			),
			'u_usermap_edit_thing'	=> array(
				'lang'		=> 'ACL_U_USERMAP_EDIT_THING',
				'cat'		=> 'usermap'
			),
			'u_usermap_delete_thing'	=> array(
				'lang'		=> 'ACL_U_USERMAP_DELETE_THING',
				'cat'		=> 'usermap'
			),
		);
		$categories['usermap'] = 'ACL_CAT_USERMAP';
		$event['categories'] = array_merge($event['categories'], $categories);
		$event['permissions'] = $permissions;
	}

	/**
	* Add link to header
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function page_header($event)
	{
		if ($this->auth->acl_get('u_usermap_view'))
		{
			$this->user->add_lang_ext('tas2580/usermap', 'link');
			$this->template->assign_vars(array(
				'U_USERMAP'	=> $this->helper->route('tas2580_usermap_index', array()),
			));
		}
	}

	/**
	 * Display input fields on register
	 *
	 * @param	object	$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function ucp_register_data_before($event)
	{
		if (!$this->config['tas2580_usermap_show_on_register'])
		{
			return;
		}

		$this->user->add_lang_ext('tas2580/usermap', 'country_codes');
		$this->user->add_lang_ext('tas2580/usermap', 'ucp');
		$this->template->assign_vars(array(
			'COUNTRY_SELECT'	=> $this->country_code_select($this->config['tas2580_usermap_default_country']),
			'S_USERMAP_ZIP'		=> ($this->config['tas2580_usermap_input_method'] == 'zip') ? true : false,
			'S_USERMAP_CORDS'	=> ($this->config['tas2580_usermap_input_method'] == 'cord') ? true : false,
		));
	}

	public function ucp_register_data_after($event)
	{
		if (!$this->config['tas2580_usermap_show_on_register'])
		{
			return;
		}

		$error = $event['error'];

		if ($this->config['tas2580_usermap_input_method'] == 'zip')
		{
			$default_country = $this->request->variable('default_country', '');
			$zip = $this->request->variable('usermap_zip', '');
			$this->info = $this->get_cords_form_zip($zip, $default_country, $error);
			$this->info['zip'] = $zip;
			$this->info['default_country'] = $default_country;
		}
		else
		{
			$this->info['lon'] = substr($this->request->variable('usermap_lon', ''), 0, 10);
			$this->info['lat'] = substr($this->request->variable('usermap_lat', ''), 0, 10);
			$this->info['zip'] = '';
			$this->info['default_country'] = '';

			$validate_array = array(
				'lon'		=> array('match', true, self::REGEX_LON),
				'lat'		=> array('match', true, self::REGEX_LAT),
			);
			$error = array_merge($error, validate_data($this->info, $validate_array));
		}

		if ($this->config['tas2580_usermap_force_on_register'] && empty($this->info['lng']) && empty($this->info['lat']))
		{
			$this->user->add_lang_ext('tas2580/usermap', 'ucp');
			$error[] = $this->user->lang('NEED_REGISTER_' . strtoupper($this->config['tas2580_usermap_input_method']));
		}

		if (sizeof($error))
		{
			$event['error'] = $error;
		}
	}


	public function ucp_register_user_row_after($event)
	{
		if (!$this->config['tas2580_usermap_show_on_register'])
		{
			return;
		}

		$user_row['user_usermap_lon'] = $this->info['lon'];
		$user_row['user_usermap_lat'] = $this->info['lat'];
		$user_row['user_usermap_zip'] = $this->info['zip'];
		$user_row['user_usermap_default_country'] = $this->info['default_country'];

		$event['user_row'] = array_merge($event['user_row'], $user_row);
	}

	/**
	* Add map to users profile
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function memberlist_view_profile($event)
	{
		if ($this->config['tas2580_usermap_map_in_viewprofile'] == 0)
		{
			return false;
		}

		$data = $event['member'];
		if (empty($data['user_usermap_lon']))
		{
			return false;
		}

		if ($this->user->data['user_usermap_lon'] && ($this->user->data['user_id'] <> $data['user_id']))
		{
			$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $data['user_usermap_lon'], $data['user_usermap_lat']);
		}

		$this->user->add_lang_ext('tas2580/usermap', 'controller');

		// Center the map to user
		$this->template->assign_vars(array(
			'S_IN_USERMAP'		=> !empty($data['user_usermap_lon']) ? true : false,
			'USERMAP_CONTROLS'	=> 'false',
			'USERNAME'			=> get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']),
			'USERMAP_LON'		=> $data['user_usermap_lon'],
			'USERMAP_LAT'		=> $data['user_usermap_lat'],
			'USERMAP_ZOOM'		=> (int) 10,
			'DISTANCE'			=> isset($distance) ? $distance : '',
			'MARKER_PATH'		=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/groups'),
			'GOOGLE_API_KEY'	=> $this->config['tas2580_usermap_google_api_key'],
			'BING_API_KEY'		=> $this->config['tas2580_usermap_bing_api_key'],
			'DEFAULT_MAP'		=> $this->config['tas2580_usermap_map_type'],
		));

		$sql = 'SELECT group_id, group_usermap_marker
			FROM ' . GROUPS_TABLE . '
			WHERE group_id = ' . (int) $data['group_id'];
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->template->assign_vars(array(
			'USERMAP_MARKER'		=> $row['group_usermap_marker'],
		));
	}

	/**
	* Add distance to viewtopic
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function viewtopic_cache_user_data($event)
	{
		if (!$this->config['tas2580_usermap_distance_in_viewtopic'])
		{
			return false;
		}

		$data = $event['row'];
		// not on own profile
		if ($data['user_id'] == $this->user->data['user_id'])
		{
			return false;
		}

		$this->user->add_lang_ext('tas2580/usermap', 'controller');
		$user_cache_data = $event['user_cache_data'];
		$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $data['user_usermap_lon'], $data['user_usermap_lat']);

		$user_cache_data['distance'] = $distance;
		$event['user_cache_data'] = $user_cache_data;
	}

	/**
	* Add distance to viewtopic
	*
	* @param	object	$event	The event object
	* @return	null
	* @access	public
	*/
	public function viewtopic_modify_post_row($event)
	{
		if (!$this->config['tas2580_usermap_distance_in_viewtopic'])
		{
			return false;
		}

		// not on own profile
		if ($event['poster_id'] == $this->user->data['user_id'])
		{
			return false;
		}

		$post_row = $event['post_row'];
		$post_row['DISTANCE'] = isset($event['user_poster_data']['distance']) ? $event['user_poster_data']['distance'] : '';
		$event['post_row'] =$post_row;
	}
}
