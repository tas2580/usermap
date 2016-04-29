<?php
/**
*
* @package phpBB Extension - tas2580 Mobile Notifier
* @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license https://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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

$lang = array_merge($lang, array(
	'USERMAP_TITLE'						=> 'Benutzer Karte',
	'BACK_TO_USERMAP'					=> 'Zurück zur Karte',
	'DISTANCE'							=> 'Distanz',
	'KM'									=> 'Kilometer',
	'MILES'								=> 'Meilen',
	'SET_MY_POSITION'						=> 'Eigene Positon hier setzen',
	'CLICK_TO_SET'							=> 'Klicke auf die Map um deine Positon angzugeben.',
	'SET_POSITION'							=> 'Position setzen',
	'CONFIRM_COORDINATES_SET'				=> 'Bist du sicher das du deine Position auf die folgenden Koordinaten setzen möchtest:<br>Längengrad %1$s<br> Breitengrad %2$s',
	'LON'									=> 'Längengrad',
	'LAT'									=> 'Breitengrad',
	'MENU_CLOSE'							=> 'Abbrechen',
	'MENU_SEARCH'						=> 'Benutzer der Nähe (%s KM)',
	'SEARCH_EXPLAIN'						=> 'Benutzer im Umkreis von %1$s Kilometern um %2$s, %3$s',
	'USERMAP_SEARCH'						=> 'Benutzer Suche',
	'MEMBERS'							=> 'Benutzername',
	'KLICK_TO_ADD'						=> 'Klick mit der rechten Maustaste auf die Karte um dich einzutragen!',
	'POSITION_SET'							=> 'Deine Position auf der Karte wurde geändert!',
	'JAVASCRIPT_ERROR'						=> 'Du hast JavaScript deaktiviert oder es ist ein Fehler aufgetreten. Um die Benutzer Karte anzeigen zu können muss JavaScript aktiviert sein!',

));
