<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Product architecture
 *
 * @package     Redshop.Library
 * @subpackage  Product
 * @since       1.5
 */
class RedshopProduct
{
	protected $info = null;

	public function __construct($id)
	{
		if (!is_int($id))
		{
			throw new InvalidArgumentException(
				JText::sprintf('LIB_REDSHOP_PRODUCT_ID_NOT_VALID', __CLASS__),
				1
			);
		}

		$this->info = RedshopHelperProduct::getProductById($id);

		if (empty($this->info))
		{
			throw new Exception("Error Processing Request", 1);
		}
	}

	public function getId()
	{
		return (int) $this->info->product_id;
	}

	public function getName()
	{
		return $this->info->product_name;
	}

	public function getPrice()
	{
		JLog::add('Don\'t use this function, still under developement.');
	}
}
