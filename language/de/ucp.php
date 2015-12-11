<?php
/**
*
* @package phpBB Extension - Wiki
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
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
	'UCP_USERMAP_COORDINATES'			=> 'Koordinaten für die Benutzer Karte',
	'UCP_USERMAP_COORDINATES_EXPLAIN'		=> 'Gib hier deine Koordinaten an um dich auf der <a href="%s">Benutzer Karte</a> einzutragen.',
	'UCP_USERMAP_LON'					=> 'Längengrad',
	'UCP_USERMAP_LAT'						=> 'Breitengrad',
	'UCP_USERMAP_GET_COORDINATES'		=> 'Meine Koordinaten eintragen',
	'UCP_USERMAP_HIDE'					=> 'Auf Karte verbergen',
	'UCP_USERMAP_HIDE_DESCRIPTION'			=> 'Stelle die Option auf Ja um deinen Marker auf der Karte auszublenden.',
));
