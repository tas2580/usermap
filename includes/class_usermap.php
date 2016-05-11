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
	protected function get_cords_form_zip($zip, &$error)
	{

		$this->file_downloader = new \phpbb\file_downloader();
		try
		{
			$info = $this->file_downloader->get('maps.google.com', '/maps/api/geocode', 'json?address=' . $zip, 80);
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

}
