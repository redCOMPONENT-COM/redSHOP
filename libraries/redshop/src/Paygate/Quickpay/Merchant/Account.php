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
class Account extends AbstractBase
{
	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function put($parameters = array(), $request = 'account')
	{
		return parent::put($parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function get($parameters = array(), $request = 'account')
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
	public function patch($parameters = array(), $request = 'account')
	{
		return parent::patch($parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function delete($parameters = array(), $request = 'account')
	{
		return parent::patch($parameters, $request);
	}

	/**
	 * @return boolean|null|\stdClass
	 * @throws \ErrorException
	 */
	public function getPrivateKey()
	{
		return $this->get(array(), 'account/private-key');
	}

	/**
	 * @return boolean|null|\stdClass
	 * @throws \ErrorException
	 */
	public function getPlatform()
	{
		return $this->get(array(), 'account/04-platform');
	}
}
