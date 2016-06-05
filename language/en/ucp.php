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
	'UCP_USERMAP_COORDINATES'			    => 'Coordinates for user map',
	'UCP_USERMAP_COORDINATES_EXPLAIN'		=> 'Enter your coordinates for the <a href="%s">User Map</a>.',
    'UCP_USERMAP_ZIP'					    => 'Zip Code for the user map',
	'UCP_USERMAP_ZIP_EXPLAIN'				=> 'Enter your zip code here to place your marker on the  <a href="%s">User Map</a>.',
	'UCP_USERMAP_LON'				        => 'Longitude',
	'UCP_USERMAP_LAT'				        => 'Latitude',
	'UCP_USERMAP_GET_COORDINATES'		    => 'Add My Coordinates',
    'UCP_USERMAP_HIDE'						=> 'Hide on map',
	'UCP_USERMAP_HIDE_DESCRIPTION'			=> 'To hide your marker on the map, select "yes".',
	'ERROR_GET_COORDINATES'					=> 'Unable to read your location from the browser.',
	'NEED_REGISTER_ZIP'						=> 'You have to enter a valid zip code.',
));



