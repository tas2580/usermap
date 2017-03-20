<?php
/**
*
* @package phpBB Extension - tas2580 Usermap
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\usermap\includes;

class class_usermap
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\config\config */
	protected $config;

	const REGEX_LON =  '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#';
	const REGEX_LAT =  '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#';

	const INPUT_ZIP = 0;
	const INPUT_CORD = 1;

	/**
	 * Get the distance between A and B
	 *
	 * @param float	$x1	lon A
	 * @param float	$y1	lat A
	 * @param float	$x2	lon B
	 * @param float	$y2	lat B
	 */
	protected function get_distance($x1, $y1, $x2, $y2)
	{
		if (empty($x1) || empty($y1) || empty($x2) || empty($y2))
		{
			return '';
		}

		// e = ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
		$distance = acos(sin($x1=deg2rad($x1))*sin($x2=deg2rad($x2))+cos($x1)*cos($x2)*cos(deg2rad($y2) - deg2rad($y1)))*(6378.137);

		if ($this->config['tas2580_usermap_distance_format'])
		{
			return round($distance, 2) . ' ' . $this->user->lang('KM');
		}
		return round($distance * 0.62137, 2) . ' ' . $this->user->lang('MILES');
	}

	/**
	 * Get coordinates from zip code
	 *
	 * @param string	$zip
	 * @param array		$error
	 * @return string
	 * @throws \RuntimeException
	 */
	protected function get_cords_form_zip($zip, $default_country, &$error)
	{

		$this->file_downloader = new \phpbb\file_downloader();
		try
		{
			$info = $this->file_downloader->get('maps.google.com', '/maps/api/geocode', 'json?address=' . $default_country . '%20' . urlencode($zip), 80);
		}
		catch (\phpbb\exception\runtime_exception $exception)
		{
			$prepare_parameters = array_merge(array($exception->getMessage()), $exception->get_parameters());
			throw new \RuntimeException(call_user_func_array(array($this->user, 'lang'), $prepare_parameters));
		}
		$error_message = $this->file_downloader->get_error_string();
		if (!empty($error_message))
		{
			$error[] = $error_message;
		}

		$info = json_decode($info, true);
		if (isset($info['results']['0']['geometry']['location']))
		{
			return array(
				'lon'		=> substr($this->_randomize_coordinate($info['results']['0']['geometry']['location']['lng']), 0, 10),
				'lat'		=> substr($this->_randomize_coordinate($info['results']['0']['geometry']['location']['lat']), 0, 10),
			);
		}
		else
		{
			$error[] = $this->user->lang('ERROR_GET_COORDINATES');
		}
	}

	private function _randomize_coordinate($coordinate)
	{
		$rand = rand(11111, 99999);
		return number_format($coordinate, 2) . $rand;
	}

	protected function marker_image_select($marker, $path)
	{
		$path = $this->phpbb_extension_manager->get_extension_path('tas2580/usermap', true) . $path;

		if (!function_exists('filelist'))
		{
			include($this->phpbb_root_path . '/includes/functions_admin.' . $this->php_ext);
		}

		$imglist = filelist($path);

		$edit_img = $filename_list = '';

		foreach ($imglist as $path => $img_ary)
		{
			sort($img_ary);

			foreach ($img_ary as $img)
			{
				$img = $path . $img;

				if ($img == $marker)
				{
					$selected = ' selected="selected"';
					$edit_img = $img;
				}
				else
				{
					$selected = '';
				}

				if (strlen($img) > 255)
				{
					continue;
				}

				$filename_list .= '<option value="' . htmlspecialchars($img) . '"' . $selected . '>' . $img . '</option>';
			}
		}

		return '<option value=""' . (($edit_img == '') ? ' selected="selected"' : '') . '>----------</option>' . $filename_list;
	}

	/**
	 * Generate the array with country codes
	 *
	 * @param string	$sel	Selected country
	 */
	protected function country_code_select($sel)
	{
		$cc_a = array('AD', 'AE', 'AF', 'AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'AO', 'AR', 'AS', 'AT', 'AU', 'AW', 'AZ');
		$cc_b = array('BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BR', 'BS', 'BT', 'BW', 'BY', 'BZ');
		$cc_c = array('CA', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 'CU', 'CV', 'CW', 'CY', 'CZ');
		$cc_d = array('DE', 'DJ', 'DK', 'DM', 'DO', 'DZ');
		$cc_e = array('EC', 'EE', 'EG', 'EH', 'ER', 'ES', 'ET');
		$cc_f = array('FK', 'FI', 'FJ', 'FO', 'FM', 'FR');
		$cc_g = array('GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GQ', 'GP', 'GR', 'GT', 'GU', 'GW', 'GY');
		$cc_h = array('HK', 'HN', 'HR', 'HT', 'HU');
		$cc_i = array('ID', 'IE', 'IL', 'IM', 'IN', 'IO', 'IR', 'IS', 'IT');
		$cc_j = array('JE', 'JM', 'JO');
		$cc_k = array('KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ');
		$cc_l = array('LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY');
		$cc_m = array('MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ');
		$cc_n = array('NA', 'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ');
		$cc_o = array('OM');
		$cc_p = array('PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PR', 'PS', 'PT', 'PW', 'PY');
		$cc_q = array('QA');
		$cc_r = array('RE', 'RO', 'RS', 'RU', 'RW');
		$cc_s = array('SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SX', 'SY', 'SZ');
		$cc_t = array('TC', 'TD', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TR', 'TT', 'TV', 'TW', 'TZ');
		$cc_u = array('UA', 'UG', 'US', 'UY', 'UZ');
		$cc_v = array('VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU');
		$cc_w = array('WF', 'WS');
		$cc_x = array('XK');
		$cc_y = array('YE', 'YT');
		$cc_z = array('ZA', 'ZM', 'ZW');

		$countrys = array_merge($cc_a, $cc_b, $cc_c, $cc_d, $cc_e, $cc_f, $cc_g, $cc_h, $cc_i, $cc_j, $cc_k, $cc_l, $cc_m, $cc_n, $cc_o, $cc_p, $cc_q, $cc_r, $cc_s, $cc_t, $cc_u, $cc_v, $cc_w, $cc_x, $cc_y, $cc_z);
		$country_code = array();
		foreach ($countrys as $country)
		{
			$country_code[$country] = $this->user->lang('CC_' . $country);
		}

		array_multisort($country_code, SORT_ASC, SORT_LOCALE_STRING);

		$options = '';
		foreach ($country_code as $cc => $name)
		{
			$selected = ($cc == strtoupper($sel)) ? ' selected="selected"' : '';
			$options .= '<option' . $selected . ' value="' . $cc . '">' . $name . '</option>';
		}
		return $options;
	}
}
