<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\controller;

class thing extends \tas2580\usermap\includes\class_usermap
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
	* @param \phpbb\auth\auth				$auth						Auth object
	* @param \phpbb\config\config			$config						Config object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\controller\helper			$helper
	* @param \phpbb\pagination				$pagination
	* @param \phpbb\path_helper				$path_helper
	* @param \phpbb\request\request			$request
	* @param \phpbb_extension_manager		$phpbb_extension_manager
	* @param \phpbb\user					$user						User Object
	* @param \phpbb\template\template		$template
	* @param string						$phpbb_root_path				phpbb_root_path
	* @param string						$php_ext						php_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\controller\helper $helper, \phpbb\pagination $pagination, \phpbb\path_helper $path_helper, \phpbb\request\request $request, $phpbb_extension_manager, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext, $things_table, $place_type_table, $comment_table, $maps_table)
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

		$this->things_table = $things_table;
		$this->place_type_table = $place_type_table;
		$this->comment_table = $comment_table;
		$this->maps_table = $maps_table;

		$this->user->add_lang_ext('tas2580/usermap', 'controller');

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_index', array()),
		));
		$translation_info = (!empty($this->user->lang['TRANSLATION_INFO'])) ? $this->user->lang['TRANSLATION_INFO'] : '';
		$this->user->lang['TRANSLATION_INFO'] = $translation_info . '<br>Usermap Extension &copy; by <a href="https://tas2580.net">tas2580</a>';
	}


	/**
	 * Delete a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function delete_place($id)
	{
		if (!$this->auth->acl_get('m_usermap_place_delete'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		if (confirm_box(true))
		{
			$sql = 'DELETE FROM ' . $this->things_table . '
				WHERE thing_id = ' . (int) $id;
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM ' . $this->comment_table . '
				WHERE place_id = ' . (int) $id;
			$this->db->sql_query($sql);

			trigger_error($this->user->lang['DELETE_THING_SUCCESS'] . '<br /><br /><a href="' . $this->helper->route('tas2580_usermap_index', array())  . '">' . $this->user->lang['BACK_TO_USERMAP'] . '</a>');
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array(
				'id'    => $id,
			));
			confirm_box(false, $this->user->lang['CONFIRM_DELETE_THING'], $s_hidden_fields);
		}
		redirect($this->helper->route('tas2580_usermap_place', array('id' => $id)));
	}

	/**
	 * Edit a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function edit_place($id)
	{
		if (!$this->auth->acl_get('m_usermap_place_edit'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
		include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);

		$bbcode_status = $this->config['tas2580_usermap_allow_bbcode'];
		$url_status = $this->config['tas2580_usermap_allow_urls'];
		$img_status = $this->config['tas2580_usermap_allow_img'];
		$flash_status = $this->config['tas2580_usermap_allow_flash'];
		$smilies_status = $this->config['tas2580_usermap_allow_smilies'];

		$this->user->add_lang('posting');

		$path = $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things');

		$submit = $this->request->is_set_post('submit');
		if ($submit)
		{
			$title = $this->request->variable('title', '', true);
			$message = $this->request->variable('message', '', true);
			$place_type_id = $this->request->variable('marker_type', 0);

			$error = array();
			if (utf8_clean_string($title) === '')
			{
				$error[] = $this->user->lang['EMPTY_SUBJECT'];
			}

			if (utf8_clean_string($message) === '')
			{
				$error[] = $this->user->lang['TOO_FEW_CHARS'];
			}

			if (sizeof($error))
			{
				generate_smilies('inline', 0);
				display_custom_bbcodes();

				$this->template->assign_vars(array(
					'ERROR'					=> implode('<br />', $error),
					'TITLE'					=> $title,
					'MESSAGE'				=> $message,
					'MARKER'				=> $this->marker_type_select($place_type_id),
					'USERMAP_MARKER_PATH'	=> $path,
				));
			}
			else
			{
				generate_text_for_storage($message, $uid, $bitfield, $options, $bbcode_status, $url_status, $smilies_status);

				$sql_data = array(
					'thing_title'			=> $title,
					'thing_text'			=> $message,
					'bbcode_uid'			=> $uid,
					'bbcode_bitfield'		=> $bitfield,
					'place_type_id'			=> $place_type_id,
				);
				$sql = 'UPDATE ' . $this->things_table . ' SET
					' . $this->db->sql_build_array('UPDATE', $sql_data) . '
						WHERE thing_id = ' . (int) $id;
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['THING_UPDATED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $id)) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang['BACK_TO_USERMAP'] . '</a>');

			}
		}
		else
		{
			$sql = 'SELECT *
				FROM ' . $this->things_table . '
				WHERE thing_id = ' . (int) $id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$text = generate_text_for_edit($row['thing_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], 3, true);

			generate_smilies('inline', 0);
			display_custom_bbcodes();

			$this->template->assign_vars(array(
				'TITLE'							=> $row['thing_title'],
				'MESSAGE'						=> $text['text'],
				'MARKER'						=> $this->marker_type_select($row['place_type_id']),
				'USERMAP_MARKER_PATH'			=> $path,
				'S_BBCODE_ALLOWED'				=> $bbcode_status,
				'S_LINKS_ALLOWED'				=> $url_status,
				'S_BBCODE_IMG'					=> $img_status,
				'S_BBCODE_FLASH'				=> $flash_status,
				'S_BBCODE_QUOTE'				=> 1,
				'BBCODE_STATUS'					=> ($bbcode_status) ? sprintf($this->user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>') : sprintf($this->user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>'),
				'IMG_STATUS'					=> ($img_status) ? $this->user->lang['IMAGES_ARE_ON'] : $this->user->lang['IMAGES_ARE_OFF'],
				'FLASH_STATUS'					=> ($flash_status) ? $this->user->lang['FLASH_IS_ON'] : $this->user->lang['FLASH_IS_OFF'],
				'SMILIES_STATUS'				=> ($smilies_status) ? $this->user->lang['SMILIES_ARE_ON'] : $this->user->lang['SMILIES_ARE_OFF'],
				'URL_STATUS'					=> ($bbcode_status && $url_status) ? $this->user->lang['URL_IS_ON'] : $this->user->lang['URL_IS_OFF'],
				'S_HIDDEN_FIELDS'				=> '',
				'FORM_TITLE'					=> $this->user->lang('EDIT_PLACE'),
			));
		}

		return $this->helper->render('usermap_places_form.html', $this->user->lang('EDIT_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])));
	}

	/**
	 * List all Things of an ID
	 *
	 * @param type $id
	 */
	public function list_places($id, $start = 1)
	{
		$sql = 'SELECT COUNT(place_type_id) AS num_things
			FROM ' . $this->things_table . '
				WHERE place_type_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$total_places = (int) $this->db->sql_fetchfield('num_things');
		$this->db->sql_freeresult($result);

		$limit = (int) $this->config['topics_per_page'];

		$sql = 'SELECT *
			FROM ' . $this->things_table . '
				WHERE place_type_id = ' . (int) $id;
		$result = $this->db->sql_query_limit($sql, $limit, ($start -1)  * $limit);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('placesrow', array(
				'TITLE'			=> $row['thing_title'],
				'U_PLACE'		=> $this->helper->route('tas2580_usermap_place', array('id' => $row['thing_id'])),
				'LON'			=> $row['thing_lon'],
				'LAT'			=> $row['thing_lat'],
			));
		}
		$this->pagination->generate_template_pagination(array(
			'routes' => array(
				'tas2580_usermap_placelist',
				'tas2580_usermap_placelist_page',
			),
			'params' => array(
			),
		), 'pagination', 'start', $total_places, $limit, ($start - 1)  * $limit);

		$sql = 'SELECT *
			FROM ' . $this->place_type_table . '
			WHERE place_type_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		$this->template->assign_vars(array(
			'TOTAL_PLACES'			=> $this->user->lang('TOTAL_PLACES', (int) $total_places),
			'TITLE'					=> $row['place_type_title'],
		));

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $row['place_type_title'],
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_placelist', array('id' => $id)),
		));
		return $this->helper->render('usermap_places_list.html', $row['place_type_title']);
	}

	/**
	 * View a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function view_place($id)
	{
		$sql_array['FROM'][$this->things_table] = 't';
		$sql_array['SELECT'] = 't.*, pt.*';
		$sql_array['LEFT_JOIN'][] = array(
			'FROM'		=> array($this->place_type_table => 'pt'),
			'ON'		=> 'pt.place_type_id = t.place_type_id'
		);
		$sql_array['WHERE'] = 'thing_id = ' . (int) $id;
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		$page_title = $row['thing_title'];

		if ($this->user->data['user_usermap_lon'])
		{
			$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $row['thing_lon'], $row['thing_lat']);
		}

		$this->template->assign_vars(array(
			'S_BBCODE_ALLOWED'		=> 1,
			'THING_TITLE'			=> $row['thing_title'],
			'THING_TEXT'			=> generate_text_for_display($row['thing_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], 3, true),
			'USERMAP_MARKER'		=> $row['place_type_marker'],
			'S_DELETE'				=> $this->auth->acl_get('m_usermap_place_delete'),
			'S_EDIT'				=> $this->auth->acl_get('m_usermap_place_edit'),
			'S_DELETE_COMMENT'		=> $this->auth->acl_get('m_usermap_comment_delete'),
			'S_EDIT_COMMENT'		=> $this->auth->acl_get('m_usermap_comment_edit'),
			'U_DELETE'				=> $this->helper->route('tas2580_usermap_place_delete', array('id' => $row['thing_id'])),
			'U_EDIT'				=> $this->helper->route('tas2580_usermap_place_edit', array('id' => $row['thing_id'])),
			'S_IN_USERMAP'			=> true,
			'DISTANCE'				=> isset($distance) ? $distance : '',
			'USERMAP_CONTROLS'		=> 'false',
			'USERMAP_LON'			=> $row['thing_lon'],
			'USERMAP_LAT'			=> $row['thing_lat'],
			'USERMAP_ZOOM'			=> (int) 10,
			'MARKER_PATH'			=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things'),
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
			'BING_API_KEY'			=> $this->config['tas2580_usermap_bing_api_key'],
			'DEFAULT_MAP'			=> $this->config['tas2580_usermap_map_type'],
			'A_COMMENT'				=> $this->auth->acl_get('u_usermap_comment'),
			'U_COMMENT'				=> $this->helper->route('tas2580_usermap_comment', array('id' => $row['thing_id'])),
		));

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $page_title,
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_place', array('id' => $id)),
		));


		unset($sql_array);

		if (!function_exists('phpbb_get_user_rank'))
		{
			include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}

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

		// Display comments
		$sql_array['FROM'][$this->comment_table] = 'c';
		$sql_array['SELECT'] = 'c.*, u.user_id, u.username, u.user_colour, u.user_regdate, u.user_posts, u.user_lastvisit, u.user_rank, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height';
		$sql_array['LEFT_JOIN'][] = array(
			'FROM'		=> array(USERS_TABLE => 'u'),
			'ON'		=> 'u.user_id = c.place_comment_user_id'
		);
		$sql_array['WHERE'] = 'place_id = ' . (int) $id;
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$user_rank_data = phpbb_get_user_rank($row, $row['user_posts']);
			$this->template->assign_block_vars('comments', array(
				'COMMENT_TITLE'		=> $row['place_comment_title'],
				'COMMENT_TEXT'		=> generate_text_for_display($row['place_comment_text'], $row['place_comment_bbcode_uid'], $row['place_comment_bbcode_bitfield'], 3, true),
				'USERNAME'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'AVATAR'			=> ($this->user->optionget('viewavatars')) ? phpbb_get_user_avatar($row) : '',
				'RANK'				=> empty($user_rank_data['title']) ? $this->user->lang('NA') : $user_rank_data['title'],
				'U_EDIT'			=> $this->helper->route('tas2580_usermap_comment_edit', array('id' => $row['place_comment_id'])),
				'U_DELETE'			=> $this->helper->route('tas2580_usermap_comment_delete', array('id' => $row['place_comment_id'])),
			));
		}


		return $this->helper->render('usermap_places_view.html', $page_title);
	}

	public function add_place()
	{
		if (!$this->auth->acl_get('u_usermap_add_thing'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
		include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);

		$bbcode_status = $this->config['tas2580_usermap_allow_bbcode'];
		$url_status = $this->config['tas2580_usermap_allow_urls'];
		$img_status = $this->config['tas2580_usermap_allow_img'];
		$flash_status = $this->config['tas2580_usermap_allow_flash'];
		$smilies_status = $this->config['tas2580_usermap_allow_smilies'];
		$marker = '';
		$this->user->add_lang('posting');

		$submit = $this->request->is_set_post('submit');
		if ($submit)
		{
			$title = $this->request->variable('title', '', true);
			$message = $this->request->variable('message', '', true);
			$place_type_id = $this->request->variable('marker_type', 0);

			$data = array(
				'lon'		=> (float) substr($this->request->variable('lon', ''), 0, 10),
				'lat'		=> (float) substr($this->request->variable('lat', ''), 0, 10),
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

			if (utf8_clean_string($title) === '')
			{
				$error[] = $this->user->lang['EMPTY_SUBJECT'];
			}

			if (utf8_clean_string($message) === '')
			{
				$error[] = $this->user->lang['TOO_FEW_CHARS'];
			}

			if (sizeof($error))
			{
				$this->template->assign_vars(array(
					'ERROR'			=> implode('<br />', $error),
					'TITLE'			=> $title,
					'MESSAGE'		=> $message,
				));
			}
			else
			{
				generate_text_for_storage($message, $uid, $bitfield, $options, $bbcode_status, $url_status, $smilies_status);
				$sql_data = array(
					'thing_title'			=> $title,
					'thing_text'			=> $message,
					'bbcode_uid'			=> $uid,
					'bbcode_bitfield'		=> $bitfield,
					'thing_lat'				=> $data['lat'],
					'thing_lon'				=> $data['lon'],
					'place_type_id'			=> $place_type_id,
					'thing_user_id'			=> $this->user->data['user_id'],
				);
				$sql = 'INSERT INTO ' . $this->things_table . '
					' . $this->db->sql_build_array('INSERT', $sql_data);
				$this->db->sql_query($sql);
				$thing_id = $this->db->sql_nextid();

				trigger_error($this->user->lang['THING_ADDED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $thing_id)) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang['BACK_TO_USERMAP'] . '</a>');
			}
		}

		$path = $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things');

		generate_smilies('inline', 0);
		display_custom_bbcodes();

		$s_hidden_fields = build_hidden_fields(array(
			'lon'		=> $this->request->variable('lon', ''),
			'lat'		=> $this->request->variable('lat', ''),
		));

		$this->template->assign_vars(array(
			'TITLE'							=> $this->request->variable('title', '', true),
			'MESSAGE'						=> $this->request->variable('message', '', true),
			'MARKER'						=> $this->marker_type_select($this->request->variable('thing_type_id', 0)),
			'USERMAP_MARKER_PATH'			=> $path,
			'S_BBCODE_ALLOWED'				=> $bbcode_status,
			'S_LINKS_ALLOWED'				=> $url_status,
			'S_BBCODE_IMG'					=> $img_status,
			'S_BBCODE_FLASH'				=> $flash_status,
			'S_BBCODE_QUOTE'				=> 1,
			'BBCODE_STATUS'					=> ($bbcode_status) ? sprintf($this->user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>') : sprintf($this->user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>'),
			'IMG_STATUS'					=> ($img_status) ? $this->user->lang['IMAGES_ARE_ON'] : $this->user->lang['IMAGES_ARE_OFF'],
			'FLASH_STATUS'					=> ($flash_status) ? $this->user->lang['FLASH_IS_ON'] : $this->user->lang['FLASH_IS_OFF'],
			'SMILIES_STATUS'				=> ($smilies_status) ? $this->user->lang['SMILIES_ARE_ON'] : $this->user->lang['SMILIES_ARE_OFF'],
			'URL_STATUS'					=> ($bbcode_status && $url_status) ? $this->user->lang['URL_IS_ON'] : $this->user->lang['URL_IS_OFF'],
			'S_HIDDEN_FIELDS'				=> $s_hidden_fields,
			'FORM_TITLE'					=> $this->user->lang('ADD_PLACE'),
		));

		return $this->helper->render('usermap_places_form.html', $this->user->lang('ADD_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])));
	}

	private function marker_type_select($sel = 0)
	{
		$options = '';
		$sql = 'SELECT place_type_id, place_type_title, place_type_marker
			FROM ' . $this->place_type_table . '
			ORDER BY place_type_title';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$selected = ($row['place_type_id'] == $sel) ? ' selected="selected"' : '';
			$options .= '<option' . $selected . ' value="' . $row['place_type_id'] . '">' . $row['place_type_title'] . '</option>';
		}
		return $options;
	}
}
