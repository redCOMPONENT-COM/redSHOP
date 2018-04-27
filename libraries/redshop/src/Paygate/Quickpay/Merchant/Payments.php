<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Paygate\Quickpay\Merchant;

use Redshop\Paygate\Quickpay\AbstractBase;

/**
 * Class Payments
 * @package Redshop\Paygate\Quickpay\Merchant
 *
 * @since   0.5.0
 */
class Payments extends AbstractBase
{
	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function get($parameters = array(), $request = 'payments')
	{
		return parent::get($parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function post($parameters = array(), $request = 'payments')
	{
		return parent::post($parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function put($parameters = array(), $request = 'payments')
	{
		return parent::put($parameters, $request);
	}

	/**
	 * @param   int $orderId Order id
	 *
	 * @return  boolean|null|\stdClass
	 * @throws  \ErrorException
	 */
	public function getByOrderId($orderId)
	{
		return $this->get(array('order_id' => $orderId));
	}
}
