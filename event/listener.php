<?php
/**
*
* @package phpBB Extension - Wiki
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
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
			'core.page_header'						=> 'page_header',
			'core.permissions'						=> 'permissions',
			'core.memberlist_view_profile'				=> 'memberlist_view_profile',
			'core.viewtopic_cache_user_data'			=> 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_modify_post_row',
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

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth				$auth
	* @param \phpbb\config\config			$config
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\controller\helper			$helper						Controller helper object
	* @param \phpbb\path_helper				$path_helper
	* @param \phpbb_extension_manager		$phpbb_extension_manager		Controller helper object
	* @param \phpbb\template				$template						Template object
	* @param \phpbb\user					$user						User object
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\path_helper $path_helper, $phpbb_extension_manager, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->path_helper = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->template = $template;
		$this->user = $user;
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
				'cat'		=> 'profile'
			),
			'u_usermap_add'	=> array(
				'lang'		=> 'ACL_U_USERMAP_ADD',
				'cat'		=> 'profile'
			),
			'u_usermap_search'	=> array(
				'lang'		=> 'ACL_U_USERMAP_SEARCH',
				'cat'		=> 'profile'
			),
			'u_usermap_hide'	=> array(
				'lang'		=> 'ACL_U_USERMAP_HIDE',
				'cat'		=> 'profile'
			),
		);
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
		$this->user->add_lang_ext('tas2580/usermap', 'controller');
		$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $data['user_usermap_lon'], $data['user_usermap_lat']);

		// Center the map to user
		$this->template->assign_vars(array(
			'S_IN_USERMAP'		=> !empty($data['user_usermap_lon']) ? true : false,
			'USERMAP_CONTROLS'	=> 'false',
			'USERNAME'			=> get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']),
			'USERMAP_LON'		=> $data['user_usermap_lon'],
			'USERMAP_LAT'			=> $data['user_usermap_lat'],
			'USERMAP_ZOOM'		=> (int) 10,
			'DISTANCE'			=> $distance,
			'MARKER_PATH'		=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker'),
			'MAP_TYPE'			=> $this->config['tas2580_usermap_map_type'],
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
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
