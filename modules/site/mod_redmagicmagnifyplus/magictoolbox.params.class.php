<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_magicmagnifyplus
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!in_array('MagicToolboxParams', get_declared_classes()))
{

	class MagicToolboxParams
	{
		var $params;

		function MagicToolboxParams()
		{
			$params = array();
		}

		function append($id, $value)
		{
			if (!is_array($value))
			{
				$this->params[$id]["value"] = $value;
			}
			else
			{
				foreach ($value as $k => $v)
				{
					$this->params[$id][$k] = $v;
				}
			}
		}

		function appendArray($params)
		{
			foreach ($params as $key => $param)
			{
				$this->append($key, $param);
			}
		}

		function get($id)
		{
			return isset($this->params[$id]) ? $this->params[$id] : false;
		}

		function set($id, $value)
		{
			$this->params[$id]['value'] = $value;
		}

		function getValue($id)
		{
			$p = $this->get($id);
			if ($p)
			{
				return isset($p['value']) ? $p['value'] : $p['default'];
			}
			else return false;
		}

		function checkValue($id, $value = false)
		{
			if (!is_array($value)) $value = array($value);
			if (in_array($this->getValue($id), $value)) return true;
			else return false;
		}

		function getArray()
		{
			return $this->params;
		}

		function getNames()
		{
			return array_keys($this->params);
		}

		function loadINI($file)
		{
			if (!file_exists($file)) return false;
			$ini = file($file);
			foreach ($ini as $num => $line)
			{
				$line = trim($line);
				if (empty($line) || in_array(substr($line, 0, 1), array(';', '#'))) continue;
				$cur = explode('=', $line);
				if (count($cur) != 2)
				{
					error_log("WARNING: You have errors in you INI file ({$file}) on line " . ($num + 1) . "!");
					continue;
				}
				$this->set(trim($cur[0]), trim($cur[1]));
			}

			return true;
		}

	}

}
?>
