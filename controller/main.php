<?php
/**
*
* @package phpBB Extension - tas2580 Social Media Buttons
* @copyright (c) 2014 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\controller;

class main extends \tas2580\usermap\includes\class_usermap
{
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\db\driver\driver */
	protected $db;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\paginationr */
	protected $paginationr;
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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\pagination $pagination, \phpbb\path_helper $path_helper, \phpbb\request\request $request, $phpbb_extension_manager, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->pagination = $pagination;
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

		$sql = 'SELECT group_id, group_name, group_usermap_marker, group_type, group_colour
			FROM ' . GROUPS_TABLE . "
			WHERE group_usermap_marker != ''
				AND group_usermap_legend <> 0
			ORDER BY group_name";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$group_name = ($row['group_type'] == GROUP_SPECIAL) ? $this->user->lang('G_' . $row['group_name']) : $row['group_name'];
			$colour_text = ($row['group_colour']) ? ' style="color:#' . $row['group_colour'] . '"' : '';
			if ($row['group_name'] == 'BOTS' || ($this->user->data['user_id'] != ANONYMOUS && !$this->auth->acl_get('u_viewprofile')))
			{
				$legend = '<span' . $colour_text . '>' . $group_name . '</span>';
			}
			else
			{
				$legend = '<a' . $colour_text . ' href="' . append_sid("{$this->phpbb_root_path}memberlist.{$this->php_ext}", 'mode=group&amp;g=' . $row['group_id']) . '">' . $group_name . '</a>';
			}
			$this->template->assign_block_vars('group_list', array(
				'GROUP_ID'		=> $row['group_id'],
				'GROUP_NAME'		=> $legend,
				'ALT'				=> $group_name,
				'MARKER'			=> $row['group_usermap_marker'],
			));
		}

		$this->template->assign_vars(array(
			'USERMAP_CONTROLS'	=> 'true',
			'S_IN_USERMAP'		=> true,
			'USERMAP_LON'		=> empty($this->config['tas2580_usermap_lon']) ? 0 : $this->config['tas2580_usermap_lon'],
			'USERMAP_LAT'			=> empty($this->config['tas2580_usermap_lat']) ? 0 : $this->config['tas2580_usermap_lat'],
			'USERMAP_ZOOM'		=> (int) $this->config['tas2580_usermap_zoom'],
			'MARKER_PATH'		=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker'),
			'A_USERMAP_ADD'		=> (($this->user->data['user_id'] <> ANONYMOUS) && $this->auth->acl_get('u_usermap_add')),
			'A_USERMAP_SEARCH'	=> $this->auth->acl_get('u_usermap_search'),
			'S_CAN_ADD'			=> (empty($this->user->data['user_usermap_lon']) || empty($this->user->data['user_usermap_lat'])),
			'U_SET_POSITON'		=> $this->helper->route('tas2580_usermap_position', array()),
			'U_GET_MARKER'		=> $this->helper->route('tas2580_usermap_get_marker', array()),
			'MAP_TYPE'			=> $this->config['tas2580_usermap_map_type'],
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
			'U_USERMAP_SEARCH'	=> $this->helper->route('tas2580_usermap_search', array()),
			'L_MENU_SEARCH'		=> $this->user->lang('MENU_SEARCH', $this->config['tas2580_usermap_search_distance'])
		));
		return $this->helper->render('usermap_body.html', $this->user->lang('USERMAP_TITLE'));
	}


	public function marker()
	{
		$data = array(
			'min_lon'		=> (float) substr($this->request->variable('alon', ''), 0, 10),
			'max_lat'		=> (float) substr($this->request->variable('alat', ''), 0, 10),
			'max_lon'		=> (float) substr($this->request->variable('blon', ''), 0, 10),
			'min_lat'		=> (float) substr($this->request->variable('blat', ''), 0, 10),
		);

		$validate_array = array(
			'min_lon'		=> array('match', false, '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
			'max_lat'		=> array('match', false, '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
			'max_lon'		=> array('match', false, '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
			'min_lat'		=> array('match', false, '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}

		$error = validate_data($data, $validate_array);
		if(sizeof($error))
		{
			$error = array_map(array($this->user, 'lang'), $error);
			$json_response = new \phpbb\json_response;
			$json_response->send($error);
		}

		$return = array();
		$sql_array['FROM'][USERS_TABLE] = 'u';
		$sql_array['SELECT'] .= 'u.user_id, u.username, u.user_colour, u.user_regdate, u.user_posts, u.group_id, u.user_usermap_lon, u.user_usermap_lat, g.group_usermap_marker';
		$sql_array['LEFT_JOIN'][] = array(
			'FROM'	=> array(GROUPS_TABLE => 'g'),
			'ON'		=> 'u.group_id = g.group_id'
		);
		$sql_array['WHERE'] = "(u.user_usermap_lon >= {$data['min_lon']} AND u.user_usermap_lon <= {$data['max_lon']}) AND (u.user_usermap_lat >= {$data['min_lat']} AND u.user_usermap_lat<= {$data['max_lat']}) AND user_usermap_hide = 0";
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['tas2580_usermap_max_marker']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $row['user_usermap_lon'], $row['user_usermap_lat']);
			$return[] = array(
				'marker'		=> $row['group_usermap_marker'],
				'lon'			=> $row['user_usermap_lon'],
				'lat'			=> $row['user_usermap_lat'],
				'title'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'distance'		=> $distance,
			);
		}

		$json_response = new \phpbb\json_response;
		$json_response->send($return);
	}

	public function search($start = 1)
	{
		if (!$this->auth->acl_get('u_usermap_search'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'	=> $this->helper->route('tas2580_usermap_index', array()),
		));

		$data = array(
			'lon'	=> substr($this->request->variable('lon', ''), 0, 10),
			'lat'	=> substr($this->request->variable('lat', ''), 0, 10),
			'dst'	=> (int) $this->request->variable('dst', $this->config['tas2580_usermap_search_distance']),
		);

		$validate_array = array(
			'lon'		=> array('match', false, '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
			'lat'		=> array('match', false, '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$error = validate_data($data, $validate_array);

		$alpha = 180 * $data['dst'] / (6378137 / 1000 * 3.14159);
		$min_lon = (float) ($data['lon'] - $alpha);
		$max_lon = (float) ($data['lon'] + $alpha);
		$min_lat = (float) ($data['lat'] - $alpha);
		$max_lat = (float) ($data['lat'] + $alpha);

		$where = " WHERE ( user_usermap_lon >= $min_lon AND user_usermap_lon <= $max_lon) AND ( user_usermap_lat >= $min_lat AND user_usermap_lat<= $max_lat)";
		$limit = (int) $this->config['topics_per_page'];

		$sql = 'SELECT COUNT(user_id) AS num_users
			FROM ' . USERS_TABLE . $where;
		$result = $this->db->sql_query($sql);
		$total_users = (int) $this->db->sql_fetchfield('num_users');
		$this->db->sql_freeresult($result);

		$sql = 'SELECT user_id, username, user_colour, user_regdate, user_posts, group_id, user_usermap_lon, user_usermap_lat
			FROM ' . USERS_TABLE . $where;
		$result = $this->db->sql_query_limit($sql, $limit, ($start -1)  * $limit);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$distance = $this->get_distance($data['lon'], $data['lat'], $row['user_usermap_lon'], $row['user_usermap_lat']);
			$this->template->assign_block_vars('memberrow', array(
				'USER_ID'			=> $row['user_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'JOINED'			=> $this->user->format_date($row['user_regdate']),
				'POSTS'			=> $row['user_posts'],
				'GROUP_ID'		=> $row['group_id'],
				'DISTANCE'		=> $distance,
			));
		}

		$this->pagination->generate_template_pagination(array(
			'routes' => array(
				'tas2580_usermap_search',
				'tas2580_usermap_search_page',
			),
			'params' => array(
			),
		), 'pagination', 'start', $total_users, $limit, ($start - 1)  * $limit);

		$error = array_map(array($this->user, 'lang'), $error);
		$this->template->assign_vars(array(
			'ERROR'				=> (sizeof($error)) ? implode('<br />', $error) : '',
			'TOTAL_USERS'			=> $this->user->lang('TOTAL_USERS', (int) $total_users),
			'L_SEARCH_EXPLAIN'		=> $this->user->lang('SEARCH_EXPLAIN', $data['dst'], $data['lon'], $data['lat']),
		));

		return $this->helper->render('usermap_search.html', $this->user->lang('USERMAP_SEARCH'));
	}


	public function position()
	{
		if (($this->user->data['user_id'] == ANONYMOUS) || !$this->auth->acl_get('u_usermap_add'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$lon = substr($this->request->variable('lon', ''), 0, 10);
		$lat = substr($this->request->variable('lat', ''), 0, 10);

		if (confirm_box(true))
		{
			$data = array(
				'user_usermap_lon'			=> $lon,
				'user_usermap_lat'			=> $lat,
			);

			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$error = validate_data($data, array(
				'user_usermap_lon'		=> array('match', false, '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
				'user_usermap_lat'		=> array('match', false, '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#'),
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
			trigger_error('POSITION_SET');
		}
		else
		{
			confirm_box(false, $this->user->lang('CONFIRM_COORDINATES_SET', $lon, $lat), build_hidden_fields(array(
				'lon'		=> $lon,
				'lat'		=> $lat))
			);
		}
		return $this->index();
	}
}
