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
 * View Tax Rates
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.6
 */
class RedshopViewTax_Rates extends RedshopViewList
{
	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$value = $row->{$config['dataCol']};

		switch ($config['dataCol'])
		{
			case 'tax_group_id':
				return '<a href="index.php?option=com_redshop&task=tax_group.edit&id=' . $value . '">'
					. $row->tax_group_name . '</a>';

			case 'tax_country':
				return $row->country_name;

			case 'tax_state':
				return $row->state_name;

			case 'tax_rate':
				return number_format(
					$value * 100,
					2,
					Redshop::getConfig()->get('PRICE_SEPERATOR'),
					Redshop::getConfig()->get('THOUSAND_SEPERATOR')
				) . ' %';

			default:
				return parent::onRenderColumn($config, $index, $row);
		}
	}
}
