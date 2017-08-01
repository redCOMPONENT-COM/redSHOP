<?php
/**
 * @package     Redshop
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Table;

defined('_JEXEC') or die;

/**
 * Describes methods required by Aesir tables.
 *
 * @since  2.0.3
 */
interface TableInterface
{
	/**
	 * Gets the name of the latest extending class.
	 * For a class named ContentTableArticles will return Articles
	 *
	 * @return  string
	 */
	public function getInstanceName();

	/**
	 * Get the class prefix
	 *
	 * @return  string
	 */
	public function getInstancePrefix();
}
