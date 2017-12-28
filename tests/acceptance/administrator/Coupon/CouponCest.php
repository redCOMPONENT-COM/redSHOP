<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Class CouponCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CouponCest extends AbstractCest
{
	use Cest\Traits\CheckIn, Cest\Traits\Publish;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'code';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'code'        => $this->faker->bothify('Coupon Code ?##?'),
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
		);
	}

	/**
	 * Method for set new data.
	 *
	 * @param   string  $oldName  Old name
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array(
			'code'        => 'New ' . $this->dataNew['code'],
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
		);
	}

	/**
	 * Method for set form fields.
	 *
	 * @return  array
	 */
	protected function prepareFormFields()
	{
		/* @TODO Need auto-generate by parse from XML file (admin/models/<class>.xml) */

		return array(
			'code'        => array('type' => 'text', 'xpath' => ['id' => 'jform_code']),
			'type'        => array('type' => 'radio', 'xpath' => ['id' => 'jform_type']),
			'value'       => array('type' => 'text', 'xpath' => ['id' => 'jform_value']),
			'effect'      => array('type' => 'radio', 'xpath' => ['id' => 'jform_effect']),
			'amount_left' => array('type' => 'text', 'xpath' => ['id' => 'jform_amount_left'])
		);
	}
}
