<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
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

$lang = array_merge($lang, array(
	'USERMAP_TITLE'						=> 'Benutzer Karte',
	'BACK_TO_USERMAP'					=> 'Zurück zur Benutzer Karte',
	'DISTANCE'							=> 'Distanz',
	'KM'								=> 'Kilometer',
	'MILES'								=> 'Meilen',
	'SET_MY_POSITION'					=> 'Eigene Positon hier setzen',
	'CLICK_TO_SET'						=> 'Klicke auf die Map um deine Positon angzugeben.',
	'SET_POSITION'						=> 'Position setzen',
	'CONFIRM_COORDINATES_SET'			=> 'Bist du sicher das du deine Position auf die folgenden Koordinaten setzen möchtest:<br>Längengrad %1$s<br> Breitengrad %2$s',
	'LON'								=> 'Längengrad',
	'LAT'								=> 'Breitengrad',
	'MENU_CLOSE'						=> 'Abbrechen',
	'MENU_SEARCH'						=> 'Benutzer der Nähe (%s KM)',
	'SEARCH_EXPLAIN'					=> 'Benutzer im Umkreis von %1$s Kilometern um %2$s, %3$s',
	'USERMAP_SEARCH'					=> 'Benutzer Suche',
	'MEMBERS'							=> 'Benutzername',
	'KLICK_TO_ADD'						=> 'Klick mit der rechten Maustaste auf die Karte um dich einzutragen!',
	'POSITION_SET'						=> 'Deine Position auf der Karte wurde geändert!',
	'JAVASCRIPT_ERROR'					=> 'Du hast JavaScript deaktiviert oder es ist ein Fehler aufgetreten. Um die Benutzer Karte anzeigen zu können muss JavaScript aktiviert sein!',
	'NEED_MARKER'						=> 'Du musst einen Marker auswählen',
	'PLACE_ADDED'						=> 'Der Eintrag wurde erfolgreich zur Datenbank hinzugefügt.',
	'BACK_TO_PLACE'						=> 'Zurück zum Ort',
	'MENU_ADD_PLACE'					=> 'Ort hinzufügen',
	'PLACE'								=> 'Ort',
	'ADD_PLACE'							=> 'Ort hinzufügen',
	'EDIT_PLACE'						=> 'Ort bearbeiten',
	'PLACE_UPDATED'						=> 'Der Eintrag wurde erfolgreich in der Datenbank geändert.',
	'PLACE_MARKER'						=> 'Marker',
	'PLACE_MARKER_EXPLAIN'				=> 'Wähle einen Marker für die Karte',
	'CONFIRM_DELETE_PLACE'				=> 'Bist du sicher das du den Eintrag entgültig löschen möchtest?',
	'DELETE_PLACE_SUCCESS'				=> 'Der Eintrag wurde gelöscht',
	'MARKER'							=> 'Marker',
	'SELECT_MAP'						=> 'Karte wählen',
	'GOOGLE_TERRAIN'					=> 'Google Terrain',
	'GOOGLE_ROADMAP'					=> 'Google Strassen',
	'GOOGLE_HYBRID'						=> 'Google Hybrid',
	'GOOGLE_SATELLITE'					=> 'Google Satellit',
	'BING_ROAD'							=> 'Bing! Strassen',
	'BING_HYBRID'						=> 'Bing! Hybrid',
	'BING_AERIAL'						=> 'Bing! Satellit',
	'OSM_MACKNICK'						=> 'OSM Macknick',
	'DISTANCE_IS'						=> 'Die Distanz von <b>%s</b> (Länge), <b>%s</b> (Breite) zu deiner Position beträgt <b>%s</b> Luftlinie.',
	'GET_DISTANCE'						=> 'Distanz zu mir',
	'TOTAL_PLACES'						=> 'Orte insgasammt %d',
	'COORDINATES'						=> 'Koordinaten',
	'NO_PLACES'							=> 'Es sind keine Orte vorhanden die angezeigt werden können.',
	'COMMENT_ADDED'						=> 'Dein Kommentar wurde hinzugefügt.',
	'ADD_COMMENT'						=> 'Kommentar schreiben',
	'USER_TOTAL'						=> 'Benutzer insgesammt',
	'PLACES_TOTAL'						=> 'Orte insgesammt',
	'DELETE_COMMENT_SUCCESS'			=> 'Das Kommentar wurde erfolgreich gelösht!',
));
