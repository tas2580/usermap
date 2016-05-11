<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\acp;

class usermap_info
{
	function module()
	{
		return array(
			'filename'		=> 'tas2580\usermap\usermap_module',
			'title'			=> 'ACP_USERMAP_TITLE',
			'version'		=> '0.1.0',
			'modes'		=> array(
				'settings'    => array(
					'title'		=> 'ACP_USERMAP_SETTINGS',
					'auth'	=> 'ext_tas2580/usermap&& acl_a_board',
					'cat'		=> array('ACP_USERMAP_TITLE')
				),
				'things'    => array(
					'title'		=> 'ACP_USERMAP_THINGS',
					'auth'	=> 'ext_tas2580/usermap&& acl_a_board',
					'cat'		=> array('ACP_USERMAP_TITLE')
				),
			),
		);
	}
}
