<?php
/**
*
* @package phpBB Extension - Wiki
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
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
	'ACP_USERMAP_TITLE'				=> 'User Map',
	'ACP_MAP_SETTINGS'				=> 'Map Settings',
	'ACP_USERMAP_ZOOM'				=> 'Zoom',
	'ACP_USERMAP_ZOOM_EXPLAIN'			=> 'Standard Zoom when you call the map.',
	'ACP_COORDINATES'				=> 'Standard Coordinates',
	'ACP_COORDINATES_EXPLAIN'			=> 'Coordinates on the map when center is called.',
	'ACP_GET_COORDINATES'				=> 'Add My Coordinates',
	'ACP_SUBMIT'					=> 'Save Settings',
	'ACP_SAVED'					=> 'The settings have been saved successfully',
	'ACP_USERMAP_MARKER'				=> 'Users Map Marker',
	'ACP_USERMAP_MARKER_DESCRIPTION'		=> 'Choose a marker for users in the group on the user map.',
	'ACP_USERMAP_COORDINATES'			=> 'Coordinates for the user map',
	'ACP_USERMAP_COORDINATES_EXPLAIN'		=> 'The coordinates of the user for the user map.',
	'ACP_USERMAP_LON'				=> 'Degrees of longitude',
	'ACP_USERMAP_LAT'				=> 'Degrees of latitude',
	'ACP_MAP_TYPE'					=> 'Map',
	'ACP_MAP_TYPE_EXPLAIN'				=> 'Choose the type of map to be used.',
	'ACP_GOOGLE_API_KEY'				=> 'Google Maps API Key',
	'ACP_GOOGLE_API_KEY_EXPLAIN'			=> 'Add your <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">API Key</a> from Google Maps.',
	'ACP_MAP_OSM'					=> 'Open Street Maps',
	'ACP_MAP_GOOGLE'				=> 'Google Maps',
	'ACP_KM'					=> 'Kilometer',
	'ACP_SEARCH_DISTANCE'				=> 'Search distance',
	'ACP_SEARCH_DISTANCE_EXPLAIN'			=> 'Radius to be searched by the user unless otherwise stated.',
));
