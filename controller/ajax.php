<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\controller;

class ajax extends \tas2580\usermap\includes\class_usermap
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

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\user $user, $phpbb_root_path, $php_ext, $things_table, $place_type_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->things_table = $things_table;
		$this->place_type_table = $place_type_table;

		$this->user->add_lang_ext('tas2580/usermap', 'controller');
	}

	/**
	 * Get the markers
	 */
	public function marker()
	{
		if (!$this->auth->acl_get('u_usermap_view'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$data = array(
			'min_lon'		=> (float) substr($this->request->variable('alon', ''), 0, 10),
			'max_lat'		=> (float) substr($this->request->variable('alat', ''), 0, 10),
			'max_lon'		=> (float) substr($this->request->variable('blon', ''), 0, 10),
			'min_lat'		=> (float) substr($this->request->variable('blat', ''), 0, 10),
		);

		$validate_array = array(
			'min_lon'		=> array('match', false, self::REGEX_LON),
			'max_lat'		=> array('match', false, self::REGEX_LAT),
			'max_lon'		=> array('match', false, self::REGEX_LON),
			'min_lat'		=> array('match', false, self::REGEX_LAT),
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}

		$error = validate_data($data, $validate_array);
		if (sizeof($error))
		{
			$error = array_map(array($this->user, 'lang'), $error);
			$json_response = new \phpbb\json_response;
			$json_response->send($error);
		}

		$return = array();

		$sql_array['FROM'][$this->things_table] = 't';
		$sql_array['SELECT'] = 't.*, pt.*';
		$sql_array['LEFT_JOIN'][] = array(
			'FROM'		=> array($this->place_type_table => 'pt'),
			'ON'		=> 'pt.place_type_id = t.place_type_id'
		);
		$sql_array['WHERE'] = "(thing_lon * 1 >= {$data['min_lon']} AND thing_lon * 1 <= {$data['max_lon']}) AND (thing_lat * 1 >= {$data['min_lat']} AND thing_lat * 1 <= {$data['max_lat']})";
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['tas2580_usermap_max_marker']);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$text = '<a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $row['thing_id'])) . '">' . $row['thing_title'] . '</a>';
			if (!empty($this->user->data['user_usermap_lon']))
			{
				$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $row['thing_lon'], $row['thing_lat']);
				$text .= '<br>' . $this->user->lang('DISTANCE'). $this->user->lang('COLON') . ' ' . $distance;
			}

			if ($this->config['tas2580_usermap_display_coordinates'])
			{
				$text .= '<br>' . $this->user->lang('LON'). $this->user->lang('COLON') . ' ' . $row['thing_lon'];
				$text .= '<br>' . $this->user->lang('LAT'). $this->user->lang('COLON') . ' ' . $row['thing_lat'];
			}

			$return_data = array(
				'marker'		=> 'things/' . $row['place_type_marker'],
				'lon'			=> $row['thing_lon'],
				'lat'			=> $row['thing_lat'],
				'text'			=> $text,
				'id'			=> 'p' . $row['place_type_id'],
			);
			$return[] = $return_data;
		}
		unset($sql_array);

		$sql_array['FROM'][USERS_TABLE] = 'u';
		$sql_array['SELECT'] = 'u.user_id, u.username, u.user_colour, u.user_regdate, u.user_posts, u.group_id, u.user_usermap_lon, u.user_usermap_lat, g.group_usermap_marker';
		$sql_array['LEFT_JOIN'][] = array(
			'FROM'		=> array(GROUPS_TABLE => 'g'),
			'ON'		=> 'u.group_id = g.group_id'
		);
		$sql_array['WHERE'] = "(u.user_usermap_lon * 1 >= {$data['min_lon']} AND u.user_usermap_lon * 1 <= {$data['max_lon']}) AND (u.user_usermap_lat * 1 >= {$data['min_lat']} AND u.user_usermap_lat * 1 <= {$data['max_lat']}) AND user_usermap_hide = 0";

		/**
		 * Modify SQL array for user marker
		 *
		 * @event tas2580.usermap_modify_user_sql_array
		 * @var    array    sql_array		SQL array
		 * @since 0.1.4
		 */
		$vars = array('sql_array');
		extract($this->phpbb_dispatcher->trigger_event('tas2580.usermap_modify_user_sql_array', compact($vars)));

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['tas2580_usermap_max_marker']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$text = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);

			if (!empty($this->user->data['user_usermap_lon']) && $row['user_id'] <> $this->user->data['user_id'])
			{
				$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $row['user_usermap_lon'], $row['user_usermap_lat']);
				$text .= '<br>' . $this->user->lang('DISTANCE'). $this->user->lang('COLON') . ' ' . $distance;
			}
			if ($this->config['tas2580_usermap_display_coordinates'])
			{
				$text .= '<br>' . $this->user->lang('LON'). $this->user->lang('COLON') . ' ' . $row['user_usermap_lon'];
				$text .= '<br>' . $this->user->lang('LAT'). $this->user->lang('COLON') . ' ' . $row['user_usermap_lat'];
			}

			$return_data = array(
				'marker'		=> 'groups/' . $row['group_usermap_marker'],
				'lon'			=> $row['user_usermap_lon'],
				'lat'			=> $row['user_usermap_lat'],
				'text'			=> $text,
				'id'			=> 'u' . $row['user_id'],
			);

			/**
			 * Modify data for user marker
			 *
			 * @event tas2580.usermap_user_marker_row_after
			 * @var    array    row				User row
			 * @var    array    return_data		Return data
			 * @since 0.1.4
			 */
			$vars = array('row', 'return_data');
			extract($this->phpbb_dispatcher->trigger_event('tas2580.usermap_user_marker_row_after', compact($vars)));

			$return[] = $return_data;
		}

		$json_response = new \phpbb\json_response;
		$json_response->send($return);
	}


	public function distance()
	{
		$data = array(
			'lon'		=> substr($this->request->variable('lon', ''), 0, 8),
			'lat'		=> substr($this->request->variable('lat', ''), 0, 8),
		);

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}

		$error = validate_data($data, array(
			'lon'		=> array('match', false, self::REGEX_LON),
			'lat'		=> array('match', false, self::REGEX_LAT),
		));

		if (sizeof($error))
		{
			$error = array_map(array($this->user, 'lang'), $error);
			trigger_error(implode('<br>', $error));
		}

		$distance = $this->get_distance($data['lon'], $data['lat'], $this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat']);

		trigger_error($this->user->lang('DISTANCE_IS', $data['lat'], $data['lon'], $distance));
		return $this->index();
	}

	/**
	 * Set own position on map
	 *
	 * @return type
	 */
	public function position()
	{
		if (($this->user->data['user_id'] == ANONYMOUS) || !$this->auth->acl_get('u_usermap_add'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$data = array(
			'user_usermap_lon'		=> substr($this->request->variable('lon', ''), 0, 10),
			'user_usermap_lat'		=> substr($this->request->variable('lat', ''), 0, 10),
		);

		if (confirm_box(true))
		{
			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}

			$error = validate_data($data, array(
				'user_usermap_lon'		=> array('match', false, self::REGEX_LON),
				'user_usermap_lat'		=> array('match', false, self::REGEX_LAT),
			));

			if (sizeof($error))
			{
				$error = array_map(array($this->user, 'lang'), $error);
				trigger_error(implode('<br>', $error));
			}

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $data) . '
				WHERE user_id = ' . (int) $this->user->data['user_id'] ;
			$this->db->sql_query($sql);
			trigger_error('POSITION_SET');
		}
		else
		{
			confirm_box(false, $this->user->lang('CONFIRM_COORDINATES_SET', $data['user_usermap_lon'], $data['user_usermap_lat']), build_hidden_fields(array(
				'lon'		=> $data['user_usermap_lon'],
				'lat'		=> $data['user_usermap_lat']))
			);
		}
		return $this->index();
	}
}
