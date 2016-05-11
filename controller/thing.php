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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\controller\helper $helper, \phpbb\pagination $pagination, \phpbb\path_helper $path_helper, \phpbb\request\request $request, $phpbb_extension_manager, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext, $things_table)
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

		$this->user->add_lang_ext('tas2580/usermap', 'controller');

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'	=> $this->helper->route('tas2580_usermap_index', array()),
		));

	}


	/**
	 * Delete a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function delete_thing($id)
	{
		if (!$this->auth->acl_get('u_usermap_delete_thing'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		if (confirm_box(true))
		{
			$sql = 'DELETE FROM ' . $this->things_table . '
				WHERE thing_id = ' . (int) $id;
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
		redirect($this->helper->route('tas2580_usermap_thing', array('id' => $id)));
	}

	/**
	 * Edit a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function edit_thing($id)
	{
		if (!$this->auth->acl_get('u_usermap_edit_thing'))
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
			$marker = $this->request->variable('marker', '', true);

			$error = array();
			if (utf8_clean_string($title) === '')
			{
				$error[] = $this->user->lang['EMPTY_SUBJECT'];
			}

			if (utf8_clean_string($message) === '')
			{
				$error[] = $this->user->lang['TOO_FEW_CHARS'];
			}

			if (empty($marker))
			{
				$error[] = $this->user->lang['NEED_MARKER'];
			}

			if (sizeof($error))
			{
				generate_smilies('inline', 0);
				display_custom_bbcodes();

				$this->template->assign_vars(array(
					'ERROR'					=> implode('<br />', $error),
					'TITLE'					=> $title,
					'MESSAGE'				=> $message,
					'MARKER_OPTIONS'		=> $this->marker_image_select($row['thing_marker'], 'marker/things/'),
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
					'thing_marker'			=> $marker,
				);
				$sql = 'UPDATE ' . $this->things_table . ' SET
					' . $this->db->sql_build_array('UPDATE', $sql_data) . '
						WHERE thing_id = ' . (int) $id;
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['THING_UPDATED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_thing', array('id' => $id)) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
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
				'MARKER_OPTIONS'				=> $this->marker_image_select($row['thing_marker'], 'marker/things/'),
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
				'FORM_TITLE'					=> $this->user->lang('EDIT_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])),
			));
		}

		return $this->helper->render('usermap_thing_form.html', $this->user->lang('EDIT_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])));

	}

	/**
	 * View a thing
	 *
	 * @param int $id	The Thing ID
	 * @return type
	 */
	public function view_thing($id)
	{
		$sql = 'SELECT *
			FROM ' . $this->things_table . '
			WHERE thing_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if ($this->user->data['user_usermap_lon'])
		{
			$distance = $this->get_distance($this->user->data['user_usermap_lon'], $this->user->data['user_usermap_lat'], $row['thing_lon'], $row['thing_lat']);
		}

		$this->template->assign_vars(array(
			'S_BBCODE_ALLOWED'		=> 1,
			'THING_TITLE'			=> $row['thing_title'],
			'THING_TEXT'			=> generate_text_for_display($row['thing_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], 3, true),
			'S_DELETE'				=> $this->auth->acl_get('u_usermap_delete_thing'),
			'S_EDIT'				=> $this->auth->acl_get('u_usermap_edit_thing'),
			'U_DELETE'				=> $this->helper->route('tas2580_usermap_thing_delete', array('id' => $row['thing_id'])),
			'U_EDIT'				=> $this->helper->route('tas2580_usermap_thing_edit', array('id' => $row['thing_id'])),
			'USERMAP_MARKER'		=> $row['thing_marker'],
			'S_IN_USERMAP'			=> true,
			'DISTANCE'				=> isset($distance) ? $distance : '',
			'USERMAP_CONTROLS'		=> 'false',
			'USERMAP_LON'			=> $row['thing_lon'],
			'USERMAP_LAT'			=> $row['thing_lat'],
			'USERMAP_ZOOM'			=> (int) 10,
			'MARKER_PATH'			=> $this->path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . 'marker/things'),
			'MAP_TYPE'				=> $this->config['tas2580_usermap_map_type'],
			'GOOGLE_API_KEY'		=> $this->config['tas2580_usermap_google_api_key'],
		));

		return $this->helper->render('usermap_thing_view.html', $row['thing_title']);
	}

	public function add_thing()
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
			$marker = $this->request->variable('marker', '', true);

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

			if (empty($marker))
			{
				$error[] = $this->user->lang['NEED_MARKER'];
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
					'thing_marker'			=> $marker,
					'thing_user_id'			=> $this->user->data['user_id'],
				);
				$sql = 'INSERT INTO ' . $this->things_table . '
					' . $this->db->sql_build_array('INSERT', $sql_data);
				$this->db->sql_query($sql);
				$thing_id = $this->db->sql_nextid();

				trigger_error($this->user->lang['THING_ADDED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_thing', array('id' => $thing_id)) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
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
			'MARKER_OPTIONS'				=> $this->marker_image_select($marker, 'marker/things/'),
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
			'FORM_TITLE'					=> $this->user->lang('ADD_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])),
		));

		return $this->helper->render('usermap_thing_form.html', $this->user->lang('ADD_THING', $this->user->lang($this->config['tas2580_usermap_thing_name'])));
	}
}
