<?php
/**
*
* @package phpBB Extension - tas2580 Mobile Notifier
* @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license https://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'DISTANCE'						=> 'Distance',
	'KM'							=> 'Kilometer',
	'SET_MY_POSITION'					=> 'Set your own position here',
	'CLICK_TO_SET'						=> 'Click on the map to set your Position.',
	'SET_POSITION'						=> 'Set Position',
	'CONFIRM_COORDINATES_SET'				=> 'Are you sure you wish to set your position to the following coordinates:<br>Longitude %1$s<br> Latitude %2$s',
	'LON'							=> 'Latitude ',
	'LAT'							=> 'Longitude',
	'MENU_CLOSE'						=> 'Close',
	'MENU_SEARCH'						=> 'Users nearby (%s KM)',
	'SEARCH_EXPLAIN'					=> 'Users within %1$s Kilometer about %2$s, %3$s',
	'USERMAP_SEARCH'					=> 'Search User Map',
	'MEMBERS'						=> 'Members',
));
