<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// â€™ Â» â€œ â€ â€¦
//
$lang = array_merge($lang, array(
    'ACL_CAT_USERMAP'				=> 'User Map',
	'ACL_U_USERMAP_VIEW'		    => 'Can view the user map',
	'ACL_U_USERMAP_ADD'		        => 'Can add themselves to the user map',
	'ACL_U_USERMAP_SEARCH'	        => 'Can search the user map', 
	'ACL_U_USERMAP_HIDE'			=> 'Can hide own location on user map',
	'ACL_U_USERMAP_ADD_THING'		=> 'Can add point of interest to user map',
	'ACL_U_USERMAP_DELETE_THING'	=> 'Can delete point of interest from user map',
	'ACL_U_USERMAP_EDIT_THING'		=> 'Can edit point of interest on user map',
));

