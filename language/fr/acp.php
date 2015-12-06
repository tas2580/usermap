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
	'ACP_USERMAP_TITLE'					=> 'Carte des utilisateurs',
	'ACP_SETTINGS'						=> 'Paramétrage de la carte',
	'ACP_USERMAP_ZOOM'					=> 'Zoom',
	'ACP_USERMAP_ZOOM_EXPLAIN'			=> 'Zoom standard à l\'appel de la carte.',
	'ACP_COORDINATES'						=> 'Coordonnées standard',
	'ACP_COORDINATES_EXPLAIN'				=> 'Coordonnées de centrage de la carte lors de son appel.',
	'ACP_GET_COORDINATES'					=> 'Saisie de mes coordonnées',
	'ACP_SUBMIT'							=> 'Enregistrer les paramètres',
	'ACP_SAVED'							=> 'Les paramètres ont bien été enregistrés.',
	'ACP_USERMAP_MARKER'					=> 'Marqueur de la carte des utilisateurs',
	'ACP_USERMAP_MARKER_DESCRIPTION'		=> 'Sélection d\'un marqueur à utiliser pour ce groupe sur la carte des utilisateurs.',
	'ACP_USERMAP_COORDINATES'			=> 'Coordonnées de la carte des utilisateurs',
	'ACP_USERMAP_COORDINATES_EXPLAIN'		=> 'Coordonnées de l\'utilisateur pour la carte des utilisateur.',
	'ACP_USERMAP_LON'					=> 'Longitude',
	'ACP_USERMAP_LAT'						=> 'Latitude',
	'ACP_MAP_TYPE'						=> 'Carte',
	'ACP_MAP_TYPE_EXPLAIN'					=> 'Sélection d\'une carte à utiliser pour la carte des utilisateurs.',
	'ACP_GOOGLE_API_KEY'					=> 'Clef de l\'API Google Maps',
	'ACP_GOOGLE_API_KEY_EXPLAIN'			=> 'Saisie de la <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">clef API</a> de Google Maps pour pouvoir utiliser Google Maps comme carte.',
	'ACP_MAP_OSM'						=> 'Cartes Open Street',
	'ACP_MAP_GOOGLE'						=> 'Cartes Google',

));
