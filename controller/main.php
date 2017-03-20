<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
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

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb_extension_manager */
	protected $phpbb_extension_manager;

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
	* @param \phpbb\auth\auth					$auth							Auth object
	* @param \phpbb\config\config				$config							Config object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\controller\helper			$helper
	* @param \phpbb\pagination					$pagination
	* @param \phpbb\path_helper					$path_helper
	* @param \phpbb\request\request				$request
	* @param \phpbb_extension_manager			$phpbb_extension_manager
	* @param \phpbb\user						$user							User Object
	* @param \phpbb\template\template			$template
	* @param string								$phpbb_root_path				phpbb_root_path
	* @param string								$php_ext						php_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\controller\helper $helper, \phpbb\pagination $pagination, \phpbb\path_helper $path_helper, \phpbb\request\request $request, $phpbb_extension_manager, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext, $places_table, $place_type_table, $maps_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->helper = $helper;
		$this->pagination = $pagination;
		$this->path_helper = $path_helper;
		$this->request = $request;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->places_table = $places_table;
		$this->place_type_table = $place_type_table;
		$this->maps_table = $maps_table;

		$this->user->add_lang_ext('tas2580/usermap', 'controller');

		$translation_info = (!empty($this->user->lang['TRANSLATION_INFO'])) ? $this->user->lang['TRANSLATION_INFO'] : '';
		$this->user->lang['TRANSLATION_INFO'] = $translation_info . '<br>Usermap Extension &copy; by <a href="https://tas2580.net">tas2580</a>';
	}

	/**
	 * Display the map
	 *
	 * @return type
	 */
	public function index()
	{
		if (!$this->auth->acl_get('u_usermap_view'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_index', array()),
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
				'GROUP_ID'			=> $row['group_id'],
				'GROUP_NAME'		=> $legend,
				'ALT'				=> $group_name,
				'MARKER'		=> 'groups/' . (empty($row['group_usermap_marker']) ? 'user.png' : $row['group_usermap_marker']);
			));
		}

		$marker_path = $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker');

		$sql = 'SELECT place_type_id, place_type_title, place_type_marker
			FROM ' . $this->place_type_table . '
			WHERE place_display_legend = 1
			ORDER BY place_type_title';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('place_types', array(
				'TITLE'			=> $row['place_type_title'],
				'MARKER'		=> $marker_path . '/things/' . $row['place_type_marker'],
				'U_MARKER'		=> $this->helper->route('tas2580_usermap_placelist', array('id' => $row['place_type_id'])),
			));
		}
		$sql = 'SELECT COUNT(place_type_id) AS num_places
			FROM ' . $this->places_table;
		$result = $this->db->sql_query($sql);
		$total_places = (int) $this->db->sql_fetchfield('num_places');
		$this->db->sql_freeresult($result);

		$sql = 'SELECT COUNT(user_id) AS num_user
			FROM ' . USERS_TABLE . '
				WHERE user_usermap_lon <> 0
				AND user_usermap_lat <> 0';
		$result = $this->db->sql_query($sql);
		$total_users = (int) $this->db->sql_fetchfield('num_user');
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'USERMAP_CONTROLS'		=> 'true',
			'S_IN_USERMAP'			=> true,
			'USERMAP_LON'			=> empty($this->config['tas2580_usermap_lon']) ? 0 : $this->config['tas2580_usermap_lon'],
			'USERMAP_LAT'			=> empty($this->config['tas2580_usermap_lat']) ? 0 : $this->config['tas2580_usermap_lat'],
			'USERMAP_ZOOM'			=> (int) $this->config['tas2580_usermap_zoom'],
			'MARKER_PATH'			=> $marker_path,
			'A_USERMAP_ADD'			=> (($this->user->data['user_id'] <> ANONYMOUS) && $this->auth->acl_get('u_usermap_add')),
			'A_ADD_PLACE'			=> $this->auth->acl_get('u_usermap_add_thing'),
			'A_USERMAP_SEARCH'		=> $this->auth->acl_get('u_usermap_search'),
			'S_CAN_ADD'				=> (empty($this->user->data['user_usermap_lon']) || empty($this->user->data['user_usermap_lat'])),
			'U_SET_POSITON'			=> $this->helper->route('tas2580_usermap_position'),
			'U_ADD_PLACE'			=> $this->helper->route('tas2580_usermap_add_place'),
			'U_GET_MARKER'			=> $this->helper->route('tas2580_usermap_get_marker'),
			'U_GET_DISTANCE'		=> $this->helper->route('tas2580_usermap_get_distance'),
			'MAP_TYPE'				=> $this->config['tas2580_usermap_map_type'],
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
			'BING_API_KEY'			=> $this->config['tas2580_usermap_bing_api_key'],
			'DEFAULT_MAP'			=> $this->config['tas2580_usermap_map_type'],
			'U_USERMAP_SEARCH'		=> $this->helper->route('tas2580_usermap_search'),
			'L_MENU_SEARCH'			=> $this->user->lang('MENU_SEARCH', $this->config['tas2580_usermap_search_distance']),
			'TOTAL_USER'			=> $total_users,
			'TOTAL_PLACES'			=> $total_places,
		));

		$sql = 'SELECT *
			FROM ' . $this->maps_table . '
				WHERE map_active = 1
			ORDER BY map_display_name';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('mapsrow', array(
				'NAME'			=> $row['map_name'],
				'DISPLAY_NAME'	=> $row['map_display_name'],
				'DEFAULT'		=> (int) $row['map_default'],
			));
		}

		return $this->helper->render('usermap_body.html', $this->user->lang('USERMAP_TITLE'));
	}

	/**
	 * Display the search page
	 *
	 * @param type $start
	 * @return type
	 */
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
			'lon'		=> array('match', false, self::REGEX_LON),
			'lat'		=> array('match', false, self::REGEX_LAT),
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$error = validate_data($data, $validate_array);

		if (sizeof($error))
		{
			$error = array_map(array($this->user, 'lang'), $error);
			trigger_error(implode('<br>', $error) . '<br><br><a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang('BACK_TO_USERMAP') . '</a>');
		}

		$alpha = 180 * $data['dst'] / (6378137 / 1000 * 3.14159);
		$min_lon = (float) ($data['lon'] - $alpha);
		$max_lon = (float) ($data['lon'] + $alpha);
		$min_lat = (float) ($data['lat'] - $alpha);
		$max_lat = (float) ($data['lat'] + $alpha);

		$where = " WHERE ( user_usermap_lon * 1 >= $min_lon AND user_usermap_lon * 1 <= $max_lon) AND ( user_usermap_lat * 1 >= $min_lat AND user_usermap_lat * 1 <= $max_lat)";
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
				'USER_ID'		=> $row['user_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'JOINED'		=> $this->user->format_date($row['user_regdate']),
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

		$this->template->assign_vars(array(
			'TOTAL_USERS'			=> $this->user->lang('TOTAL_USERS', (int) $total_users),
			'L_SEARCH_EXPLAIN'		=> $this->user->lang('SEARCH_EXPLAIN', $data['dst'], $data['lon'], $data['lat']),
		));

		return $this->helper->render('usermap_search.html', $this->user->lang('USERMAP_SEARCH'));
	}
}
