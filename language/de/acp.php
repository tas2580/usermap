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
	'ACP_USERMAP_TITLE'						=> 'Benutzer Karte',
	'ACP_MAP_SETTINGS'						=> 'Karten Einstellungen',
	'ACP_SETTINGS'							=> 'Weitere Einstellungen',
	'ACP_USERMAP_ZOOM'						=> 'Zoom',
	'ACP_USERMAP_ZOOM_EXPLAIN'				=> 'Standard Zoom beim aufrufen der Karte.',
	'ACP_DEFAULT_COORDINATES'				=> 'Standard Koordinaten',
	'ACP_DEFAULT_COORDINATES_EXPLAIN'		=> 'Koordinaten auf die die Karte beim aufrufen zentriert wird.',
	'ACP_GET_COORDINATES'					=> 'Meine Koordinaten eintragen',
	'ACP_SUBMIT'							=> 'Einstellungen speichern',
	'ACP_SAVED'								=> 'Die Einstellungen wurde erfolgreich gespeichert',
	'ACP_USERMAP_MARKER'					=> 'Benutzer Karte Marker',
	'ACP_USERMAP_MARKER_DESCRIPTION'		=> 'Wähle einen Marker der für Benutzer in dieser Gruppe auf der Benutzer Karte verwendet wird.',
	'ACP_USERMAP_COORDINATES'				=> 'Koordinaten für die Benutzer Karte',
	'ACP_USERMAP_COORDINATES_EXPLAIN'		=> 'Die Koordinaten den Benutzers für die Benutzer Karte.',
	'ACP_USERMAP_LON'						=> 'Längengrad',
	'ACP_USERMAP_LAT'						=> 'Breitengrad',
	'ACP_GOOGLE_API_KEY'					=> 'Google Maps API Key',
	'ACP_GOOGLE_API_KEY_EXPLAIN'			=> 'Trage deinen Google Maps <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">API Key</a> ein um Google Maps als Karte verwenden zu können.',
	'ACP_BING_API_KEY'						=> 'Bing! Maps API Key',
	'ACP_BING_API_KEY_EXPLAIN'				=> 'Trage deinen Bing! Maps <a href="https://msdn.microsoft.com/de-de/library/ff428642.aspx">API Key</a> ein um Bing! Maps als Karte verwenden zu können.',
	'ACP_KM'								=> 'Kilometer',
	'ACP_SEARCH_DISTANCE'					=> 'Such Distanz',
	'ACP_SEARCH_DISTANCE_EXPLAIN'			=> 'Umkreis in dem Benutzer gesucht werden sollen wenn nichts anderes angegeben ist.',
	'ACP_MAP_IN_VIEWPROFILE'				=> 'Karte im Profil anzeigen',
	'ACP_MAP_IN_VIEWPROFILE_EXPLAIN'		=> 'Zeigt eine Karte mit dem Standort des Benutzers im Profil an. Zusätzlich wird berechnet wie weit man selber von dem Benutzer entfernt ist.',
	'ACP_DISTANCE_IN_VIEWTOPIC'				=> 'Distanz in Themenansicht',
	'DISTANCE_IN_VIEWTOPIC_EXPLAIN'			=> 'Zeigt die Distanz zu einem Benutzer neben seinen Beiträgen an. Dazu müssen der jeweilige Benutzer und der angemeldete Benutzer auf der Karte eingetragen sein.',
	'ACP_DISTANCE_FORMAT'					=> 'Distanzen Format',
	'DISTANCE_FORMAT_EXPLAIN'				=> 'Distanzen in Kilometern oder Meilen anzeigen?',
	'ACP_KM'								=> 'Kilometer',
	'ACP_MILES'								=> 'Meilen',
	'ACP_MAX_MARKER'						=> 'Max. Marker',
	'ACP_MAX_MARKER_EXPLAIN'				=> 'Anzahl der maximal angezeigten Marker pro Karten Ausschnitt und Zoom Stufe. Beachte das zu viele Marker die Karte langsam machen können.',
	'ACP_USERMAP_LEGEND'					=> 'In der Legende anzeigen',
	'ACP_USERMAP_LEGEND_DESCRIPTION'		=> 'Wenn gesetzt wird der Marker der Gruppe in der Legende der Benutzer Karte angezeigt.',
	'ACP_USERMAP_HIDE'						=> 'Auf Karte verbergen',
	'ACP_USERMAP_HIDE_DESCRIPTION'			=> 'Stelle die Option auf Ja um den Marker des Benutzers auf der Karte auszublenden.',
	'ERROR_GET_COORDINATES'					=> 'Deine Koordinaten konnten nicht vom Browser ausgelesen werden.',
	'ACP_USER_INPUT_SETTINGS'				=> 'Benutzer Eingabe',
	'ACP_INPUT_METHOD'						=> 'Eingabe Methode',
	'ACP_INPUT_METHOD_EXPLAIN'				=> 'Wähle aus wie der Benutzer seinen Standort eingeben soll.',
	'ACP_ZIP'								=> 'Postleitzahl',
	'ACP_COORDINATES'						=> 'Koordinaten',
	'ACP_SHOW_ON_REGISTER'					=> 'Bei Registrierung anzeigen',
	'ACP_SHOW_ON_REGISTER_EXPLAIN'			=> 'Soll der Benutzer bei der Registrierung seinen Standort angeben können?',
	'ACP_FORCE_ON_REGISTER'					=> 'Bei Registrierung erzwingen',
	'ACP_FORCE_ON_REGISTER_EXPLAIN'			=> 'Soll die Eingabe des Standorts bei der Registrierung erzwungen werden?',
	'ACP_COUNTRY_SELECT'					=> 'Standard Land',
	'ACP_COUNTRY_SELECT_EXPLAIN'			=> 'Wähle ein Land das für den Benutzer vorausgewählt ist wenn er noch kein Land ausgewählt hat.',
	'ACP_ALLOW_BBCODE'						=> 'BB Code erlauben',
	'ACP_ALLOW_SMILIES'						=> 'Smilies erlauben',
	'ACP_ALLOW_URL'							=> 'URL erlauben',
	'ACP_ALLOW_IMG'							=> 'IMG erlauben',
	'ACP_ALLOW_FLASH'						=> 'Flash erlauben',
	'ACP_DISPLAY_COORDINATES'				=> 'Koordinaten anzeigen',
	'ACP_DISPLAY_COORDINATES_EXPLAIN'		=> 'Sollen die Koordinaten im Marker auf der Karte angezeigt werden?',
	'NEED_MARKER'							=> 'Du musst einen Marker auswählen!',
	'ACP_USERMAP_ADD_PLACE_TYPE'			=> 'Ort Typ hinzufügen',
	'ACP_USERMAP_EDIT_PLACE_TYPE'			=> 'Ort Typ bearbeiten',
	'ACP_PLACE_TYPE_TITLE'					=> 'Name',
	'ACP_PLACE_TYPE_TITLE_EXPLAIN'			=> 'Gib einen Namen für den Ort Typ an.',
	'ACP_PLACE_TYPE_EDIT_SUCCESS'			=> 'Der Orts Typ wurde erfolgreich bearbeidet',
	'ACP_PLACE_MARKER'						=> 'Marker',
	'ACP_PLACE_MARKER_EXPLAIN'				=> 'Wähle einen Marker der für Orte des Typs verwendet werden soll.',
	'ACP_PLACE_DISPLAY_LEGEND'				=> 'In Legende anzeigen',
	'ACP_PLACE_DISPLAY_LEGEND_EXPLAIN'		=> 'Sollen Orte des Typs in der Legende angezeit werden?',
	'ACP_PLACE_TYPE_ADD_SUCCESS'			=> 'Der Typ für Orte wurde hinzugefügt',
	'ACP_ADD_PLACE_TYPE'					=> 'Typ hinzufügen',
	'ACP_PLACE_TYPES'						=> 'Arten von Orten',

	'ACP_DEFAULT_MAP'						=> 'Standard',
	'ACP_ACTIVE_MAPS'						=> 'Aktive Karten',
	'ACP_INACTIVE_MAPS'						=> 'Inaktive Karten',
	'ACP_MAPS_EXPLAIN'						=> 'Hier kannst du die Karten verwalten die für den Benutzer verfügbar sind. Der Benutzer kann aus allen aktiven Karten auswählen. Mindestens eine Karte muss aktiv aund als Standard markiert sein.',
	'ACP_MAP_DISPLAY_NAME'					=> 'Angezeigter Name',
	'MAP_DISPLAY_NAME_EXPLAIN'				=> 'Der Name der dem Benutzer für die Karte angezeigt wird.',
	'ACP_MAP_ACTIVE'						=> 'Karte verfügbar',
	'ACP_MAP_ACTIVE_EXPLAIN'				=> 'Soll die Karte für den Benutzer zur Auswahl setehen oder nicht?',
	'ACP_MAP_DEFAULT'						=> 'Standart Karte',
	'ACP_MAP_DEFAULT_EXPLAIN'				=> 'Soll die Karte beim ersten Aufruf der Usermap angezeigt werden?',
	'ACP_MAP_EDIT_SUCCESS'					=> 'Die Karte wurde erfolgreich geändert.',
	'EMPTY_MAP_TITLE'						=> 'Du musst einen Namen für die Karte angeben.',
	'DEFAULT_MAP_NOT_ACTIVE'				=> 'Wenn die Karte Standard sein soll muss sie auch aktiv sein.',

));
