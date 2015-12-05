<?php
/**
*
* @package phpBB Extension - tas2580 Content Security Policy
* @copyright (c) 2014 tas2580
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
					'title'		=> 'ACP_USERMAP_TITLE',
					'auth'	=> 'ext_tas2580/usermap&& acl_a_board',
					'cat'		=> array('ACP_USERMAP_TITLE')
				),
			),
		);
	}
}
