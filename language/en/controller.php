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

$lang = array_merge($lang, array(
	'USERMAP_TITLE'						=> 'User Map',
	'BACK_TO_USERMAP'					=> 'Return to User Map',
	'DISTANCE'						    => 'Distance',
	'KM'							    => 'Kilometer',
	'SET_MY_POSITION'					=> 'Set your own position here',
	'CLICK_TO_SET'						=> 'Click on the map to set your Position.',
	'SET_POSITION'						=> 'Set Position',
	'CONFIRM_COORDINATES_SET'			=> 'Are you sure you wish to set your position to the following coordinates:<br>Longitude %1$s<br> Latitude %2$s',
	'LON'							    => 'Latitude ',
	'LAT'							    => 'Longitude',
	'MENU_CLOSE'						=> 'Close',
	'MENU_SEARCH'						=> 'Users nearby (%s KM)',
	'SEARCH_EXPLAIN'					=> 'Users within %1$s Kilometer about %2$s, %3$s',
	'USERMAP_SEARCH'					=> 'Search User Map',
	'MEMBERS'						    => 'Members',
    'KLICK_TO_ADD'						=> 'Right click to add yourself to the map!',
	'POSITION_SET'						=> 'Your location on the map was changed!',
	'JAVASCRIPT_ERROR'					=> 'JavaScript is deactivated or an error occurred. You have to activate JavaScript to see the user map!',
	'NEED_MARKER'						=> 'You have to choose a marker',
	'THING_ADDED'						=> 'Your entry has been added successfully.',
	'BACK_TO_THING'						=> 'show the entry',
	'MENU_ADD_THING'					=> 'Add %s',
	'THING'								=> 'Point of Interest',
	'ADD_THING'							=> 'Add %s',
	'THING_UPDATED'						=> 'Your entry has been added successfully.',
	'THING_MARKER'						=> 'Marker',
	'THING_MARKER_EXPLAIN'				=> 'Choose your marker for the map',
	'CONFIRM_DELETE_THING'				=> 'Are you sure you want to delete the entry permanently?',
	'DELETE_THING_SUCCESS'				=> 'The entry has been deleted',
	'MARKER'							=> 'Marker',
	'SELECT_MAP'						=> 'Choose Map',
	'GOOGLE_TERRAIN'					=> 'Google Terrain',
	'GOOGLE_ROADMAP'					=> 'Google Roads',
	'GOOGLE_HYBRID'						=> 'Google Hybrid',
	'GOOGLE_SATELLITE'					=> 'Google Satellite',
	'BING_ROAD'							=> 'Bing! Roads',
	'BING_HYBRID'						=> 'Bing! Hybrid',
	'BING_AERIAL'						=> 'Bing! Satellite',
	'OSM_MACKNICK'						=> 'OSM Macknick',
));

	
	