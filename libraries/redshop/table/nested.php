<?php
/**
 * @package     Redshop
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Redshop\Table\AbstractNestedTable;

defined('_JEXEC') or die;

JLoader::import('joomla.database.tablenested');

/**
 * redSHOP Base Table
 *
 * @package     Redshop
 * @subpackage  Base
 * @since       1.0
 */
class RedshopTableNested extends AbstractNestedTable
{
	/**
	 * Prefix to add to log files
	 *
	 * @var  string
	 */
	protected $logPrefix = 'redshop';
}
