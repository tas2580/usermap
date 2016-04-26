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
	'USERMAP_TITLE'						=> 'Carte des utilisateurs',
	'BACK_TO_USERMAP'					=> 'Retour à la carte',
	'DISTANCE'							=> 'Distance',
	'KM'									=> 'Kilomètres',
	'MILES'								=> 'Milles',
	'SET_MY_POSITION'						=> 'Modifier sa propre position',
	'CLICK_TO_SET'							=> 'Cliquez sur la carte pour indiquer la position.',
	'SET_POSITION'							=> 'Définition d ela position',
	'CONFIRM_COORDINATES_SET'				=> 'Êtes-vous certain que la position doit être définie avec les coordonnées suivantes : <br>Longitude %1$s<br>Latitude %2$s ?',
	'LON'									=> 'Longitude',
	'LAT'									=> 'Latitude',
	'MENU_CLOSE'							=> 'Abandonner',
	'MENU_SEARCH'						=> 'Utilisateurs proches (%s km)',
	'SEARCH_EXPLAIN'						=> 'Utilisateurs à une distance de %1$s km autour de %2$s, %3$s',
	'USERMAP_SEARCH'						=> 'Recherche d\'utilisateurs',
	'MEMBERS'							=> 'Noms des utilisateurs',
	'KLICK_TO_ADD'						=> 'Cliquez sur le bouton droit de la souris pour vous inscrire sur la carte.',
	'POSITION_SET'							=> 'Votre position sur la carte a été modifiée.',
	'JAVASCRIPT_ERROR'						=> 'JavaScript est désactivé ou une erreur s\'est produite. JavaScript doit être activé pour pouvoir afficher la carte.',
));
