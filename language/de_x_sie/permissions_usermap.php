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
// ’ » “ ” …
//
$lang = array_merge($lang, array(
	'ACL_CAT_USERMAP'				=> 'Benutzerkarte',
	'ACL_U_USERMAP_VIEW'			=> 'Kann die Benutzerkarte anschauen',
	'ACL_U_USERMAP_ADD'				=> 'Kann sich in die Benutzerkarte eintragen',
	'ACL_U_USERMAP_SEARCH'			=> 'Kann Benutzer im Umkreis suchen',
	'ACL_U_USERMAP_HIDE'			=> 'Kann seinen Standort auf der Benutzerkarte ausblenden',
	'ACL_U_USERMAP_ADD_PLACE'		=> 'Kann einen Ort auf die Benutzerkarte eintragen',
	'ACL_M_USERMAP_DELETE_PLACE'	=> 'Kann einen Ort von der Benutzerkarte löschen',
	'ACL_M_USERMAP_EDIT_PLACE'		=> 'Kann einen Ort auf der Benutzerkarte bearbeiten',
	'ACL_U_USERMAP_COMMENT_PLACE'	=> 'Kann Kommentare zu Orten schreiben',
	'ACL_M_USERMAP_COMMENT_DELETE'	=> 'Kann Kommentare löschen',
	'ACL_M_USERMAP_COMMENT_EDIT'	=> 'Kann Kommentare bearbeiten',
));
