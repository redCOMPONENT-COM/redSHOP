<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Redshop\Entity\AbstractEntity;

defined('_JEXEC') or die;

/**
 * Order Status Log Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityOrder_Status_Log extends AbstractEntity
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  Tableorder_status_log
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Order_Status_Log', 'Table');
	}
}
