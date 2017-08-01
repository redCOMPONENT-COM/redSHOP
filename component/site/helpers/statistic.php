<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Statistic helper
 *
 * @since  1.5
 */
class statistic
{
	protected static $instance = null;

	/**
	 * Returns the productHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  self  The productHelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Add entry to statistics
	 *
	 * @return  void
	 */
	public function track()
	{
		// Only when enabled in configuration
		if (Redshop::getConfig()->get('STATISTICS_ENABLE'))
		{
			RedshopHelperStatistic::recordVisitor();
			RedshopHelperStatistic::recordPage();
		}
	}

	/**
	 * Method for store view of page.
	 *
	 * @return  bool  True on success. False otherwise.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperStatistic::recordVisitor() instead.
	 */
	public function reshop_visitors()
	{
		return RedshopHelperStatistic::recordVisitor();
	}

	/**
	 * Method for store view of page.
	 *
	 * @return  bool  True on success. False otherwise.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperStatistic::recordPage() instead.
	 */
	public function reshop_pageview()
	{
		return RedshopHelperStatistic::recordPage();
	}
}
