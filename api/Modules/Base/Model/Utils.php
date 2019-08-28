<?php

namespace Tulic\aPiE\Base;

/**
 * Description of Utils
 *
 * @author Peter Tulic
 */
class Utils
{

	public static function dashToPascal($text)
	{
		$ret = str_replace('-', ' ', $text);
		$ret = ucwords($ret);
		$ret = str_replace(' ', '', $ret);

		return $ret;
	}

	public static function getDefinition($name)
	{
		if (file_exists($filename = 'Definitions/' . $name . '.json')) {
			$content = file_get_contents($filename);
			return json_decode($content, true);
		}
		return false;
	}
}
