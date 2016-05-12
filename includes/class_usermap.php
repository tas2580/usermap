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
			$info = $this->file_downloader->get('maps.google.com', '/maps/api/geocode', 'json?address=' . $zip . '%20' . $default_country, 80);
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
		return isset($info['results']['0']['geometry']['location']) ? $info['results']['0']['geometry']['location'] : '';
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
		$country_code = array(
			'AD'		=> $this->user->lang('CC_AD'),
			'AE'		=> $this->user->lang('CC_AE'),
			'AF'		=> $this->user->lang('CC_AF'),
			'AG'		=> $this->user->lang('CC_AG'),
			'AI'		=> $this->user->lang('CC_AI'),
			'AL'		=> $this->user->lang('CC_AL'),
			'AM'		=> $this->user->lang('CC_AM'),
			'AO'		=> $this->user->lang('CC_AO'),
			'AR'		=> $this->user->lang('CC_AR'),
			'AS'		=> $this->user->lang('CC_AS'),
			'AT'		=> $this->user->lang('CC_AT'),
			'AU'		=> $this->user->lang('CC_AU'),
			'AW'		=> $this->user->lang('CC_AW'),
			'AZ'		=> $this->user->lang('CC_AZ'),
			'BA'		=> $this->user->lang('CC_BA'),
			'BB'		=> $this->user->lang('CC_BB'),
			'BD'		=> $this->user->lang('CC_BD'),
			'BE'		=> $this->user->lang('CC_BE'),
			'BF'		=> $this->user->lang('CC_BF'),
			'BG'		=> $this->user->lang('CC_BG'),
			'BH'		=> $this->user->lang('CC_BH'),
			'BI'		=> $this->user->lang('CC_BI'),
			'BJ'		=> $this->user->lang('CC_BJ'),
			'BL'		=> $this->user->lang('CC_BL'),
			'BM'		=> $this->user->lang('CC_BM'),
			'BN'		=> $this->user->lang('CC_BN'),
			'BO'		=> $this->user->lang('CC_BO'),
			'BQ'		=> $this->user->lang('CC_BQ'),
			'BR'		=> $this->user->lang('CC_BR'),
			'BS'		=> $this->user->lang('CC_BS'),
			'BT'		=> $this->user->lang('CC_BT'),
			'BW'		=> $this->user->lang('CC_BW'),
			'BY'		=> $this->user->lang('CC_BY'),
			'BZ'		=> $this->user->lang('CC_BZ'),
			'CA'		=> $this->user->lang('CC_CA'),
			'CD'		=> $this->user->lang('CC_CD'),
			'CF'		=> $this->user->lang('CC_CF'),
			'CG'		=> $this->user->lang('CC_CG'),
			'CH'		=> $this->user->lang('CC_CH'),
			'CI'		=> $this->user->lang('CC_CI'),
			'CK'		=> $this->user->lang('CC_CK'),
			'CL'		=> $this->user->lang('CC_CL'),
			'CM'		=> $this->user->lang('CC_CM'),
			'CN'		=> $this->user->lang('CC_CN'),
			'CO'		=> $this->user->lang('CC_CO'),
			'CR'		=> $this->user->lang('CC_CR'),
			'CU'		=> $this->user->lang('CC_CU'),
			'CV'		=> $this->user->lang('CC_CV'),
			'CW'		=> $this->user->lang('CC_CW'),
			'CY'		=> $this->user->lang('CC_CY'),
			'CZ'		=> $this->user->lang('CC_CZ'),
			'DE'		=> $this->user->lang('CC_DE'),
			'DJ'		=> $this->user->lang('CC_DJ'),
			'DK'		=> $this->user->lang('CC_DK'),
			'DM'		=> $this->user->lang('CC_DM'),
			'DO'		=> $this->user->lang('CC_DO'),
			'DZ'		=> $this->user->lang('CC_DZ'),
			'EC'		=> $this->user->lang('CC_EC'),
			'EE'		=> $this->user->lang('CC_EE'),
			'EG'		=> $this->user->lang('CC_EG'),
			'EH'		=> $this->user->lang('CC_EH'),
			'ER'		=> $this->user->lang('CC_ER'),
			'ES'		=> $this->user->lang('CC_ES'),
			'ET'		=> $this->user->lang('CC_ET'),
			'FK'		=> $this->user->lang('CC_FK'),
			'FI'		=> $this->user->lang('CC_FI'),
			'FJ'		=> $this->user->lang('CC_FJ'),
			'FO'		=> $this->user->lang('CC_FO'),
			'FM'		=> $this->user->lang('CC_FM'),
			'FR'		=> $this->user->lang('CC_FR'),
			'GA'		=> $this->user->lang('CC_GA'),
			'GB'		=> $this->user->lang('CC_GB'),
			'GD'		=> $this->user->lang('CC_GD'),
			'GE'		=> $this->user->lang('CC_GE'),
			'GF'		=> $this->user->lang('CC_GF'),
			'GG'		=> $this->user->lang('CC_GG'),
			'GH'		=> $this->user->lang('CC_GH'),
			'GI'		=> $this->user->lang('CC_GI'),
			'GL'		=> $this->user->lang('CC_GL'),
			'GM'		=> $this->user->lang('CC_GM'),
			'GN'		=> $this->user->lang('CC_GN'),
			'GQ'		=> $this->user->lang('CC_GQ'),
			'GP'		=> $this->user->lang('CC_GP'),
			'GR'		=> $this->user->lang('CC_GR'),
			'GT'		=> $this->user->lang('CC_GT'),
			'GU'		=> $this->user->lang('CC_GU'),
			'GW'		=> $this->user->lang('CC_GW'),
			'GY'		=> $this->user->lang('CC_GY'),
			'HK'		=> $this->user->lang('CC_HK'),
			'HN'		=> $this->user->lang('CC_HN'),
			'HR'		=> $this->user->lang('CC_HR'),
			'HT'		=> $this->user->lang('CC_HT'),
			'HU'		=> $this->user->lang('CC_HU'),
			'ID'		=> $this->user->lang('CC_ID'),
			'IE'		=> $this->user->lang('CC_IE'),
			'IL'		=> $this->user->lang('CC_IL'),
			'IM'		=> $this->user->lang('CC_IM'),
			'IN'		=> $this->user->lang('CC_IN'),
			'IO'		=> $this->user->lang('CC_IO'),
			'IQ'		=> $this->user->lang('CC_IQ'),
			'IR'		=> $this->user->lang('CC_IR'),
			'IS'		=> $this->user->lang('CC_IS'),
			'IT'		=> $this->user->lang('CC_IT'),
			'JE'		=> $this->user->lang('CC_JE'),
			'JM'		=> $this->user->lang('CC_JM'),
			'JO'		=> $this->user->lang('CC_JO'),
			'JO'		=> $this->user->lang('CC_JO'),
			'KE'		=> $this->user->lang('CC_KE'),
			'KG'		=> $this->user->lang('CC_KG'),
			'KH'		=> $this->user->lang('CC_KH'),
			'KI'		=> $this->user->lang('CC_KI'),
			'KM'		=> $this->user->lang('CC_KM'),
			'KN'		=> $this->user->lang('CC_KN'),
			'KP'		=> $this->user->lang('CC_KP'),
			'KR'		=> $this->user->lang('CC_KR'),
			'KW'		=> $this->user->lang('CC_KW'),
			'KY'		=> $this->user->lang('CC_KY'),
			'KZ'		=> $this->user->lang('CC_KZ'),
			'LA'		=> $this->user->lang('CC_LA'),
			'LB'		=> $this->user->lang('CC_LB'),
			'LC'		=> $this->user->lang('CC_LC'),
			'LI'		=> $this->user->lang('CC_LI'),
			'LK'		=> $this->user->lang('CC_LK'),
			'LR'		=> $this->user->lang('CC_LR'),
			'LS'		=> $this->user->lang('CC_LS'),
			'LT'		=> $this->user->lang('CC_LT'),
			'LU'		=> $this->user->lang('CC_LU'),
			'LV'		=> $this->user->lang('CC_LV'),
			'LY'		=> $this->user->lang('CC_LY'),
			'MA'		=> $this->user->lang('CC_MA'),
			'MC'		=> $this->user->lang('CC_MC'),
			'MD'		=> $this->user->lang('CC_MD'),
			'ME'		=> $this->user->lang('CC_ME'),
			'MF'		=> $this->user->lang('CC_MF'),
			'MG'		=> $this->user->lang('CC_MG'),
			'MH'		=> $this->user->lang('CC_MH'),
			'MK'		=> $this->user->lang('CC_MK'),
			'ML'		=> $this->user->lang('CC_ML'),
			'MM'		=> $this->user->lang('CC_MM'),
			'MN'		=> $this->user->lang('CC_MN'),
			'MO'		=> $this->user->lang('CC_MO'),
			'MP'		=> $this->user->lang('CC_MP'),
			'MQ'		=> $this->user->lang('CC_MQ'),
			'MR'		=> $this->user->lang('CC_MR'),
			'MS'		=> $this->user->lang('CC_MS'),
			'MT'		=> $this->user->lang('CC_MT'),
			'MU'		=> $this->user->lang('CC_MU'),
			'MV'		=> $this->user->lang('CC_MV'),
			'MW'		=> $this->user->lang('CC_MW'),
			'MX'		=> $this->user->lang('CC_MX'),
			'MY'		=> $this->user->lang('CC_MY'),
			'MZ'		=> $this->user->lang('CC_MZ'),
			'NA'		=> $this->user->lang('CC_NA'),
			'NC'		=> $this->user->lang('CC_NC'),
			'NE'		=> $this->user->lang('CC_NE'),
			'NF'		=> $this->user->lang('CC_NF'),
			'NG'		=> $this->user->lang('CC_NG'),
			'NI'		=> $this->user->lang('CC_NI'),
			'NL'		=> $this->user->lang('CC_NL'),
			'NO'		=> $this->user->lang('CC_NO'),
			'NP'		=> $this->user->lang('CC_NP'),
			'NR'		=> $this->user->lang('CC_NR'),
			'NU'		=> $this->user->lang('CC_NU'),
			'NZ'		=> $this->user->lang('CC_NZ'),
			'OM'		=> $this->user->lang('CC_OM'),
			'PA'		=> $this->user->lang('CC_PA'),
			'PE'		=> $this->user->lang('CC_PE'),
			'PF'		=> $this->user->lang('CC_PF'),
			'PG'		=> $this->user->lang('CC_PG'),
			'PH'		=> $this->user->lang('CC_PH'),
			'PK'		=> $this->user->lang('CC_PK'),
			'PL'		=> $this->user->lang('CC_PL'),
			'PM'		=> $this->user->lang('CC_PM'),
			'PR'		=> $this->user->lang('CC_PR'),
			'PS'		=> $this->user->lang('CC_PS'),
			'PT'		=> $this->user->lang('CC_PT'),
			'PW'		=> $this->user->lang('CC_PW'),
			'PY'		=> $this->user->lang('CC_PY'),
			'QA'		=> $this->user->lang('CC_QA'),
			'RE'		=> $this->user->lang('CC_RE'),
			'RO'		=> $this->user->lang('CC_RO'),
			'RS'		=> $this->user->lang('CC_RS'),
			'RU'		=> $this->user->lang('CC_RU'),
			'RW'		=> $this->user->lang('CC_RW'),
			'SA'		=> $this->user->lang('CC_SA'),
			'SB'		=> $this->user->lang('CC_SB'),
			'SC'		=> $this->user->lang('CC_SC'),
			'SD'		=> $this->user->lang('CC_SD'),
			'SE'		=> $this->user->lang('CC_SE'),
			'SG'		=> $this->user->lang('CC_SG'),
			'SH'		=> $this->user->lang('CC_SH'),
			'SI'		=> $this->user->lang('CC_SI'),
			'SK'		=> $this->user->lang('CC_SK'),
			'SL'		=> $this->user->lang('CC_SL'),
			'SM'		=> $this->user->lang('CC_SM'),
			'SN'		=> $this->user->lang('CC_SN'),
			'SO'		=> $this->user->lang('CC_SO'),
			'SR'		=> $this->user->lang('CC_SR'),
			'SS'		=> $this->user->lang('CC_SS'),
			'ST'		=> $this->user->lang('CC_ST'),
			'SV'		=> $this->user->lang('CC_SV'),
			'SX'		=> $this->user->lang('CC_SX'),
			'SY'		=> $this->user->lang('CC_SY'),
			'SZ'		=> $this->user->lang('CC_SZ'),
			'TC'		=> $this->user->lang('CC_TC'),
			'TD'		=> $this->user->lang('CC_TD'),
			'TG'		=> $this->user->lang('CC_TG'),
			'TH'		=> $this->user->lang('CC_TH'),
			'TJ'		=> $this->user->lang('CC_TJ'),
			'TK'		=> $this->user->lang('CC_TK'),
			'TL'		=> $this->user->lang('CC_TL'),
			'TM'		=> $this->user->lang('CC_TM'),
			'TN'		=> $this->user->lang('CC_TN'),
			'TO'		=> $this->user->lang('CC_TO'),
			'TR'		=> $this->user->lang('CC_TR'),
			'TT'		=> $this->user->lang('CC_TT'),
			'TV'		=> $this->user->lang('CC_TV'),
			'TW'		=> $this->user->lang('CC_TW'),
			'TZ'		=> $this->user->lang('CC_TZ'),
			'UA'		=> $this->user->lang('CC_UA'),
			'UG'		=> $this->user->lang('CC_UG'),
			'US'		=> $this->user->lang('CC_US'),
			'UY'		=> $this->user->lang('CC_UY'),
			'UZ'		=> $this->user->lang('CC_UZ'),
			'VA'		=> $this->user->lang('CC_VA'),
			'VC'		=> $this->user->lang('CC_VC'),
			'VE'		=> $this->user->lang('CC_VE'),
			'VG'		=> $this->user->lang('CC_VG'),
			'VI'		=> $this->user->lang('CC_VI'),
			'VN'		=> $this->user->lang('CC_VN'),
			'VU'		=> $this->user->lang('CC_VU'),
			'WF'		=> $this->user->lang('CC_WF'),
			'WS'		=> $this->user->lang('CC_WS'),
			'XK'		=> $this->user->lang('CC_XK'),
			'YE'		=> $this->user->lang('CC_YE'),
			'YT'		=> $this->user->lang('CC_YT'),
			'ZA'		=> $this->user->lang('CC_ZA'),
			'ZM'		=> $this->user->lang('CC_ZM'),
			'ZW'		=> $this->user->lang('CC_ZW'),
		);

		array_multisort($country_code, SORT_ASC,  0);

		$options = '';
		foreach ($country_code as $cc => $name)
		{
			$selected = ($cc == strtoupper($sel)) ? ' selected="selected"' : '';
			$options .= '<option' . $selected . ' value="' . $cc . '">' . $name . '</option>';
		}
		return $options;
	}
}
