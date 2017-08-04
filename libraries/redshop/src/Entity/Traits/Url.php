<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

namespace Redshop\Entity\Traits;

defined('_JEXEC') or die;

/**
 * Trait Url
 * @package Redshop\Entity\Traits
 *
 * @since   2.0.7
 */
trait Url
{
	/**
	 * Format a link
	 *
	 * @param   string   $url     Url to format
	 * @param   boolean  $routed  Process Url through JRoute?
	 * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
	 *
	 * @return  string
	 */
	protected function formatUrl($url, $routed = true, $xhtml = true)
	{
		if (!$url)
		{
			return null;
		}

		if (!$routed)
		{
			return $url;
		}

		return \JRoute::_($url, $xhtml);
	}
}
