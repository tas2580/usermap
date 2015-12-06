<?php
/**
*
* @package phpBB Extension - tas2580 Social Media Buttons
* @copyright (c) 2014 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\controller;

use Symfony\Component\HttpFoundation\Response;

class main
{
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\db\driver\driver */
	protected $db;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\path_helper */
	protected $path_helper;
	/** @var string */
	protected $phpbb_extension_manager;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var string phpbb_root_path */
	protected $phpbb_root_path;
	/** @var string php_ext */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth			$auth		Auth object
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\path_helper $path_helper, \phpbb\request\request $request, $phpbb_extension_manager, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->path_helper = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->user->add_lang_ext('tas2580/usermap', 'controller');
	}

	public function index()
	{
		if (!$this->auth->acl_get('u_usermap_view'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'	=> $this->helper->route('tas2580_usermap_index', array()),
		));

		$sql = 'SELECT group_id, group_usermap_marker
			FROM ' . GROUPS_TABLE . "
			WHERE group_usermap_marker != ''";
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('group_list', array(
				'GROUP_ID'	=> $row['group_id'],
				'MARKER'		=> $row['group_usermap_marker'],
			));
		}

		include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);

		$my_lon = $this->user->data['user_usermap_lon'];
		$my_lat = $this->user->data['user_usermap_lat'];

		$sql = 'SELECT user_id, username, user_colour, group_id, user_usermap_lon, user_usermap_lat
			FROM ' . USERS_TABLE . "
			WHERE user_usermap_lon != ''
				AND user_usermap_lat != ''";
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$distance = 0;
			if(!empty($my_lon) && !empty($my_lat))
			{
				$x1 = $my_lon;
				$y1 = $my_lat;
				$x2 = $row['user_usermap_lon'];
				$y2 = $row['user_usermap_lat'];
				// e = ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
				$distance = acos(sin($x1=deg2rad($x1))*sin($x2=deg2rad($x2))+cos($x1)*cos($x2)*cos(deg2rad($y2) - deg2rad($y1)))*(6378.137);
			}

			$this->template->assign_block_vars('user_list', array(
				'USER_ID'			=> $row['user_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'LON'				=> $row['user_usermap_lon'],
				'LAT'				=> $row['user_usermap_lat'],
				'GROUP_ID'		=> $row['group_id'],
				'DISTANCE'		=> ($distance <> 0) ? round($distance, 2) : '',
			));
		}

		$this->template->assign_vars(array(
			'USERMAP_CONTROLS'	=> 'true',
			'S_IN_USERMAP'		=> true,
			'USERMAP_LON'		=> empty($this->config['tas2580_usermap_lon']) ? 0 : $this->config['tas2580_usermap_lon'],
			'USERMAP_LAT'			=> empty($this->config['tas2580_usermap_lat']) ? 0 : $this->config['tas2580_usermap_lat'],
			'USERMAP_ZOOM'		=> (int) $this->config['tas2580_usermap_zoom'],
			'MARKER_PATH'		=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker'),
			'A_USERMAP_ADD'		=> $this->auth->acl_get('u_usermap_add'),
			'U_SET_POSITON'		=> $this->helper->route('tas2580_usermap_position', array()),
			'MAP_TYPE'			=> $this->config['tas2580_usermap_map_type'],
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
			'A_USERMAP_SEARCH'	=> true,
			'U_USERMAP_SEARCH'	=> $this->helper->route('tas2580_usermap_search', array()),
		));
		return $this->helper->render('usermap_body.html', $this->user->lang('USERMAP_TITLE'));
	}

	public function search()
	{

		if (!$this->auth->acl_get('u_usermap_search'))
		{
		//	trigger_error('NOT_AUTHORISED');
		}

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'	=> $this->helper->route('tas2580_usermap_index', array()),
		));

		$lon = substr($this->request->variable('lon', ''), 0, 10);
		$lat = substr($this->request->variable('lat', ''), 0, 10);

		$entf = 40; // max. Km-Entfernung von Startort

		$alpha = 180*$entf/(6378137/1000*3.14159);

		$min_lon = $lon-$alpha;
		$max_lon = $lon+$alpha;
		$min_lat = $lat-$alpha;
		$max_lat = $lat+$alpha;

		$sql = 'SELECT user_id, username, user_colour, user_regdate, user_posts, group_id, user_usermap_lon, user_usermap_lat
			FROM ' . USERS_TABLE . "
			WHERE ( user_usermap_lon >= '$min_lon' AND user_usermap_lon <= '$max_lon')
				AND ( user_usermap_lat >= '$min_lat' AND user_usermap_lat<= '$max_lat')
				";
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$x1 = $lon;
			$y1 = $lat;
			$x2 = $row['user_usermap_lon'];
			$y2 = $row['user_usermap_lat'];
			// e = ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
			$distance = acos(sin($x1=deg2rad($x1))*sin($x2=deg2rad($x2))+cos($x1)*cos($x2)*cos(deg2rad($y2) - deg2rad($y1)))*(6378.137);

			$this->template->assign_block_vars('memberrow', array(
				'USER_ID'			=> $row['user_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'JOINED'				=> $this->user->format_date($row['user_regdate']),
				'POSTS'				=> $row['user_posts'],
				'GROUP_ID'		=> $row['group_id'],
				'DISTANCE'		=> ($distance <> 0) ? round($distance, 2) : '',
			));

		}


		return $this->helper->render('usermap_search.html', $this->user->lang('USERMAP_TITLE'));
	}


	public function position()
	{
		if (!$this->auth->acl_get('u_usermap_add'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$lon = substr($this->request->variable('lon', ''), 0, 10);
		$lat = substr($this->request->variable('lat', ''), 0, 10);
		$data = array(
			'user_usermap_lon'			=> $lon,
			'user_usermap_lat'			=> $lat,
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$error = validate_data($data, array(
			'user_usermap_lon'			=> array(
				array('string', true, 5, 10)
			),
			'user_usermap_lat'			=> array(
				array('string', true, 5, 10)
			),
		));
		$error = array_map(array($this->user, 'lang'), $error);
		if (sizeof($error))
		{
			trigger_error(implode('<br>', $error) . '<br><br><a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang('BACK_TO_USERMAP') . '</a>');
		}
		$sql = 'UPDATE ' . USERS_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $data) . '
			WHERE user_id = ' . (int) $this->user->data['user_id'] ;

		$this->db->sql_query($sql);

		trigger_error($this->user->lang('COORDINATES_SET', $lon, $lat) . '<br><br><a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang('BACK_TO_USERMAP') . '</a>');

		return new Response($this->user->lang('COORDINATES_SET', $lon, $lat));
	}
}
