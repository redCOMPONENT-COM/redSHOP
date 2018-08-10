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
 * Class Account
 * @package Redshop\Paygate\Quickpay\Merchant
 *
 * @since   0.5.0
 */
class Ping extends AbstractBase
{
	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function post($parameters = array(), $request = 'ping')
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
	public function get($parameters = array(), $request = 'ping')
	{
		return parent::get($parameters, $request);
	}
}
