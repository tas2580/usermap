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
	'USERMAP_TITLE'						=> 'Carte des utilisateurs',
	'BACK_TO_USERMAP'					=> 'Retour à la carte',
	'SET_MY_POSITION'						=> 'Modifier sa propre position',
	'CLICK_TO_SET'							=> 'Cliquez sur la carte pour indiquer la position.',
	'SET_POSITION'							=> 'Définition d ela position',
	'COORDINATES_SET'						=> 'Votre position a été renseignée avec les paramètres suivants :<br>Longitude %1$s<br>Latitude %2$s'
));
