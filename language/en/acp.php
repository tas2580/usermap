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
	'ACP_USERMAP_TITLE'				        => 'User Map Settings',
	'ACP_MAP_SETTINGS'				        => 'Map Settings',
	'ACP_USERMAP_ZOOM'				        => 'Zoom',
	'ACP_USERMAP_ZOOM_EXPLAIN'		        => 'Standard Zoom when you call the map.',
    'ACP_DEFAULT_COORDINATES'				=> 'Standard Coordinates',
	'ACP_DEFAULT_COORDINATES_EXPLAIN'		=> 'Center Coordinates for map.',
	'ACP_COORDINATES'				        => 'Standard Coordinates',
	'ACP_COORDINATES_EXPLAIN'		        => 'Coordinates on the map when center is called.',
	'ACP_GET_COORDINATES'			        => 'Add My Coordinates',
	'ACP_SUBMIT'					        => 'Save Settings',
	'ACP_SAVED'					            => 'The settings have been saved successfully',
	'ACP_USERMAP_MARKER'			        => 'Users Map Marker',
	'ACP_USERMAP_MARKER_DESCRIPTION'        => 'Choose a marker for users in the group on the user map.',
	'ACP_USERMAP_COORDINATES'		        => 'Coordinates for the user map',
	'ACP_USERMAP_COORDINATES_EXPLAIN'       => 'The coordinates of the user for the user map.',
	'ACP_USERMAP_LON'				        => 'Longitude',
	'ACP_USERMAP_LAT'				        => 'Latitude',
	'ACP_MAP_TYPE'					        => 'Map',
	'ACP_MAP_TYPE_EXPLAIN'			        => 'Choose the type of map to be used.',
	'ACP_GOOGLE_API_KEY'			        => 'Google Maps API Key',
	'ACP_GOOGLE_API_KEY_EXPLAIN'	        => 'Add your <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">API Key</a> from Google Maps.',
	'ACP_BING_API_KEY'						=> 'Bing! Maps API Key',
	'ACP_BING_API_KEY_EXPLAIN'				=> 'Add your Bing! Maps <a href="https://msdn.microsoft.com/de-de/library/ff428642.aspx">API Key</a> to use Bing! Maps.',
	'ACP_MAP_OSM'					        => 'Open Street Maps',
	'ACP_MAP_GOOGLE'				        => 'Google Maps',
	'ACP_KM'					            => 'Kilometer',
    'ACP_MILES'								=> 'Miles',
	'ACP_SEARCH_DISTANCE'			        => 'Search distance',
	'ACP_SEARCH_DISTANCE_EXPLAIN'	        => 'Radius to be searched by the user unless otherwise stated.',
    'ACP_MAP_IN_VIEWPROFILE'			    => 'Map',
	'ACP_MAP_IN_VIEWPROFILE_EXPLAIN'	    => 'Show map in Viewprofile',
    'ACP_DISTANCE_IN_VIEWTOPIC' 		    => 'Distance',
	'DISTANCE_IN_VIEWTOPIC_EXPLAIN' 	    => 'Show distance in Viewtopic',
    'ACP_DISTANCE_FORMAT'				    => 'Units',
	'DISTANCE_FORMAT_EXPLAIN'			    => 'Distance shown in these units',
    'ACP_MAX_MARKER'					    => 'Marker Number',
	'ACP_MAX_MARKER_EXPLAIN'			    => 'Maximum Number of markers shown in map for each zoom level. Note: too many will slow the display',
    'ACP_USERMAP_LEGEND'				    => 'Show in Legend',
	'ACP_USERMAP_LEGEND_DESCRIPTION'	    => 'The marker for the group is shown when activated.',
	'ACP_USERMAP_HIDE'					    => 'Hide marker on map',
	'ACP_USERMAP_HIDE_DESCRIPTION'		    => 'Do you want to hide own marker?',
	'ERROR_GET_COORDINATES'					=> 'Turn on location feature of the browser.',
    'ACP_THING_NAME'						=> 'Name for point of interest',
	'ACP_THING_NAME_EXPLAIN'				=> 'Name points of interest on the map. For example places or meeting locations.',
	'ACP_USER_INPUT_SETTINGS'				=> 'User Input',
	'ACP_INPUT_METHOD'						=> 'Input Method',
	'ACP_INPUT_METHOD_EXPLAIN'				=> 'Choose how the user should input their location.',
	'ACP_ZIP'								=> 'ZIP Code',
	'ACP_COORDINATES'						=> 'Coordinates',
	'ACP_SHOW_ON_REGISTER'					=> 'Show on register',
	'ACP_SHOW_ON_REGISTER_EXPLAIN'			=> 'Show input field on registration page?',
	'ACP_FORCE_ON_REGISTER'					=> 'Force on Register',
	'ACP_FORCE_ON_REGISTER_EXPLAIN'			=> 'Force the user to input location when registering?',
	'ACP_COUNTRY_SELECT'					=> 'Default Country',
	'ACP_COUNTRY_SELECT_EXPLAIN'			=> 'Choose the default country for the user.',
	'ACP_ALLOW_BBCODE'						=> 'allow BB Code',
	'ACP_ALLOW_SMILIES'						=> 'allow Smilies',
	'ACP_ALLOW_URL'							=> 'allow URL',
	'ACP_ALLOW_IMG'							=> 'allow IMG',
	'ACP_ALLOW_FLASH'						=> 'allow Flash',
	'ACP_DISPLAY_COORDINATES'				=> 'Show Coordinates',
	'ACP_DISPLAY_COORDINATES_EXPLAIN'		=> 'Show coordinates in the maker?',
	'ACP_GOOGLE_TERRAIN'					=> 'Google Terrain',
	'ACP_GOOGLE_ROADMAP'					=> 'Google Roads',
	'ACP_GOOGLE_HYBRID'						=> 'Google Hybrid',
	'ACP_GOOGLE_SATELLITE'					=> 'Google Satellite',
	'ACP_BING_ROAD'							=> 'Bing! Roads',
	'ACP_BING_HYBRID'						=> 'Bing! Hybrid',
	'ACP_BING_AERIAL'						=> 'Bing! Satellite',
	'ACP_OSM_MACKNICK'						=> 'OSM Macknick',
));



