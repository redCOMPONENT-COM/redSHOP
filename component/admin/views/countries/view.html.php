<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2.1
 */

class RedshopViewCountries extends RedshopViewList
{
	/**
	 * Column for render published state.
	 *
	 * @var    array
	 * @since  2.0.7
	 */
	protected $stateColumns = array();

	/**
	 * Display check-in button or not.
	 *
	 * @var   boolean
	 * @since  2.0.7
	 */
	protected $checkIn = false;
}
