<?php
/**
*
* @package phpBB Extension - Wiki
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
	'UCP_USERMAP_COORDINATES'			=> 'Coordonnées pour la carte des utilisateurs',
	'UCP_USERMAP_COORDINATES_EXPLAIN'		=> 'Indiquez vos coordonnées pour l\'inscription sur le carte des utilisateurs.',
	'UCP_USERMAP_LON'					=> 'Longitude',
	'UCP_USERMAP_LAT'						=> 'Latitude',
	'UCP_USERMAP_GET_COORDINATES'		=> 'Inscription de mes coordonnées',
	'UCP_USERMAP_HIDE'					=> 'Afficher sur la carte',
	'UCP_USERMAP_MAIL'					=> 'Emailadresse die auf der Karte angezeigt wird',
	'UCP_USERMAP_PHONE'						=> 'Telefonnummer die auf der Karte angezeigt wird',
	'UCP_USERMAP_HIDE_DESCRIPTION'			=> 'Votre localisation doit-elle être affichée sur la carte ?',
));
