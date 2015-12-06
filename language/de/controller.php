<?php
/**
*
* @package phpBB Extension - tas2580 Mobile Notifier
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

$lang = array_merge($lang, array(
	'USERMAP_TITLE'						=> 'Benutzer Karte',
	'BACK_TO_USERMAP'					=> 'Zurück zur Karte',
	'DISTANCE'							=> 'Diszanz zu mir',
	'KM'									=> 'KM',
	'SET_MY_POSITION'						=> 'Eigene Positon ändern',
	'CLICK_TO_SET'							=> 'Klicke auf die Map um deine Positon angzugeben.',
	'SET_POSITION'							=> 'Position setzen',
	'COORDINATES_SET'						=> 'Deine Position wurde auf die folgenden Koordinaten gesetzt:<br>Längengrad %1$s<br> Breitengrad %2$s'
));
