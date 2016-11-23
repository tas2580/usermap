<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\controller;

class comment extends \tas2580\usermap\includes\class_usermap
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\user $user, \phpbb\template\template $template, $phpbb_root_path, $php_ext, $things_table, $comment_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->things_table = $things_table;
		$this->comment_table = $comment_table;

		$this->user->add_lang_ext('tas2580/usermap', 'controller');

		$this->bbcode_status = $this->config['tas2580_usermap_allow_bbcode'];
		$this->url_status = $this->config['tas2580_usermap_allow_urls'];
		$this->img_status = $this->config['tas2580_usermap_allow_img'];
		$this->flash_status = $this->config['tas2580_usermap_allow_flash'];
		$this->smilies_status = $this->config['tas2580_usermap_allow_smilies'];

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $this->user->lang('USERMAP_TITLE'),
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_index', array()),
		));

	}

	public function add($id)
	{
		$sql = 'SELECT thing_title
			FROM ' . $this->things_table . '
			WHERE thing_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		// Add breadcrumb
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'		=> $row['thing_title'],
			'U_VIEW_FORUM'		=> $this->helper->route('tas2580_usermap_place', array('id' => $id)),
		));

		$submit = $this->request->is_set_post('submit');
		if ($submit)
		{
			$sql_data = $this->generate_sql_array($id, true);
			if($sql_data)
			{
				$sql = 'INSERT INTO ' . $this->comment_table . '
					' . $this->db->sql_build_array('INSERT', $sql_data);
				$this->db->sql_query($sql);

				trigger_error($this->user->lang['COMMENT_ADDED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $id)) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang['BACK_TO_USERMAP'] . '</a>');
			}
		}

		$title = $this->request->variable('title', '', true);
		$text = $this->request->variable('message', '', true);
		$this->generate_form($title, $text);

		return $this->helper->render('usermap_comment_form.html', $this->user->lang('ADD_COMMENT'));
	}

	/**
	 * Delete a comment
	 *
	 * @param int $id	The comment ID
	 * @return type
	 */
	public function delete($id)
	{
		if (!$this->auth->acl_get('m_usermap_comment_delete'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$sql = 'SELECT place_id
			FROM ' . $this->comment_table . '
			WHERE place_comment_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if (confirm_box(true))
		{
			$sql = 'DELETE FROM ' . $this->comment_table . '
				WHERE place_comment_id = ' . (int) $id;
			$this->db->sql_query($sql);

			trigger_error($this->user->lang['DELETE_COMMENT_SUCCESS'] . '<br /><br /><a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $row['place_id']))  . '">' . $this->user->lang['BACK_TO_THING'] . '</a>');
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array(
				'id'    => $id,
			));
			confirm_box(false, $this->user->lang['CONFIRM_DELETE_THING'], $s_hidden_fields);
		}
		redirect($this->helper->route('tas2580_usermap_place', array('id' => $row['place_id'])));
	}

	public function edit($id)
	{
		if (!$this->auth->acl_get('m_usermap_comment_edit'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$submit = $this->request->is_set_post('submit');
		if ($submit)
		{
			$title = $this->request->variable('title', '', true);
			$text = $this->request->variable('message', '', true);
			$sql_data = $this->generate_sql_array($id);
			if($sql_data)
			{
				$sql = 'SELECT place_id
					FROM ' . $this->comment_table . '
					WHERE place_comment_id = ' . (int) $id;
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);

				$sql = 'UPDATE ' . $this->comment_table . ' SET
					' . $this->db->sql_build_array('UPDATE', $sql_data) . '
						WHERE place_comment_id = ' . (int) $id;
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['THING_UPDATED'] . '<br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_place', array('id' => $row['place_id'])) . '">' . $this->user->lang['BACK_TO_THING'] . '</a><br /><br />'
					. '<a href="' . $this->helper->route('tas2580_usermap_index', array()) . '">' . $this->user->lang['BACK_TO_USERMAP'] . '</a>');
			}
		}
		else
		{
			$sql = 'SELECT *
				FROM ' . $this->comment_table . '
				WHERE place_comment_id = ' . (int) $id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$text = generate_text_for_edit($row['place_comment_text'], $row['place_comment_bbcode_uid'], $row['place_comment_bbcode_bitfield'], 3, true);
			$text = $text['text'];
			$title = $row['place_comment_title'];
		}

		$this->generate_form($title, $text);

		return $this->helper->render('usermap_comment_form.html', $this->user->lang('EDIT_COMMENT'));
	}


	private function generate_form($title, $text)
	{
		$this->user->add_lang('posting');

		if(!function_exists('generate_smilies'))
		{
			include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
		}
		if(!function_exists('display_custom_bbcodes'))
		{
			include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}

		generate_smilies('inline', 0);
		display_custom_bbcodes();

		$this->template->assign_vars(array(
			'TITLE'							=> $title,
			'MESSAGE'						=> $text,
			'FORM_TITLE'					=> $this->user->lang('EDIT_COMMENT'),
			'S_BBCODE_ALLOWED'				=> $this->bbcode_status,
			'S_LINKS_ALLOWED'				=> $this->url_status,
			'S_BBCODE_IMG'					=> $this->img_status,
			'S_BBCODE_FLASH'				=> $this->flash_status,
			'S_BBCODE_QUOTE'				=> 1,
			'BBCODE_STATUS'					=> ($this->bbcode_status) ? sprintf($this->user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>') : sprintf($this->user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>'),
			'IMG_STATUS'					=> ($this->img_status) ? $this->user->lang['IMAGES_ARE_ON'] : $this->user->lang['IMAGES_ARE_OFF'],
			'FLASH_STATUS'					=> ($this->flash_status) ? $this->user->lang['FLASH_IS_ON'] : $this->user->lang['FLASH_IS_OFF'],
			'SMILIES_STATUS'				=> ($this->smilies_status) ? $this->user->lang['SMILIES_ARE_ON'] : $this->user->lang['SMILIES_ARE_OFF'],
			'URL_STATUS'					=> ($this->bbcode_status && $this->url_status) ? $this->user->lang['URL_IS_ON'] : $this->user->lang['URL_IS_OFF'],
		));
	}



	private function generate_sql_array($id, $add = false)
	{
		$error = array();
		$title = $this->request->variable('title', '', true);
		$message = $this->request->variable('message', '', true);
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
				'ERROR'		=> implode('<br />', $error),
			));
		}
		else
		{
			$uid = $bitfield = $options = '';
			generate_text_for_storage($message, $uid, $bitfield, $options, $this->bbcode_status, $this->url_status, $this->smilies_status);
			$sql_data = array(
				'place_comment_title'				=> $title,
				'place_comment_text'				=> $message,
				'place_comment_bbcode_uid'			=> $uid,
				'place_comment_bbcode_bitfield'		=> $bitfield,
			);
			if($add)
			{
				$sql_data['place_comment_time'] = time();
				$sql_data['place_comment_user_id'] = $this->user->data['user_id'];
				$sql_data['place_id'] = $id;
			}

			return $sql_data;
		}
	}

}
