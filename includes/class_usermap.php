<?php

namespace tas2580\usermap\includes;

class class_usermap
{
	const REGEX_LON =  '#^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$#';
	const REGEX_LAT =  '#^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$#';

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
		$distance = 0;
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
}
