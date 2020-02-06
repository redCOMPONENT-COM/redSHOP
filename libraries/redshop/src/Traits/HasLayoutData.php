<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Twig
 *
 ** @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Twig\Traits;

defined('_JEXEC') || die;

/**
 * For classes having cached layout data.
 *
 * @since  1.1.0
 */
trait HasLayoutData
{
	/**
	 * Layout data for the views.
	 *
	 * @var    array
	 */
	protected $layoutData = [];

	/**
	 * Get the data that will be sent to renderer.
	 *
	 * @return  array
	 */
	protected function getLayoutData()
	{
		if (!isset($this->layoutData[__CLASS__]))
		{
			$this->layoutData[__CLASS__] = $this->loadLayoutData();
		}

		return $this->layoutData[__CLASS__];
	}

	/**
	 * Load layout data.
	 *
	 * @return  array
	 */
	abstract protected function loadLayoutData();
}
