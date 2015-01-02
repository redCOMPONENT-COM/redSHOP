<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_magicmagnifyplus
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


/**
 * Magic Magnify Plus Module for Joomla 1.5
 *
 * @version   2.11.2.0
 * @author    Magic Toolbox
 * @copyright (C) 2009 Magic Toolbox
 * @license   http://www.magictoolbox.com/license/
 **/
defined('_JEXEC') or die;

require_once dirname(__FILE__) . "/magicmagnifyplus.module.core.class.php";

class redMagicMagnifyPlus
{
	var $params = Array();
	var $conf = Array();
	var $content = "";
	var $baseurl = "";
	var $page = "";
	var $coreClass = "";

	function redMagicMagnifyPlus($params)
	{
		$this->params = $params;

		$this->baseurl = JURI::base() . '/modules/mod_redmagicmagnifyplus/core';

		$coreClassName   = 'MagicMagnifyPlusModuleCoreClass';
		$this->coreClass = new $coreClassName;

		if (isset($_REQUEST["page"])) $this->page = trim($_REQUEST["page"]);

		$this->loadConf();

		$this->registerEvent('onAfterRender', 'redMagicMagnifyPlusLoad');
	}

	function registerEvent($event, $handler)
	{
		/* can't use $app->registerEvent function when System.Cache plugin activated */
		$dispatcher             = JDispatcher::getInstance();
		$obs                    = Array("event" => $event, "handler" => $handler);
		$dispatcher->_observers = array_merge(Array($obs), $dispatcher->_observers);
	}

	function loadConf()
	{

		$this->conf = $this->coreClass->params;

		foreach ($this->conf->getArray() as $key => $c)
		{
			$value = $this->params->get($key, "__default__");
			if ($c["type"] == 'text' && $value == 'none') $value = "";
			if ($value !== "__default__")
			{
				$this->conf->set($key, $value);
			}
		}

		// for MT module
		$this->conf->set('caption_source', 'Title');

		// for MM and MMP modules (centered thumbnails)
		$this->conf->set('containerDisplay', 'inline-block');

	}

	function load()
	{

		$this->content = JResponse::toString();

		/* load JS and CSS */
		if (!defined('MagicMagnifyPlus_HEADERS_LOADED'))
		{
			$pattern       = '/<\/head>/is';
			$this->content = preg_replace_callback($pattern, array($this, "loadJSCSSCallback"), $this->content);
			define('MagicMagnifyPlus_HEADERS_LOADED', true);
		}

		$this->content = preg_replace('/(<body.*?>)/is', '$1' . $this->coreClass->addonsTemplate($this->baseurl), $this->content);

		$this->content = preg_replace_callback("/<a([^>]*?class=\"[^\"]*?MagicMagnifyPlus[^\"]*?\"[^>]*)>(.*?)<\/a>/is", array($this, "loadIMGCallback"), $this->content);

		JResponse::setBody($this->content);
	}

	function loadJSCSSCallback($matches)
	{
		return $this->coreClass->headers($this->baseurl) . $matches[0];
	}

	function loadIMGCallback($matches)
	{

		$img = preg_replace("/^.*?href=\"(.*?)\".*$/is", "$1", $matches[1]);
		if ($img == $matches[1]) $img = '';

		$thumb = preg_replace("/^.*?src=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($thumb == $matches[2]) $thumb = '';

		$id = preg_replace("/^.*?id=\"(.*?)\".*$/is", "$1", $matches[1]);
		if ($id == $matches[1]) $id = '';
		if (preg_match('/MagicMagnifyPlusImage/is', $id)) return $matches[0];

		$alt = preg_replace("/^.*?alt=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($alt == $matches[2]) $alt = '';

		$title = preg_replace("/^.*?title=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($title == $matches[2]) $title = '';

		$titleA = preg_replace("/^.*?title=\"(.*?)\".*$/is", "$1", $matches[1]);
		if ($titleA == $matches[1]) $titleA = '';

		$caption  = '';
		$mosimage = false;
		if ($alt == $title && $alt == 'Image')
		{
			$mosimage = true;
			$alt      = $title = '';
		}
		if (preg_match('/mosimage/is', $matches[2]))
		{
			$mosimage = true;
			$caption  = preg_replace('/^.*?<div[^>]*?class=\"mosimage_caption\"[^>]*>(.*?)<\/div>.*$/is', '$1', $matches[2]);
		}

		$title = empty($caption) ? (empty($alt) ? (empty($title) ? $titleA : $title) : $alt) : $caption;

		if (!empty($title))
		{
			$title = html_entity_decode($title);
			$title = str_replace('mce_thref', 'href', $title);
		}

		$width = preg_replace("/^.*?width=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($width == $matches[2]) $width = '';

		$height = preg_replace("/^.*?height=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($height == $matches[2]) $height = '';

		$align = preg_replace("/^.*?align=\"(.*?)\".*$/is", "$1", $matches[2]);
		if ($align == $matches[2]) $align = '';

		$html = $this->coreClass->template(compact('img', 'thumb', 'id', 'title', 'width', 'height'));

		if (!empty($id)) $html = preg_replace("/(<a[^>]*?id=\")MagicMagnifyPlusImage/s", "$1", $html);

		$rel = preg_replace("/^.*?rel=\"(.*?)\".*$/is", "$1", $matches[1]);
		if ($rel != $matches[1])
		{
			$relOrig = preg_replace("/^.*?rel=\"(.*?)\".*$/is", "$1", $html);
			if ($relOrig != $html)
			{
				$rel       = explode(';', $rel);
				$relOrig   = explode(';', $relOrig);
				$relResult = Array();
				foreach ($rel as $r)
				{
					if (empty($r)) continue;
					$rr                = explode(':', $r);
					$relResult[$rr[0]] = $rr[1];
				}
				foreach ($relOrig as $r)
				{
					if (empty($r)) continue;
					$rr = explode(':', $r);
					if (!isset($relResult[$rr[0]])) $relResult[$rr[0]] = $rr[1];
				}
				$rel = array();
				foreach ($relResult as $k => $v)
				{
					$rel[] = $k . ':' . $v;
				}
				$rel = implode(';', $rel) . ';';
			}
			$html = preg_replace("/^(.*?rel=\").*?(\".*)$/is", "$1{$rel}$2", $html);
		}

		$html = preg_replace("/^(<a.*?<\/a>).*$/iUs", "$1", $html);

		$message = trim($this->conf->getValue('message'));
		if (!empty($message) && $this->conf->checkValue("show_message", "Yes"))
		{
			$html = $html . "<br />" . $message;
		}

		//$html = "<div style=\"float:{$align}; margin: 0 3px 0 0;\">{$html}</div>";
		if (!empty($align)) $float = "float:" . $align;
		else $float = "";
		$html = "<b style=\"display: block; font-weight: normal; {$float}; margin: 0 3px 0 0; text-align: center;\">{$html}</b>";

		return $html;
	}
}

$GLOBALS["magictoolbox"]["magicmagnifyplus"] = new redMagicMagnifyPlus($params);

function redMagicMagnifyPlusLoad()
{
	$GLOBALS["magictoolbox"]["magicmagnifyplus"]->load();
}

